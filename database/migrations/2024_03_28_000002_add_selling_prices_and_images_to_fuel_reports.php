<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fuel_reports', function (Blueprint $table) {
            // Selling prices (কত টাকায় দিচ্ছে)
            $table->decimal('petrol_selling_price', 8, 2)->nullable()->after('petrol_price');
            $table->decimal('diesel_selling_price', 8, 2)->nullable()->after('diesel_price');
            $table->decimal('octane_selling_price', 8, 2)->nullable()->after('octane_price');
            
            // Multiple images - JSON array
            $table->json('images')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('fuel_reports', function (Blueprint $table) {
            $table->dropColumn(['petrol_selling_price', 'diesel_selling_price', 'octane_selling_price', 'images']);
        });
    }
};
