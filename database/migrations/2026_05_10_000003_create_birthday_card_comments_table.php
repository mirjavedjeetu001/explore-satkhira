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
        if (! Schema::hasTable('birthday_card_comments')) {
            Schema::create('birthday_card_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('birthday_card_id')->constrained('birthday_cards')->onDelete('cascade');
                $table->string('visitor_name');
                $table->string('visitor_phone')->unique();
                $table->text('wish_message');
                $table->timestamps();
                $table->unique(['birthday_card_id', 'visitor_phone']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birthday_card_comments');
    }
};
