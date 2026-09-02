<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('celebration_card_settings', 'template_image_path')) {
            Schema::table('celebration_card_settings', function (Blueprint $table) {
                $table->string('template_image_path')->nullable()->after('footer_text');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('celebration_card_settings', 'template_image_path')) {
            Schema::table('celebration_card_settings', function (Blueprint $table) {
                $table->dropColumn('template_image_path');
            });
        }
    }
};
