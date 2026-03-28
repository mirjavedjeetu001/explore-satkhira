<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'Welcome to Satkhira',
                'title_bn' => 'স্বাগতম সাতক্ষীরায়',
                'subtitle' => 'দক্ষিণ-পশ্চিম বাংলাদেশের এক অপরূপ জেলা। সুন্দরবন, ঐতিহ্য এবং সংস্কৃতির মিলনমেলা।',
                'image' => 'sliders/slider-1-satkhira.jpg',
                'link' => '/upazilas',
                'button_text' => 'উপজেলা দেখুন',
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'title' => 'Gateway to Sundarbans',
                'title_bn' => 'সুন্দরবনের দেশে',
                'subtitle' => 'বিশ্বের বৃহত্তম ম্যানগ্রোভ বনাঞ্চল সুন্দরবনের প্রবেশদ্বার সাতক্ষীরা। রয়েল বেঙ্গল টাইগারের আবাসস্থল।',
                'image' => 'sliders/slider-2-sundarbans.jpg',
                'link' => '/listings?category=travel',
                'button_text' => 'ঘুরে দেখুন',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Explore Satkhira',
                'title_bn' => 'এক্সপ্লোর সাতক্ষীরা',
                'subtitle' => 'তেলের আপডেট, টু-লেট, টিউটর, রেস্টুরেন্ট, হাসপাতাল - সবকিছু এক জায়গায়।',
                'image' => 'sliders/slider-3-explore.jpg',
                'link' => '/listings',
                'button_text' => 'তথ্য খুঁজুন',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::updateOrCreate(
                ['image' => $slider['image']],
                $slider
            );
        }
    }
}
