<?php

use App\Models\Enquiry;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('authenticated non staff cannot access resident enquiries', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->getJson('/api/enquiries')->assertForbidden();
});

test('council staff can retrieve and update an enquiry by its route model binding', function () {
    $staff = User::factory()->create();
    $staff->forceFill(['is_council_staff' => true])->save();
    $enquiry = Enquiry::query()->create([
        'name' => 'Resident Example',
        'email' => 'resident@example.com',
        'message' => 'Please review this road repair request.',
        'status' => 'new',
    ]);

    Sanctum::actingAs($staff);

    $this->getJson("/api/enquiries/{$enquiry->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $enquiry->id);

    $this->putJson("/api/enquiries/{$enquiry->id}", ['status' => 'resolved'])
        ->assertOk()
        ->assertJsonPath('data.status', 'resolved');

    $this->assertDatabaseHas('enquiries', [
        'id' => $enquiry->id,
        'status' => 'resolved',
    ]);
});

test('council staff can delete an enquiry and the API returns no content', function () {
    $staff = User::factory()->create();
    $staff->forceFill(['is_council_staff' => true])->save();
    $enquiry = Enquiry::query()->create([
        'name' => 'Resident Example',
        'email' => 'resident@example.com',
        'message' => 'Please review this road repair request.',
    ]);

    Sanctum::actingAs($staff);

    $this->deleteJson("/api/enquiries/{$enquiry->id}")->assertNoContent();
    $this->assertDatabaseMissing('enquiries', ['id' => $enquiry->id]);
});
