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
        Schema::create('user_category_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'category_id']);
        });

        // Add new fields to users table for detailed registration
        Schema::table('users', function (Blueprint $table) {
            $table->string('nid_number')->nullable()->after('phone');
            $table->text('registration_purpose')->nullable()->after('bio');
            $table->json('requested_categories')->nullable()->after('registration_purpose');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_category_permissions');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nid_number', 'registration_purpose', 'requested_categories']);
        });
    }
};
