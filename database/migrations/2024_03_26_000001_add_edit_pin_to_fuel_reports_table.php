<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fuel_reports', function (Blueprint $table) {
            $table->string('edit_pin', 4)->nullable()->after('reporter_email');
        });
    }

    public function down(): void
    {
        Schema::table('fuel_reports', function (Blueprint $table) {
            $table->dropColumn('edit_pin');
        });
    }
};
