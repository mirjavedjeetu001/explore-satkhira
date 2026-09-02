<?php

namespace Tests\Feature;

use App\Models\CelebrationCardGeneration;
use App\Models\CelebrationCardSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        $response->assertSee('Mir Javed Jeetu');
        $response->assertSee('Developer');
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

    public function test_visitor_download_is_saved_in_history(): void
    {
        Storage::fake('public');
        CelebrationCardSetting::getSettings();
        $photo = UploadedFile::fake()->image('visitor.png');

        $response = $this->post(route('celebration-card.generations.store'), [
            'name' => 'Mir Javed Jeetu',
            'designation' => 'Developer',
            'download_format' => 'png',
            'photo' => $photo,
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $generation = CelebrationCardGeneration::firstOrFail();
        $this->assertSame('Mir Javed Jeetu', $generation->name);
        $this->assertSame('Developer', $generation->designation);
        $this->assertSame('png', $generation->download_format);
        Storage::disk('public')->assertExists($generation->photo_path);
    }

    public function test_same_visitor_can_download_more_than_once(): void
    {
        CelebrationCardSetting::getSettings();

        $this->post(route('celebration-card.generations.store'), [
            'name' => 'Same Visitor',
            'designation' => 'Developer',
            'download_format' => 'png',
        ])->assertOk();
        $this->post(route('celebration-card.generations.store'), [
            'name' => 'Same Visitor',
            'designation' => 'Developer',
            'download_format' => 'jpg',
        ]);

        $this->assertSame(2, CelebrationCardGeneration::where('name', 'Same Visitor')->count());
    }
}
