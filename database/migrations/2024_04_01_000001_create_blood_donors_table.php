<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_donors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20);
            $table->string('whatsapp_number', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']);
            $table->date('last_donation_date')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('hide_phone')->default(false);
            $table->string('alternative_contact')->nullable();
            $table->enum('type', ['individual', 'organization'])->default('individual');
            $table->string('organization_name')->nullable();
            $table->foreignId('upazila_id')->constrained()->onDelete('cascade');
            $table->string('address')->nullable();
            $table->json('available_areas')->nullable();
            $table->json('available_for')->nullable();
            $table->integer('not_reachable_count')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();

            $table->index(['blood_group', 'status', 'is_available']);
            $table->index(['upazila_id', 'blood_group']);
            $table->index('phone');
        });

        Schema::create('blood_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_donor_id')->constrained('blood_donors')->onDelete('cascade');
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->text('comment');
            $table->enum('status', ['approved', 'pending', 'rejected'])->default('approved');
            $table->timestamps();
        });

        Schema::create('blood_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('blood_settings')->insert([
            ['key' => 'is_enabled', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'cooldown_days', 'value' => '90', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'not_reachable_threshold', 'value' => '10', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'page_views', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_comments');
        Schema::dropIfExists('blood_donors');
        Schema::dropIfExists('blood_settings');
    }
};
