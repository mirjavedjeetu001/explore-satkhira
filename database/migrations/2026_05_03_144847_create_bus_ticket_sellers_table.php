<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_ticket_sellers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20)->unique();
            $table->string('password');
            $table->string('whatsapp', 20)->nullable();
            $table->unsignedBigInteger('upazila_id')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();

            $table->foreign('upazila_id')->references('id')->on('upazilas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_ticket_sellers');
    }
};
