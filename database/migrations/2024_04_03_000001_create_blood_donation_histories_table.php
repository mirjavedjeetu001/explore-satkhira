<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_donation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_donor_id')->constrained()->onDelete('cascade');
            $table->date('donation_date');
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['blood_donor_id', 'donation_date']);
        });

        // Add parent_id for organization donor management
        Schema::table('blood_donors', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('organization_name')
                  ->constrained('blood_donors')->nullOnDelete();
        });

        // Add show_on_homepage setting
        DB::table('blood_settings')->insertOrIgnore([
            'key' => 'show_on_homepage',
            'value' => '0',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('blood_donors', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
        Schema::dropIfExists('blood_donation_histories');
        DB::table('blood_settings')->where('key', 'show_on_homepage')->delete();
    }
};
