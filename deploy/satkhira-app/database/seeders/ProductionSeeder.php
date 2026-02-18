<?php
/**
 * Satkhira Portal - MySQL Database Seeder for Production
 * Run this after migration: php artisan db:seed --class=ProductionSeeder
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin', 'display_name' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'editor', 'display_name' => 'Editor', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'moderator', 'display_name' => 'Moderator', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'user', 'display_name' => 'User', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Users
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Super Admin',
                'email' => 'admin@satkhira.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role_id' => 1,
                'status' => 'active',
                'is_approved' => 1,
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Satkhira Sadar Moderator',
                'email' => 'moderator@satkhira.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role_id' => 3,
                'upazila_id' => 1,
                'status' => 'active',
                'is_approved' => 1,
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Test User',
                'email' => 'user@satkhira.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role_id' => 4,
                'status' => 'active',
                'is_approved' => 1,
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Upazilas
        DB::table('upazilas')->insert([
            ['id' => 1, 'name' => 'Satkhira Sadar', 'name_bn' => 'সাতক্ষীরা সদর', 'slug' => 'satkhira-sadar', 'description' => 'সাতক্ষীরা সদর উপজেলা', 'is_active' => 1, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Kaliganj', 'name_bn' => 'কালীগঞ্জ', 'slug' => 'kaliganj', 'description' => 'কালীগঞ্জ উপজেলা', 'is_active' => 1, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Shyamnagar', 'name_bn' => 'শ্যামনগর', 'slug' => 'shyamnagar', 'description' => 'শ্যামনগর উপজেলা - সুন্দরবনের প্রবেশদ্বার', 'is_active' => 1, 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Assasuni', 'name_bn' => 'আশাশুনি', 'slug' => 'assasuni', 'description' => 'আশাশুনি উপজেলা', 'is_active' => 1, 'order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Debhata', 'name_bn' => 'দেবহাটা', 'slug' => 'debhata', 'description' => 'দেবহাটা উপজেলা', 'is_active' => 1, 'order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Kalaroa', 'name_bn' => 'কলারোয়া', 'slug' => 'kalaroa', 'description' => 'কলারোয়া উপজেলা', 'is_active' => 1, 'order' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Tala', 'name_bn' => 'তালা', 'slug' => 'tala', 'description' => 'তালা উপজেলা', 'is_active' => 1, 'order' => 7, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Categories
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Home Tutor', 'name_bn' => 'হোম টিউটর', 'slug' => 'home-tutor', 'description' => 'হোম টিউটর খুঁজুন', 'icon' => 'fa-graduation-cap', 'color' => '#007bff', 'is_active' => 1, 'show_in_menu' => 1, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'To-Let', 'name_bn' => 'টু-লেট', 'slug' => 'to-let', 'description' => 'ভাড়ার জন্য বাসা/ফ্ল্যাট', 'icon' => 'fa-home', 'color' => '#28a745', 'is_active' => 1, 'show_in_menu' => 1, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Restaurant', 'name_bn' => 'রেস্টুরেন্ট', 'slug' => 'restaurant', 'description' => 'রেস্টুরেন্ট ও খাবারের দোকান', 'icon' => 'fa-utensils', 'color' => '#dc3545', 'is_active' => 1, 'show_in_menu' => 1, 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Hospital', 'name_bn' => 'হাসপাতাল', 'slug' => 'hospital', 'description' => 'হাসপাতাল ও ক্লিনিক', 'icon' => 'fa-hospital', 'color' => '#17a2b8', 'is_active' => 1, 'show_in_menu' => 1, 'order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'School', 'name_bn' => 'স্কুল', 'slug' => 'school', 'description' => 'স্কুল ও শিক্ষা প্রতিষ্ঠান', 'icon' => 'fa-school', 'color' => '#6f42c1', 'is_active' => 1, 'show_in_menu' => 1, 'order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'College', 'name_bn' => 'কলেজ', 'slug' => 'college', 'description' => 'কলেজ ও উচ্চ শিক্ষা প্রতিষ্ঠান', 'icon' => 'fa-university', 'color' => '#fd7e14', 'is_active' => 1, 'show_in_menu' => 1, 'order' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Pharmacy', 'name_bn' => 'ফার্মেসি', 'slug' => 'pharmacy', 'description' => 'ঔষধের দোকান', 'icon' => 'fa-pills', 'color' => '#20c997', 'is_active' => 1, 'show_in_menu' => 1, 'order' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'Doctor', 'name_bn' => 'ডাক্তার', 'slug' => 'doctor', 'description' => 'ডাক্তার ও চিকিৎসক', 'icon' => 'fa-user-md', 'color' => '#e83e8c', 'is_active' => 1, 'show_in_menu' => 1, 'order' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'name' => 'Government Office', 'name_bn' => 'সরকারি অফিস', 'slug' => 'government-office', 'description' => 'সরকারি দপ্তর', 'icon' => 'fa-landmark', 'color' => '#343a40', 'is_active' => 1, 'show_in_menu' => 1, 'order' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name' => 'Bank', 'name_bn' => 'ব্যাংক', 'slug' => 'bank', 'description' => 'ব্যাংক ও আর্থিক প্রতিষ্ঠান', 'icon' => 'fa-university', 'color' => '#6610f2', 'is_active' => 1, 'show_in_menu' => 1, 'order' => 10, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Site Settings
        DB::table('site_settings')->insert([
            ['key' => 'site_name', 'value' => 'Explore Satkhira', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_name_bn', 'value' => 'এক্সপ্লোর সাতক্ষীরা', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_description', 'value' => 'সাতক্ষীরা জেলার সকল তথ্যের একটি সম্পূর্ণ পোর্টাল', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_email', 'value' => 'info@satkhira.com', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_phone', 'value' => '+880 1700-000000', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_address', 'value' => 'Satkhira, Bangladesh', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'footer_text', 'value' => '© 2026 Explore Satkhira. All rights reserved. Developed by Metasoft', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/satkhira', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // MP Profile
        DB::table('mp_profiles')->insert([
            [
                'id' => 1,
                'name' => 'Demo MP',
                'name_bn' => 'ডেমো সাংসদ',
                'designation' => 'Member of Parliament',
                'constituency' => 'Satkhira-1',
                'bio' => 'Member of Parliament for Satkhira-1 constituency',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
