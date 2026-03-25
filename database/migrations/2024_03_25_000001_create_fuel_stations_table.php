<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_stations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // পাম্পের নাম
            $table->foreignId('upazila_id')->constrained()->onDelete('cascade');
            $table->string('address')->nullable(); // ঠিকানা
            $table->string('phone')->nullable(); // পাম্পের ফোন
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_stations');
    }
};
