<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('celebration_card_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(true);
            $table->string('title')->default('শুভেচ্ছা কার্ড মেকার');
            $table->text('description')->nullable();
            $table->string('brand_name')->default('Explore Satkhira');
            $table->string('brand_tagline')->default('সবার গল্প, সবার পাশে');
            $table->string('headline')->default('দুর্গম যাত্রায় সকলকে অভিনন্দন');
            $table->string('footer_text')->default('এক্সপ্লোর সাতক্ষীরার পক্ষ থেকে');
            $table->timestamps();
        });

        DB::table('celebration_card_settings')->insert([
            'is_enabled' => true,
            'title' => 'শুভেচ্ছা কার্ড মেকার',
            'description' => 'আপনার নাম ও পদবি দিয়ে সুন্দর একটি শুভেচ্ছা কার্ড তৈরি করুন এবং সামাজিক মাধ্যমে শেয়ার করুন।',
            'brand_name' => 'Explore Satkhira',
            'brand_tagline' => 'সবার গল্প, সবার পাশে',
            'headline' => 'দুর্গম যাত্রায় সকলকে অভিনন্দন',
            'footer_text' => 'এক্সপ্লোর সাতক্ষীরার পক্ষ থেকে',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('celebration_card_settings');
    }
};
