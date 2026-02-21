<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Models\Upazila;
use App\Models\Role;
use App\Mail\RegistrationPendingMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $categories = Category::active()
            ->where('allow_user_submission', true)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();
        
        $upazilas = Upazila::active()->ordered()->get();
        
        return view('auth.register', compact('categories', 'upazilas'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Check if at least one option is selected
        $hasMpQuestion = $request->boolean('mp_question_only');
        $hasCommentOnly = $request->boolean('comment_only');
        $hasCategories = !empty($request->categories);
        $wantsUpazilaModerator = $request->boolean('wants_upazila_moderator');
        $wantsOwnBusinessModerator = $request->boolean('wants_own_business_moderator');
        
        // If comment_only or mp_question_only is selected (without moderator options), categories are optional
        $categoriesRequired = !$hasMpQuestion && !$hasCommentOnly;
        
        // If wants upazila moderator or own business moderator, categories are required
        if ($wantsUpazilaModerator || $wantsOwnBusinessModerator) {
            $categoriesRequired = true;
        }
        
        // Validation rules for categories based on type
        $categoriesRules = ['nullable', 'array'];
        if ($categoriesRequired) {
            $categoriesRules = ['required', 'array', 'min:1'];
        }
        if ($wantsOwnBusinessModerator) {
            // Own business moderator can only select ONE category
            $categoriesRules = ['required', 'array', 'size:1'];
        }
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'nid_number' => ['nullable', 'string', 'max:20'],
            'upazila_id' => ['required', 'exists:upazilas,id'],
            'address' => ['required', 'string', 'max:500'],
            'mp_question_only' => ['nullable', 'boolean'],
            'comment_only' => ['nullable', 'boolean'],
            'wants_upazila_moderator' => ['nullable', 'boolean'],
            'wants_own_business_moderator' => ['nullable', 'boolean'],
            'categories' => $categoriesRules,
            'categories.*' => ['exists:categories,id'],
            'registration_purpose' => ['required', 'string', 'max:1000'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ], [
            'name.required' => 'নাম দিতে হবে',
            'email.required' => 'ইমেইল দিতে হবে',
            'email.unique' => 'এই ইমেইল ইতিমধ্যে ব্যবহৃত হয়েছে',
            'phone.required' => 'মোবাইল নম্বর দিতে হবে',
            'upazila_id.required' => 'উপজেলা নির্বাচন করতে হবে',
            'address.required' => 'ঠিকানা দিতে হবে',
            'categories.required' => 'অন্তত একটি ক্যাটাগরি সিলেক্ট করুন',
            'categories.min' => 'অন্তত একটি ক্যাটাগরি সিলেক্ট করুন',
            'categories.size' => 'নিজের ব্যবসার মডারেটর হতে শুধুমাত্র একটি ক্যাটাগরি সিলেক্ট করুন',
            'registration_purpose.required' => 'রেজিস্ট্রেশনের উদ্দেশ্য লিখতে হবে',
            'password.required' => 'পাসওয়ার্ড দিতে হবে',
            'password.confirmed' => 'পাসওয়ার্ড মিলছে না',
            'avatar.image' => 'শুধুমাত্র ছবি ফাইল আপলোড করুন',
            'avatar.mimes' => 'JPG, PNG অথবা GIF ফাইল আপলোড করুন',
            'avatar.max' => 'ছবির সাইজ সর্বোচ্চ 2MB হতে পারবে',
        ]);

        // Get the default user role
        $userRole = Role::where('slug', 'user')->first();

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'nid_number' => $request->nid_number,
            'upazila_id' => $request->upazila_id,
            'address' => $request->address,
            'registration_purpose' => $request->registration_purpose,
            'wants_mp_questions' => $request->boolean('mp_question_only'),
            'comment_only' => $request->boolean('comment_only'),
            'wants_upazila_moderator' => $request->boolean('wants_upazila_moderator'),
            'wants_own_business_moderator' => $request->boolean('wants_own_business_moderator'),
            'requested_categories' => $request->categories ?? [],
            'password' => Hash::make($request->password),
            'role_id' => $userRole?->id,
            'status' => 'pending', // Account needs admin approval
            'avatar' => $avatarPath,
        ]);

        // Store requested categories (not yet approved) - only if categories selected
        if (!empty($request->categories)) {
            foreach ($request->categories as $categoryId) {
                $user->categoryPermissions()->attach($categoryId, [
                    'is_approved' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Send registration pending email
        try {
            Mail::to($user->email)->send(new RegistrationPendingMail($user));
        } catch (\Exception $e) {
            // Log email error but don't fail registration
            \Log::error('Failed to send registration email: ' . $e->getMessage());
        }

        event(new Registered($user));

        // Don't auto-login - redirect to success page
        return redirect()->route('register.success');
    }

    /**
     * Show registration success page
     */
    public function success(): View
    {
        return view('auth.register-success');
    }
}
