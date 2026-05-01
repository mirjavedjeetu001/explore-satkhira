<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mango_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(true);
            $table->string('title')->default('সাতক্ষীরার আম');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('mango_settings')->insert([
            'is_enabled' => true,
            'title' => 'সাতক্ষীরার আম',
            'description' => 'সাতক্ষীরার সেরা আম সংগ্রহ করুন সরাসরি বাগান থেকে!',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('mango_settings');
    }
};
