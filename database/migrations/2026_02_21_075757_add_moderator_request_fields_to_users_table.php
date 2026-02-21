<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('wants_upazila_moderator')->default(false)->after('is_own_business_moderator');
            $table->boolean('wants_own_business_moderator')->default(false)->after('wants_upazila_moderator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['wants_upazila_moderator', 'wants_own_business_moderator']);
        });
    }
};
