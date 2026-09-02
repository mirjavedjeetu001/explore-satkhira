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
        if (! Schema::hasTable('birthday_cards')) {
            Schema::create('birthday_cards', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->date('birthday_date');
                $table->string('card_image')->nullable();
                $table->text('bengali_message')->nullable();
                $table->text('english_message')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'birthday_date']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birthday_cards');
    }
};
