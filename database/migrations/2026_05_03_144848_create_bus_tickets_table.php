<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bus_ticket_seller_id');
            $table->string('from_location');
            $table->string('to_location');
            $table->date('journey_date');
            $table->string('bus_name')->nullable();
            $table->string('ticket_type')->default('seat'); // seat, ac, sleeper
            $table->integer('seat_count')->default(1);
            $table->decimal('price_per_ticket', 10, 2);
            $table->text('description')->nullable();
            $table->string('contact_number');
            $table->string('whatsapp_number')->nullable();
            $table->boolean('is_sold')->default(false);
            $table->unsignedInteger('interested_count')->default(0);
            $table->timestamp('sold_at')->nullable();
            $table->timestamps();

            $table->foreign('bus_ticket_seller_id')->references('id')->on('bus_ticket_sellers')->cascadeOnDelete();
            $table->index(['is_sold', 'journey_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_tickets');
    }
};
