<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('fuel_settings')->insert([
            ['key' => 'is_enabled', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'section_title', 'value' => 'জ্বালানি তেল আপডেট', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'section_subtitle', 'value' => 'সাতক্ষীরার পেট্রোল পাম্পে তেলের বর্তমান অবস্থা', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_settings');
    }
};
