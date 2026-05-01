<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MangoProduct;
use App\Models\MangoSetting;
use App\Models\MangoStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MangoController extends Controller
{
    public function index(Request $request)
    {
        $settings = MangoSetting::getSettings();

        $query = MangoStore::with('upazila')->withCount('products');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('store_name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $stores = $query->latest()->paginate(25);

        $stats = [
            'total_stores'  => MangoStore::count(),
            'active_stores' => MangoStore::where('is_active', true)->count(),
            'total_products'=> MangoProduct::count(),
            'today_new'     => MangoStore::whereDate('created_at', today())->count(),
        ];

        return view('admin.mango.index', compact('settings', 'stores', 'stats'));
    }

    public function show($id)
    {
        $store = MangoStore::with(['upazila', 'products'])->findOrFail($id);
        return view('admin.mango.show', compact('store'));
    }

    public function toggleStatus()
    {
        $settings = MangoSetting::getSettings();
        $settings->is_enabled = !$settings->is_enabled;
        $settings->save();

        $msg = $settings->is_enabled ? 'সাতক্ষীরার আম ফিচার চালু হয়েছে।' : 'সাতক্ষীরার আম ফিচার বন্ধ হয়েছে।';
        return back()->with('success', $msg);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $settings = MangoSetting::getSettings();
        $settings->update([
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        return back()->with('success', 'সেটিংস আপডেট হয়েছে।');
    }

    public function toggleStoreActive($id)
    {
        $store = MangoStore::findOrFail($id);
        $store->is_active = !$store->is_active;
        $store->save();

        $msg = $store->is_active ? "'{$store->store_name}' স্টোর সক্রিয় করা হয়েছে।" : "'{$store->store_name}' স্টোর নিষ্ক্রিয় করা হয়েছে।";
        return back()->with('success', $msg);
    }

    public function destroy($id)
    {
        $store = MangoStore::with('products')->findOrFail($id);

        foreach ($store->products as $product) {
            Storage::disk('public')->delete($product->image);
        }

        if ($store->logo) {
            Storage::disk('public')->delete($store->logo);
        }

        $store->delete();

        return back()->with('success', 'স্টোর এবং সকল পণ্য মুছে ফেলা হয়েছে।');
    }
}
