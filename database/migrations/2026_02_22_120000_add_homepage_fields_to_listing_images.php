<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listing_images', function (Blueprint $table) {
            $table->boolean('show_on_homepage')->default(false)->after('is_active');
            $table->integer('homepage_priority')->default(0)->after('show_on_homepage');
        });
    }

    public function down(): void
    {
        Schema::table('listing_images', function (Blueprint $table) {
            $table->dropColumn(['show_on_homepage', 'homepage_priority']);
        });
    }
};
