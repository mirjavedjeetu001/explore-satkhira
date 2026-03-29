<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_station_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fuel_station_id')->constrained()->onDelete('cascade');
            $table->foreignId('push_subscription_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['fuel_station_id', 'push_subscription_id'], 'fuel_push_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_station_subscriptions');
    }
};
