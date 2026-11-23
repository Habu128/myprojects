<?php

use App\Models\ContentPage;
use App\Models\MediaAsset;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function councilStaff(): User
{
    $user = User::factory()->create();
    $user->forceFill(['is_council_staff' => true])->save();

    return $user;
}

test('guests are redirected to the council login page', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('council.reports.login'));
});

test('only council staff can open the admin dashboard', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.dashboard'))
        ->assertForbidden();

    $this->actingAs(councilStaff())
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Website dashboard');
});

test('non staff cannot upload media or change site settings', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('admin.media.store'), [])
        ->assertForbidden();

    $this->actingAs(User::factory()->create())
        ->put(route('admin.settings.update'), [])
        ->assertForbidden();
});

test('council staff can upload a media asset', function () {
    Storage::fake('public');

    $this->actingAs(councilStaff())->post(route('admin.media.store'), [
        'title' => 'Uploaded chairman portrait',
        'alt_text' => 'Uploaded portrait of the chairman',
        'category' => 'chairman',
        'file' => UploadedFile::fake()->image('chairman.png', 600, 400),
    ])->assertRedirect(route('admin.media.index'));

    $asset = MediaAsset::query()->firstOrFail();

    expect($asset->title)->toBe('Uploaded chairman portrait')
        ->and($asset->category)->toBe('chairman');

    Storage::disk('public')->assertExists($asset->path);

    $this->get(route('admin.media.index'))
        ->assertOk()
        ->assertSee('Uploaded chairman portrait');
});

test('site settings update what visitors see', function () {
    ContentPage::factory()->create([
        'slug' => 'contact',
        'items' => [
            ['title' => 'Phone', 'description' => 'Not published'],
        ],
    ]);
    SiteSetting::flushCache();

    $this->actingAs(councilStaff())->put(route('admin.settings.update'), [
        'hero_title_line_1' => 'Updated Shani',
        'hero_title_line_2' => 'Council website',
        'contact_phone' => '0800 123 4567',
        'contact_note' => 'Phone number verified from council records.',
    ])->assertRedirect(route('admin.settings.edit'));

    $this->get('/')->assertOk()->assertSee('Updated Shani');
    $this->get('/contact')
        ->assertOk()
        ->assertSee('0800 123 4567')
        ->assertSee('Phone number verified from council records.');

    $this->get(route('admin.settings.edit'))
        ->assertOk()
        ->assertSee('Site settings');
});

test('uploaded chairman photos appear in the council directory', function () {
    ContentPage::factory()->create(['slug' => 'council', 'items' => [], 'sources' => []]);

    MediaAsset::factory()->create([
        'title' => 'Uploaded portrait',
        'alt_text' => 'Uploaded portrait alternative text',
        'path' => 'media/uploaded-portrait.png',
        'category' => 'chairman',
    ]);

    $this->get(route('council'))
        ->assertOk()
        ->assertSee('Uploaded portrait alternative text');
});
