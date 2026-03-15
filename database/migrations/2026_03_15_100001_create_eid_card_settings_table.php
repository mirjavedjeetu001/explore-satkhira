<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eid_card_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(true);
            $table->string('title')->default('ঈদ গ্রিটিং কার্ড মেকার');
            $table->string('description')->nullable();
            $table->timestamps();
        });
        
        // Insert default settings
        DB::table('eid_card_settings')->insert([
            'is_enabled' => true,
            'title' => 'ঈদ গ্রিটিং কার্ড মেকার',
            'description' => 'আপনার ছবি দিয়ে সুন্দর ঈদ কার্ড তৈরি করুন!',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('eid_card_settings');
    }
};
