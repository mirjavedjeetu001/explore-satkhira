<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fuel_stations', function (Blueprint $table) {
            $table->string('google_map_link')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('fuel_stations', function (Blueprint $table) {
            $table->dropColumn('google_map_link');
        });
    }
};
