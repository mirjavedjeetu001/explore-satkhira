<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_ticket_inperson_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_ticket_id')->constrained('bus_tickets')->onDelete('cascade');
            $table->string('buyer_name');
            $table->string('buyer_phone');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_ticket_inperson_requests');
    }
};
