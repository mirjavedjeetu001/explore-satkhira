<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add is_verified to fuel_reports
        Schema::table('fuel_reports', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('notes');
            $table->timestamp('verified_at')->nullable()->after('is_verified');
        });
        
        // Add view_count to fuel_stations
        Schema::table('fuel_stations', function (Blueprint $table) {
            $table->unsignedInteger('view_count')->default(0)->after('google_map_link');
        });
    }

    public function down(): void
    {
        Schema::table('fuel_reports', function (Blueprint $table) {
            $table->dropColumn(['is_verified', 'verified_at']);
        });
        
        Schema::table('fuel_stations', function (Blueprint $table) {
            $table->dropColumn('view_count');
        });
    }
};
