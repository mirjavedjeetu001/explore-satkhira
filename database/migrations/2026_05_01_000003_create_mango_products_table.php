<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mango_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mango_store_id');
            $table->string('name');
            $table->decimal('price_per_kg', 8, 2);
            $table->decimal('min_order_kg', 8, 2)->nullable();
            $table->string('image');
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('mango_store_id')->references('id')->on('mango_stores')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mango_products');
    }
};
