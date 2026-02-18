<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mp_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mp_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('question');
            $table->text('answer')->nullable();
            $table->enum('status', ['pending', 'approved', 'answered', 'rejected'])->default('pending');
            $table->boolean('is_public')->default(true);
            $table->timestamp('answered_at')->nullable();
            $table->foreignId('answered_by')->nullable();
            $table->timestamps();

            $table->index(['mp_profile_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mp_questions');
    }
};
