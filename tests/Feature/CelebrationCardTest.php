<?php

namespace Tests\Feature;

use App\Models\CelebrationCardRecipient;
use App\Models\CelebrationCardSetting;
use App\Models\Role;
use App\Models\User;
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

    public function test_saved_recipient_card_page_renders(): void
    {
        CelebrationCardSetting::getSettings();
        $recipient = CelebrationCardRecipient::create([
            'name' => 'Mir Javed Jeetu',
            'designation' => 'Developer',
            'photo_path' => 'celebration-card/recipients/demo.png',
        ]);

        $response = $this->get(route('celebration-card.recipient', $recipient));

        $response->assertOk();
        $response->assertSee('Mir Javed Jeetu');
        $response->assertSee('Developer');
        $response->assertSee('celebration-card-photo');
        $response->assertSee(asset('storage/celebration-card/recipients/demo.png'), false);
    }

    public function test_admin_can_save_a_recipient_with_a_photo(): void
    {
        Storage::fake('public');
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $admin = User::factory()->create([
            'role_id' => $role->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.celebration-card.recipients.store'), [
            'recipient_name' => 'Mir Javed Jeetu',
            'recipient_designation' => 'Developer',
            'recipient_photo' => UploadedFile::fake()->image('recipient.jpg'),
        ]);

        $response->assertRedirect();
        $recipient = CelebrationCardRecipient::firstOrFail();
        $this->assertSame('Mir Javed Jeetu', $recipient->name);
        $this->assertSame('Developer', $recipient->designation);
        Storage::disk('public')->assertExists($recipient->photo_path);
    }
}
