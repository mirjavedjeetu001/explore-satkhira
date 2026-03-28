<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fuel_reports', function (Blueprint $table) {
            $table->decimal('fixed_amount', 10, 2)->nullable()->after('octane_selling_price');
        });
    }

    public function down(): void
    {
        Schema::table('fuel_reports', function (Blueprint $table) {
            $table->dropColumn('fixed_amount');
        });
    }
};
