<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_ticket_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(true);
            $table->string('title')->default('বাস টিকেট বেচাকেনা');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('bus_ticket_settings')->insert([
            'is_enabled' => true,
            'title' => 'বাস টিকেট বেচাকেনা',
            'description' => 'ঈদসহ বিভিন্ন সময়ে অতিরিক্ত বাস টিকেট কিনে থাকলে বা কিনতে চাইলে এখানে বিজ্ঞাপন দিতে পারেন।',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_ticket_settings');
    }
};
