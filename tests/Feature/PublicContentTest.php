<?php

use App\Models\ContentPage;
use App\Models\Notice;
use App\Models\User;

test('public pages render content from the database', function () {
    ContentPage::factory()->create([
        'slug' => 'about',
        'title' => 'Database driven about page',
        'items' => [['title' => 'A stored item', 'description' => 'Stored description']],
    ]);

    $this->get('/about')
        ->assertOk()
        ->assertSee('Database driven about page')
        ->assertSee('A stored item');
});

test('unpublished pages are not available to visitors', function () {
    ContentPage::factory()->create([
        'slug' => 'about',
        'is_published' => false,
    ]);

    $this->get('/about')->assertNotFound();
});

test('published notices appear on the news page', function () {
    ContentPage::factory()->create(['slug' => 'updates', 'items' => []]);
    Notice::factory()->create(['title' => 'A verified council notice']);

    $this->get('/news')
        ->assertOk()
        ->assertSee('A verified council notice');
});

test('draft notices do not appear on the news page', function () {
    ContentPage::factory()->create(['slug' => 'updates', 'items' => []]);
    Notice::factory()->create([
        'title' => 'An unfinished notice',
        'is_published' => false,
    ]);

    $this->get('/news')
        ->assertOk()
        ->assertDontSee('An unfinished notice');
});

test('published notices appear on the home page', function () {
    Notice::factory()->create(['title' => 'A homepage notice']);

    $this->get('/')
        ->assertOk()
        ->assertSee('A homepage notice');
});

test('the transparency page renders database content', function () {
    ContentPage::factory()->create([
        'slug' => 'transparency',
        'title' => 'Database driven transparency page',
        'items' => [['title' => 'A stored record', 'description' => 'Stored record description']],
    ]);

    $this->get('/transparency')
        ->assertOk()
        ->assertSee('Database driven transparency page')
        ->assertSee('A stored record');
});

test('the council directory renders database content', function () {
    ContentPage::factory()->create([
        'slug' => 'council',
        'items' => [[
            'kicker' => 'EXECUTIVE COUNCIL',
            'title' => 'Stored council leader',
            'description' => 'Stored leadership description',
        ]],
        'sources' => [],
    ]);

    $this->get('/council')
        ->assertOk()
        ->assertSee('Stored council leader');
});

test('only council staff can edit site content', function () {
    ContentPage::factory()->create(['slug' => 'about']);
    $user = User::factory()->create();

    $this->actingAs($user)->get('/council/staff/content')->assertForbidden();

    $user->forceFill(['is_council_staff' => true])->save();

    $this->actingAs($user)->get('/council/staff/content')->assertOk();
});

test('council staff can update a page and publish a notice', function () {
    $user = User::factory()->create();
    $user->forceFill(['is_council_staff' => true])->save();
    $contentPage = ContentPage::factory()->create(['slug' => 'services']);

    $this->actingAs($user)->put(route('staff.content.update', $contentPage), [
        'eyebrow' => 'UPDATED',
        'title' => 'Updated services',
        'description' => 'Updated description',
        'details' => null,
        'items' => '[{"title":"Updated item","description":"Updated detail"}]',
        'sources' => '[]',
        'is_published' => '1',
    ])->assertRedirect(route('staff.content.index'));

    expect($contentPage->fresh()->title)->toBe('Updated services');

    $this->actingAs($user)->post(route('staff.notices.store'), [
        'title' => 'New public notice',
        'body' => 'The notice body has been verified.',
        'published_at' => null,
        'is_published' => '1',
    ])->assertRedirect(route('staff.notices.index'));

    $this->assertDatabaseHas('notices', ['title' => 'New public notice', 'is_published' => true]);
});
