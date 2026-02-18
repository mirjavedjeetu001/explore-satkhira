<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('id')->constrained('roles')->onDelete('set null');
            $table->foreignId('upazila_id')->nullable()->after('role_id')->constrained('upazilas')->onDelete('set null');
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->text('address')->nullable()->after('avatar');
            $table->text('bio')->nullable()->after('address');
            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending')->after('bio');
            $table->boolean('is_verified')->default(false)->after('status');
            $table->timestamp('approved_at')->nullable()->after('is_verified');
            $table->foreignId('approved_by')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['upazila_id']);
            $table->dropColumn(['role_id', 'upazila_id', 'phone', 'avatar', 'address', 'bio', 'status', 'is_verified', 'approved_at', 'approved_by']);
        });
    }
};
