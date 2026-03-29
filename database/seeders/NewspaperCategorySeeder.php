<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class NewspaperCategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::updateOrCreate(
            ['slug' => 'newspaper'],
            [
                'name' => 'Newspaper',
                'name_bn' => 'সংবাদপত্র',
                'description' => 'স্থানীয় ও জাতীয় সংবাদপত্র - অনলাইন লিংক ও দৈনিক সংস্করণ',
                'icon' => 'fas fa-newspaper',
                'color' => '#1a73e8',
                'is_active' => true,
                'show_in_menu' => true,
                'allow_user_submission' => true,
                'sort_order' => 13,
            ]
        );
    }
}
