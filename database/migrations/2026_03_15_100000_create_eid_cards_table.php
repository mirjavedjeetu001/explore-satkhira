<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eid_cards', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 11);
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('custom_message')->nullable();
            $table->string('template', 20)->default('template1');
            $table->string('photo')->nullable();
            $table->timestamps();
            
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eid_cards');
    }
};
