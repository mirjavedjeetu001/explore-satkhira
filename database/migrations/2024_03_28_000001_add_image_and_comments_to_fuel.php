<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add image to fuel_reports
        Schema::table('fuel_reports', function (Blueprint $table) {
            $table->string('image')->nullable()->after('notes');
        });
        
        // Create fuel_comments table
        Schema::create('fuel_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fuel_station_id')->constrained()->onDelete('cascade');
            $table->string('commenter_name');
            $table->string('commenter_phone');
            $table->text('comment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('fuel_reports', function (Blueprint $table) {
            $table->dropColumn('image');
        });
        
        Schema::dropIfExists('fuel_comments');
    }
};
