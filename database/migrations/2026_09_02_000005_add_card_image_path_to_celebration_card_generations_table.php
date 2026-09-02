<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('celebration_card_generations', 'card_image_path')) {
            Schema::table('celebration_card_generations', function (Blueprint $table) {
                $table->string('card_image_path')->nullable()->after('photo_path');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('celebration_card_generations', 'card_image_path')) {
            Schema::table('celebration_card_generations', function (Blueprint $table) {
                $table->dropColumn('card_image_path');
            });
        }
    }
};
