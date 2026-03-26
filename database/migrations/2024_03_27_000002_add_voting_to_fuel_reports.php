<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fuel_reports', function (Blueprint $table) {
            $table->unsignedInteger('correct_votes')->default(0)->after('is_verified');
            $table->unsignedInteger('incorrect_votes')->default(0)->after('correct_votes');
        });
    }

    public function down(): void
    {
        Schema::table('fuel_reports', function (Blueprint $table) {
            $table->dropColumn(['correct_votes', 'incorrect_votes']);
        });
    }
};
