<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Role;
use App\Models\Upazila;
use App\Models\User;
use App\Mail\UserApprovedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['role', 'upazila']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('role', fn($q) => $q->where('slug', $request->role));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15);
        $roles = Role::all();
        $upazilas = Upazila::active()->ordered()->get();

        return view('admin.users.index', compact('users', 'roles', 'upazilas'));
    }

    public function create()
    {
        $roles = Role::all();
        $upazilas = Upazila::active()->ordered()->get();
        return view('admin.users.form', compact('roles', 'upazilas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'upazila_id' => 'nullable|exists:upazilas,id',
            'status' => 'required|in:pending,active,suspended',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($validated['status'] === 'active') {
            $validated['approved_at'] = now();
            $validated['approved_by'] = auth()->id();
        }

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['role', 'upazila', 'categoryPermissions']);
        $categories = Category::active()->whereNull('parent_id')->orderBy('name')->get();
        
        return view('admin.users.show', compact('user', 'categories'));
    }

    public function edit(User $user)
    {
        $user->load(['categoryPermissions', 'assignedUpazilas']);
        $roles = Role::all();
        $upazilas = Upazila::active()->ordered()->get();
        $categories = Category::active()->whereNull('parent_id')->orderBy('name')->get();
        return view('admin.users.form', compact('user', 'roles', 'upazilas', 'categories'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:pending,active,suspended',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'approved_categories' => 'nullable|array',
            'approved_categories.*' => 'exists:categories,id',
            'assigned_upazilas' => 'nullable|array',
            'assigned_upazilas.*' => 'exists:upazilas,id',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $wasNotActive = $user->status !== 'active';
        if ($validated['status'] === 'active' && $wasNotActive) {
            $validated['approved_at'] = now();
            $validated['approved_by'] = auth()->id();
        }

        unset($validated['approved_categories']);
        unset($validated['assigned_upazilas']);
        $user->update($validated);

        // Send approval email if status changed to active
        if ($validated['status'] === 'active' && $wasNotActive) {
            try {
                Mail::to($user->email)->send(new UserApprovedMail($user));
            } catch (\Exception $e) {
                \Log::error('Failed to send user approval email: ' . $e->getMessage());
            }
        }

        // Update upazila permissions
        $assignedUpazilas = $request->assigned_upazilas ?? [];
        $user->assignedUpazilas()->sync($assignedUpazilas);

        // Update category permissions
        if ($request->has('approved_categories')) {
            $approvedCategories = $request->approved_categories ?? [];
            
            // Update all category permissions
            foreach ($user->categoryPermissions as $permission) {
                $isApproved = in_array($permission->id, $approvedCategories);
                $user->categoryPermissions()->updateExistingPivot($permission->id, [
                    'is_approved' => $isApproved,
                    'approved_by' => $isApproved ? auth()->id() : null,
                    'approved_at' => $isApproved ? now() : null,
                ]);
            }
            
            // Add new category permissions if admin selects categories not in user request
            $existingCategoryIds = $user->categoryPermissions->pluck('id')->toArray();
            $newCategories = array_diff($approvedCategories, $existingCategoryIds);
            
            foreach ($newCategories as $categoryId) {
                $user->categoryPermissions()->attach($categoryId, [
                    'is_approved' => true,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function approve(User $user)
    {
        $user->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Approve all requested categories
        foreach ($user->categoryPermissions as $permission) {
            $user->categoryPermissions()->updateExistingPivot($permission->id, [
                'is_approved' => true,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
        }

        // Send approval email
        try {
            Mail::to($user->email)->send(new UserApprovedMail($user));
        } catch (\Exception $e) {
            \Log::error('Failed to send user approval email: ' . $e->getMessage());
        }

        return back()->with('success', 'User approved successfully with all requested categories.');
    }

    public function suspend(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        $user->update(['status' => 'suspended']);
        return back()->with('success', 'User suspended successfully.');
    }
}
