<?php

namespace App\Http\Controllers;

use App\Models\MangoProduct;
use App\Models\MangoSetting;
use App\Models\MangoStore;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MangoController extends Controller
{
    private function requireFeatureEnabled()
    {
        if (!MangoSetting::isEnabled()) {
            return redirect('/')->with('error', 'সাতক্ষীরার আম ফিচার বর্তমানে বন্ধ আছে।');
        }
        return null;
    }

    private function requireSellerLogin()
    {
        if (!session('mango_store_id')) {
            return redirect()->route('mango.login')->with('error', 'অনুগ্রহ করে প্রথমে লগইন করুন।');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;

        $settings = MangoSetting::getSettings();
        $upazilas = Upazila::orderBy('name')->get();
        $selectedUpazila = request('upazila');

        $query = MangoStore::with(['upazila'])
            ->withCount('products')
            ->active()
            ->latest();

        if ($selectedUpazila) {
            $query->where('upazila_id', $selectedUpazila);
        }

        $stores = $query->paginate(12);

        return view('frontend.mango.index', compact('settings', 'stores', 'upazilas', 'selectedUpazila'));
    }

    public function show($id)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;

        $store = MangoStore::with(['upazila', 'products'])->active()->findOrFail($id);
        $store->increment('view_count');

        return view('frontend.mango.show', compact('store'));
    }

    public function registerForm()
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;

        if (session('mango_store_id')) {
            return redirect()->route('mango.dashboard');
        }

        $upazilas = Upazila::orderBy('name')->get();
        return view('frontend.mango.register', compact('upazilas'));
    }

    public function register(Request $request)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;

        $request->validate([
            'owner_name'   => 'required|string|max:100',
            'store_name'   => 'required|string|max:150',
            'phone'        => 'required|string|max:20|unique:mango_stores,phone',
            'password'     => 'required|string|min:6|confirmed',
            'upazila_id'   => 'nullable|exists:upazilas,id',
            'address'      => 'nullable|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'delivery_info'=> 'nullable|string|max:1000',
            'whatsapp'     => 'nullable|string|max:20',
            'facebook_url' => 'nullable|url|max:255',
            'logo'         => 'nullable|image|max:2048',
        ], [
            'phone.unique'       => 'এই মোবাইল নম্বর দিয়ে ইতিমধ্যে একটি স্টোর আছে।',
            'password.confirmed' => 'পাসওয়ার্ড মিলছে না।',
            'password.min'       => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('mango/logos', 'public');
        }

        $store = MangoStore::create([
            'owner_name'    => $request->owner_name,
            'store_name'    => $request->store_name,
            'phone'         => preg_replace('/[^0-9]/', '', $request->phone),
            'password'      => Hash::make($request->password),
            'upazila_id'    => $request->upazila_id,
            'address'       => $request->address,
            'description'   => $request->description,
            'delivery_info' => $request->delivery_info,
            'whatsapp'      => $request->whatsapp ? preg_replace('/[^0-9]/', '', $request->whatsapp) : null,
            'facebook_url'  => $request->facebook_url,
            'logo'          => $logoPath,
        ]);

        session(['mango_store_id' => $store->id]);

        return redirect()->route('mango.dashboard')->with('success', 'স্টোর তৈরি হয়েছে! এখন আমের তথ্য যোগ করুন।');
    }

    public function loginForm()
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;

        if (session('mango_store_id')) {
            return redirect()->route('mango.dashboard');
        }

        return view('frontend.mango.login');
    }

    public function login(Request $request)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;

        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        $store = MangoStore::where('phone', $phone)->first();

        if (!$store || !Hash::check($request->password, $store->password)) {
            return back()->withErrors(['phone' => 'মোবাইল নম্বর বা পাসওয়ার্ড সঠিক নয়।'])->withInput();
        }

        if (!$store->is_active) {
            return back()->withErrors(['phone' => 'আপনার স্টোর বর্তমানে নিষ্ক্রিয় আছে। অ্যাডমিনের সাথে যোগাযোগ করুন।']);
        }

        session(['mango_store_id' => $store->id]);

        return redirect()->route('mango.dashboard')->with('success', 'লগইন সফল হয়েছে!');
    }

    public function logout()
    {
        session()->forget('mango_store_id');
        return redirect()->route('mango.index')->with('success', 'লগআউট হয়েছে।');
    }

    public function dashboard()
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        $store = MangoStore::with(['products', 'upazila'])->findOrFail(session('mango_store_id'));
        $upazilas = Upazila::orderBy('name')->get();

        return view('frontend.mango.dashboard', compact('store', 'upazilas'));
    }

    public function updateStore(Request $request)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        $store = MangoStore::findOrFail(session('mango_store_id'));

        $request->validate([
            'owner_name'    => 'required|string|max:100',
            'store_name'    => 'required|string|max:150',
            'upazila_id'    => 'nullable|exists:upazilas,id',
            'address'       => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'delivery_info' => 'nullable|string|max:1000',
            'whatsapp'      => 'nullable|string|max:20',
            'facebook_url'  => 'nullable|url|max:255',
            'logo'          => 'nullable|image|max:2048',
            'password'      => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->only(['owner_name', 'store_name', 'upazila_id', 'address', 'description', 'delivery_info', 'facebook_url']);

        if ($request->whatsapp) {
            $data['whatsapp'] = preg_replace('/[^0-9]/', '', $request->whatsapp);
        }

        if ($request->hasFile('logo')) {
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $data['logo'] = $request->file('logo')->store('mango/logos', 'public');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $store->update($data);

        return back()->with('success', 'স্টোরের তথ্য আপডেট হয়েছে।');
    }

    public function addProduct(Request $request)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        $store = MangoStore::findOrFail(session('mango_store_id'));

        $request->validate([
            'name'          => 'required|string|max:100',
            'price_per_kg'  => 'required|numeric|min:1|max:9999',
            'min_order_kg'  => 'nullable|numeric|min:0.5|max:999',
            'image'         => 'required|image|max:3072',
            'description'   => 'nullable|string|max:500',
        ], [
            'image.required' => 'আমের ছবি আপলোড করতে হবে।',
            'image.max'      => 'ছবির সাইজ সর্বোচ্চ ৩ MB হতে পারবে।',
        ]);

        $imagePath = $request->file('image')->store('mango/products', 'public');

        $store->products()->create([
            'name'         => $request->name,
            'price_per_kg' => $request->price_per_kg,
            'min_order_kg' => $request->min_order_kg,
            'image'        => $imagePath,
            'description'  => $request->description,
            'sort_order'   => $store->products()->count(),
        ]);

        return back()->with('success', 'আম যোগ করা হয়েছে!');
    }

    public function updateProduct(Request $request, $id)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        $product = MangoProduct::whereHas('store', fn($q) => $q->where('id', session('mango_store_id')))->findOrFail($id);

        $request->validate([
            'name'         => 'required|string|max:100',
            'price_per_kg' => 'required|numeric|min:1|max:9999',
            'min_order_kg' => 'nullable|numeric|min:0.5|max:999',
            'image'        => 'nullable|image|max:3072',
            'description'  => 'nullable|string|max:500',
            'is_available' => 'boolean',
        ]);

        $data = $request->only(['name', 'price_per_kg', 'min_order_kg', 'description']);
        $data['is_available'] = $request->boolean('is_available', true);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('mango/products', 'public');
        }

        $product->update($data);

        return back()->with('success', 'আমের তথ্য আপডেট হয়েছে।');
    }

    public function deleteProduct($id)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        $product = MangoProduct::whereHas('store', fn($q) => $q->where('id', session('mango_store_id')))->findOrFail($id);

        Storage::disk('public')->delete($product->image);
        $product->delete();

        return back()->with('success', 'আম মুছে ফেলা হয়েছে।');
    }
}
