<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blood_donors', function (Blueprint $table) {
            $table->dropForeign(['upazila_id']);
            $table->unsignedBigInteger('upazila_id')->nullable()->change();
            $table->foreign('upazila_id')->references('id')->on('upazilas')->nullOnDelete();
            $table->string('outside_area')->nullable()->after('upazila_id');
        });
    }

    public function down(): void
    {
        Schema::table('blood_donors', function (Blueprint $table) {
            $table->dropColumn('outside_area');
            $table->dropForeign(['upazila_id']);
            $table->foreignId('upazila_id')->change()->constrained()->onDelete('cascade');
        });
    }
};
