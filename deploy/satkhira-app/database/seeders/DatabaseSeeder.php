<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Listing;
use App\Models\MpProfile;
use App\Models\MpQuestion;
use App\Models\News;
use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\Slider;
use App\Models\Upazila;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Full system access', 'permissions' => ['*']],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrative access', 'permissions' => ['manage-users', 'manage-listings', 'manage-comments', 'manage-settings']],
            ['name' => 'Moderator', 'slug' => 'moderator', 'description' => 'Upazila moderator', 'permissions' => ['manage-listings', 'manage-comments']],
            ['name' => 'User', 'slug' => 'user', 'description' => 'Regular user', 'permissions' => ['create-listing', 'comment']],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Create Upazilas of Satkhira
        $upazilas = [
            ['name' => 'Satkhira Sadar', 'name_bn' => 'সাতক্ষীরা সদর', 'description' => 'The central upazila of Satkhira district', 'population' => 450000, 'area' => 370.51],
            ['name' => 'Kaliganj', 'name_bn' => 'কালীগঞ্জ', 'description' => 'A upazila in Satkhira district', 'population' => 380000, 'area' => 324.08],
            ['name' => 'Shyamnagar', 'name_bn' => 'শ্যামনগর', 'description' => 'Famous for Sundarbans', 'population' => 320000, 'area' => 467.40],
            ['name' => 'Assasuni', 'name_bn' => 'আশাশুনি', 'description' => 'A upazila in Satkhira district', 'population' => 280000, 'area' => 306.87],
            ['name' => 'Debhata', 'name_bn' => 'দেবহাটা', 'description' => 'Border upazila', 'population' => 150000, 'area' => 178.46],
            ['name' => 'Kalaroa', 'name_bn' => 'কলারোয়া', 'description' => 'Agricultural upazila', 'population' => 320000, 'area' => 233.91],
            ['name' => 'Tala', 'name_bn' => 'তালা', 'description' => 'A upazila in Satkhira district', 'population' => 330000, 'area' => 344.08],
        ];

        foreach ($upazilas as $upazila) {
            Upazila::create([
                ...$upazila,
                'slug' => Str::slug($upazila['name']),
                'is_active' => true,
            ]);
        }

        // Create Categories based on reference image
        $categories = [
            ['name' => 'Tourist Spots', 'name_bn' => 'পর্যটন কেন্দ্র', 'icon' => 'fa-mountain-sun', 'color' => '#28a745', 'description' => 'Explore beautiful tourist destinations'],
            ['name' => 'Emergency Services', 'name_bn' => 'জরুরী সেবা', 'icon' => 'fa-phone-volume', 'color' => '#dc3545', 'description' => 'Emergency contact numbers and services'],
            ['name' => 'Educational Institutions', 'name_bn' => 'শিক্ষা প্রতিষ্ঠান', 'icon' => 'fa-graduation-cap', 'color' => '#007bff', 'description' => 'Schools, colleges and universities'],
            ['name' => 'Hotels & Restaurants', 'name_bn' => 'হোটেল রেস্টুরেন্ট', 'icon' => 'fa-utensils', 'color' => '#fd7e14', 'description' => 'Hotels and restaurants'],
            ['name' => 'Hospitals', 'name_bn' => 'হাসপাতাল', 'icon' => 'fa-hospital', 'color' => '#17a2b8', 'description' => 'Hospitals and clinics'],
            ['name' => 'Home Tutors', 'name_bn' => 'হোম টিউটর', 'icon' => 'fa-chalkboard-teacher', 'color' => '#6f42c1', 'description' => 'Private tutors for home tuition'],
            ['name' => 'To-Let', 'name_bn' => 'টু লেট', 'icon' => 'fa-home', 'color' => '#20c997', 'description' => 'Houses and apartments for rent'],
            ['name' => 'Volunteer Organizations', 'name_bn' => 'স্বেচ্ছাসেবী সংগঠন', 'icon' => 'fa-hands-helping', 'color' => '#e83e8c', 'description' => 'NGOs and volunteer groups'],
            ['name' => 'Joruri Sheba', 'name_bn' => 'জরুরি সেবা', 'icon' => 'fa-phone-volume', 'color' => '#dc3545', 'description' => 'Emergency services and helplines'],
            ['name' => 'Samajik Songothon', 'name_bn' => 'সামাজিক সংগঠন', 'icon' => 'fa-people-group', 'color' => '#9c27b0', 'description' => 'Social organizations and community groups'],
        ];

        foreach ($categories as $index => $category) {
            Category::create([
                ...$category,
                'slug' => Str::slug($category['name']),
                'is_active' => true,
                'show_in_menu' => true,
                'allow_user_submission' => true,
                'sort_order' => $index,
            ]);
        }

        // Create Super Admin user
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        $moderatorRole = Role::where('slug', 'moderator')->first();
        $userRole = Role::where('slug', 'user')->first();

        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@satkhira.com',
            'password' => Hash::make('password'),
            'role_id' => $superAdminRole->id,
            'status' => 'active',
            'is_verified' => true,
            'approved_at' => now(),
        ]);

        // Create sample moderator
        $satkhiraSadar = Upazila::where('slug', 'satkhira-sadar')->first();
        User::create([
            'name' => 'Satkhira Sadar Moderator',
            'email' => 'moderator@satkhira.com',
            'password' => Hash::make('password'),
            'role_id' => $moderatorRole->id,
            'upazila_id' => $satkhiraSadar->id,
            'status' => 'active',
            'is_verified' => true,
            'approved_at' => now(),
        ]);

        // Create sample user
        User::create([
            'name' => 'Test User',
            'email' => 'user@satkhira.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
            'status' => 'active',
            'is_verified' => true,
            'approved_at' => now(),
        ]);

        // Create Site Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'Satkhira Portal', 'group' => 'general', 'type' => 'text', 'is_public' => true],
            ['key' => 'site_name_bn', 'value' => 'সাতক্ষীরা পোর্টাল', 'group' => 'general', 'type' => 'text', 'is_public' => true],
            ['key' => 'site_tagline', 'value' => 'Explore Satkhira District', 'group' => 'general', 'type' => 'text', 'is_public' => true],
            ['key' => 'site_description', 'value' => 'সাতক্ষীরা জেলার সকল তথ্যের একটি সম্পূর্ণ পোর্টাল', 'group' => 'general', 'type' => 'textarea', 'is_public' => true],
            ['key' => 'site_email', 'value' => 'info@satkhira-portal.com', 'group' => 'general', 'type' => 'email', 'is_public' => true],
            ['key' => 'site_phone', 'value' => '+880 1700-000000', 'group' => 'general', 'type' => 'text', 'is_public' => true],
            ['key' => 'site_address', 'value' => 'Satkhira Sadar, Satkhira, Bangladesh', 'group' => 'general', 'type' => 'textarea', 'is_public' => true],
            ['key' => 'footer_text', 'value' => '© 2024 Satkhira Portal. All rights reserved.', 'group' => 'general', 'type' => 'text', 'is_public' => true],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/satkhiraportal', 'group' => 'social', 'type' => 'url', 'is_public' => true],
            ['key' => 'youtube_url', 'value' => 'https://youtube.com/@satkhiraportal', 'group' => 'social', 'type' => 'url', 'is_public' => true],
            ['key' => 'about_title', 'value' => 'About Satkhira Portal', 'group' => 'about', 'type' => 'text', 'is_public' => true],
            ['key' => 'about_content', 'value' => 'সাতক্ষীরা পোর্টাল হল সাতক্ষীরা জেলার একটি ব্যাপক তথ্য প্ল্যাটফর্ম।', 'group' => 'about', 'type' => 'textarea', 'is_public' => true],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }

        // Create MP Profile
        $mpProfile = MpProfile::create([
            'name' => 'সংসদ সদস্য',
            'name_bn' => 'সংসদ সদস্য',
            'slug' => 'satkhira-mp',
            'designation' => 'Member of Parliament',
            'constituency' => 'Satkhira-1',
            'bio' => 'সাতক্ষীরা-১ আসনের মাননীয় সংসদ সদস্য।',
            'is_active' => true,
        ]);

        // Create Sample Listings
        $admin = User::where('email', 'admin@satkhira.com')->first();

        $sampleListings = [
            ['title' => 'Sundarbans East Wildlife Sanctuary', 'title_bn' => 'সুন্দরবন পূর্ব বন্যপ্রাণী অভয়ারণ্য', 'category' => 'Tourist Spots', 'upazila' => 'Shyamnagar', 'description' => 'The world\'s largest mangrove forest.'],
            ['title' => 'Satkhira District Hospital', 'title_bn' => 'সাতক্ষীরা জেলা হাসপাতাল', 'category' => 'Emergency Services', 'upazila' => 'Satkhira Sadar', 'phone' => '999', 'description' => 'Main government hospital.'],
            ['title' => 'Satkhira Government College', 'title_bn' => 'সাতক্ষীরা সরকারি কলেজ', 'category' => 'Educational Institutions', 'upazila' => 'Satkhira Sadar', 'description' => 'Premier government college.'],
            ['title' => 'Hotel Sundarban', 'title_bn' => 'হোটেল সুন্দরবন', 'category' => 'Hotels & Restaurants', 'upazila' => 'Satkhira Sadar', 'description' => 'Comfortable hotel.'],
            ['title' => 'Satkhira Medical Center', 'title_bn' => 'সাতক্ষীরা মেডিক্যাল সেন্টার', 'category' => 'Hospitals', 'upazila' => 'Satkhira Sadar', 'description' => 'Private medical center.'],
            ['title' => 'English Language Tutor', 'title_bn' => 'ইংরেজি ভাষা শিক্ষক', 'category' => 'Home Tutors', 'upazila' => 'Satkhira Sadar', 'description' => 'Experienced English teacher.'],
            ['title' => '2 Bedroom Apartment', 'title_bn' => '২ বেডরুম ফ্ল্যাট ভাড়া', 'category' => 'To-Let', 'upazila' => 'Satkhira Sadar', 'description' => '2 bedroom apartment for rent.'],
            ['title' => 'Satkhira Youth Foundation', 'title_bn' => 'সাতক্ষীরা যুব ফাউন্ডেশন', 'category' => 'Volunteer Organizations', 'upazila' => 'Satkhira Sadar', 'description' => 'Youth-led organization.'],
        ];

        foreach ($sampleListings as $listingData) {
            $category = Category::where('name', $listingData['category'])->first();
            $upazila = Upazila::where('name', $listingData['upazila'])->first();
            
            Listing::create([
                'user_id' => $admin->id,
                'category_id' => $category->id,
                'upazila_id' => $upazila->id,
                'title' => $listingData['title'],
                'title_bn' => $listingData['title_bn'],
                'slug' => Str::slug($listingData['title']) . '-' . uniqid(),
                'description' => $listingData['description'],
                'phone' => $listingData['phone'] ?? null,
                'address' => $upazila->name . ', Satkhira',
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $admin->id,
            ]);
        }

        // Create Sample News
        News::create([
            'user_id' => $admin->id,
            'title' => 'Welcome to Satkhira Portal',
            'title_bn' => 'সাতক্ষীরা পোর্টালে স্বাগতম',
            'slug' => 'welcome-to-satkhira-portal',
            'excerpt' => 'We are excited to launch the new Satkhira Portal.',
            'content' => 'We are excited to launch the new Satkhira Portal, your one-stop destination for all information about Satkhira district.',
            'type' => 'news',
            'is_active' => true,
        ]);

        // Create Sample MP Question
        MpQuestion::create([
            'mp_profile_id' => $mpProfile->id,
            'name' => 'রহিম উদ্দিন',
            'email' => 'rahim@example.com',
            'question' => 'শ্যামনগর উপজেলায় রাস্তার অবস্থা অত্যন্ত খারাপ।',
            'answer' => 'ধন্যবাদ আপনার প্রশ্নের জন্য। আমরা কাজ করছি।',
            'status' => 'answered',
            'is_public' => true,
            'answered_at' => now(),
        ]);
    }
}
