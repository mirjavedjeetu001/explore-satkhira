<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('world_cup_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        DB::table('world_cup_settings')->insert([
            ['key' => 'is_enabled', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'show_on_homepage', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'show_floating_button', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'section_title', 'value' => '⚽ FIFA World Cup 2026', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'section_subtitle', 'value' => 'United States · Mexico · Canada | বাংলাদেশ সময় অনুযায়ী', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('world_cup_settings');
    }
};
