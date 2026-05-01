<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mango_stores', function (Blueprint $table) {
            $table->id();
            $table->string('owner_name');
            $table->string('store_name');
            $table->string('phone', 20)->unique();
            $table->string('password');
            $table->unsignedBigInteger('upazila_id')->nullable();
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->text('delivery_info')->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();

            $table->foreign('upazila_id')->references('id')->on('upazilas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mango_stores');
    }
};
