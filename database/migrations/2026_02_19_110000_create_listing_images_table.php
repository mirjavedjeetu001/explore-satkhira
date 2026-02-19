<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['offer', 'promotion', 'banner', 'gallery', 'menu', 'other'])->default('gallery');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['listing_id', 'status']);
            $table->index(['listing_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_images');
    }
};
