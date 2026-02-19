<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listing_images', function (Blueprint $table) {
            $table->enum('position', ['left', 'right', 'center', 'top-left', 'top-right', 'bottom-left', 'bottom-right', 'full-width'])->default('center')->after('type');
            $table->enum('display_size', ['small', 'medium', 'large', 'extra-large', 'full-width'])->default('medium')->after('position');
        });
    }

    public function down(): void
    {
        Schema::table('listing_images', function (Blueprint $table) {
            $table->dropColumn(['position', 'display_size']);
        });
    }
};
