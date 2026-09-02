<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Keep this migration safe for databases where is_verified was already
        // included in the original fuel_reports table definition.
        if (! Schema::hasColumn('fuel_reports', 'is_verified')) {
            Schema::table('fuel_reports', function (Blueprint $table) {
                $table->boolean('is_verified')->default(false)->after('notes');
            });
        }

        if (! Schema::hasColumn('fuel_reports', 'verified_at')) {
            Schema::table('fuel_reports', function (Blueprint $table) {
                $table->timestamp('verified_at')->nullable()->after('is_verified');
            });
        }
        
        // Add view_count to fuel_stations
        if (! Schema::hasColumn('fuel_stations', 'view_count')) {
            Schema::table('fuel_stations', function (Blueprint $table) {
                $table->unsignedInteger('view_count')->default(0)->after('google_map_link');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('fuel_reports', 'verified_at')) {
            Schema::table('fuel_reports', function (Blueprint $table) {
                $table->dropColumn('verified_at');
            });
        }

        if (Schema::hasColumn('fuel_stations', 'view_count')) {
            Schema::table('fuel_stations', function (Blueprint $table) {
                $table->dropColumn('view_count');
            });
        }
    }
};
