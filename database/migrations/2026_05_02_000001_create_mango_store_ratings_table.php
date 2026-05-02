<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mango_store_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mango_store_id');
            $table->string('phone', 20);
            $table->unsignedTinyInteger('rating');
            $table->timestamps();

            $table->foreign('mango_store_id')
                ->references('id')
                ->on('mango_stores')
                ->cascadeOnDelete();

            $table->unique(['mango_store_id', 'phone']);
            $table->index('mango_store_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mango_store_ratings');
    }
};
