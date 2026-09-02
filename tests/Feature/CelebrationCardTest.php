<?php

namespace Tests\Feature;

use App\Models\CelebrationCardSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CelebrationCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_enabled_celebration_card_page_renders(): void
    {
        CelebrationCardSetting::getSettings();

        $response = $this->get('/celebration-card');

        $response->assertOk();
        $response->assertSee('celebrationCard');
        $response->assertSee('downloadPngBtn');
        $response->assertSee('downloadJpgBtn');
    }

    public function test_disabled_celebration_card_page_shows_disabled_state(): void
    {
        $settings = CelebrationCardSetting::getSettings();
        $settings->update(['is_enabled' => false]);

        $response = $this->get('/celebration-card');

        $response->assertOk();
        $response->assertSee('শুভেচ্ছা কার্ড ফিচারটি এখন বন্ধ আছে');
        $response->assertDontSee('downloadPngBtn');
    }

    public function test_uploaded_template_image_is_used_as_the_card_background(): void
    {
        $settings = CelebrationCardSetting::getSettings();
        $settings->update([
            'template_image_path' => 'celebration-card/templates/original-template.png',
        ]);

        $response = $this->get('/celebration-card');

        $response->assertOk();
        $response->assertSee('celebration-card-template-image');
        $response->assertSee('storage/celebration-card/templates/original-template.png', false);
        $response->assertSee('celebration-template-person-name');
    }
}
