<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('question');
            $table->json('options'); // ["হ্যা", "না", "মতামত নেই", "অন্যান্য"]
            $table->string('image')->nullable();
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_homepage')->default(true);
            $table->boolean('has_comment_option')->default(false); // last option allows comment
            $table->json('form_fields')->nullable(); // dynamic form config
            $table->timestamps();
        });

        Schema::create('survey_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('phone', 20);
            $table->string('class_type')->nullable(); // intermediate / honours
            $table->string('department')->nullable(); // বিভাগ / ডিপার্টমেন্ট
            $table->string('year')->nullable(); // বর্ষ
            $table->string('session')->nullable(); // সেশন
            $table->string('selected_option');
            $table->text('comment')->nullable();
            $table->string('device_fingerprint')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->unique(['survey_id', 'phone'], 'unique_phone_per_survey');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_votes');
        Schema::dropIfExists('surveys');
    }
};
