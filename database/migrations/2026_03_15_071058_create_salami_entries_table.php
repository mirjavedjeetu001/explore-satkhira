<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salami_entries', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index(); // To group entries by user session
            $table->string('user_name'); // User's name
            $table->string('phone', 20)->nullable(); // Phone number
            $table->string('giver_name'); // Who gave salami
            $table->string('giver_relation')->nullable(); // Relation (uncle, aunt, etc)
            $table->decimal('amount', 10, 2); // Salami amount
            $table->text('note')->nullable(); // Optional note
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salami_entries');
    }
};
