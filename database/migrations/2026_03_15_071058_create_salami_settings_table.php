<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salami_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(true);
            $table->string('title')->default('ঈদ সালামি ক্যালকুলেটর');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('salami_settings')->insert([
            'is_enabled' => true,
            'title' => 'ঈদ সালামি ক্যালকুলেটর',
            'description' => 'আপনার ঈদের সালামি হিসাব রাখুন সহজেই!',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salami_settings');
    }
};
