<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('celebration_card_generations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('designation', 100)->nullable();
            $table->string('photo_path')->nullable();
            $table->string('download_format', 10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('celebration_card_generations');
    }
};
