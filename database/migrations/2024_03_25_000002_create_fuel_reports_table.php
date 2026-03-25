<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fuel_station_id')->constrained()->onDelete('cascade');
            $table->string('reporter_name'); // রিপোর্টকারীর নাম
            $table->string('reporter_phone'); // ফোন নম্বর
            $table->string('reporter_email')->nullable(); // ইমেইল
            $table->string('session_id')->nullable(); // For edit access
            $table->boolean('petrol_available')->default(false); // পেট্রোল আছে?
            $table->boolean('diesel_available')->default(false); // ডিজেল আছে?
            $table->boolean('octane_available')->default(false); // অকটেন আছে?
            $table->decimal('petrol_price', 8, 2)->nullable(); // পেট্রোলের দাম
            $table->decimal('diesel_price', 8, 2)->nullable(); // ডিজেলের দাম
            $table->decimal('octane_price', 8, 2)->nullable(); // অকটেনের দাম
            $table->enum('queue_status', ['none', 'short', 'medium', 'long'])->default('none'); // লাইনের অবস্থা
            $table->text('notes')->nullable(); // অতিরিক্ত মন্তব্য
            $table->boolean('is_verified')->default(false); // Admin verified
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_reports');
    }
};
