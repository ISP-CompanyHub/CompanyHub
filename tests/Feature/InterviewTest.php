<?php

use App\Mail\InterviewScheduledMail;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('administrator');

    $this->manager = User::factory()->create();
    $this->manager->assignRole('manager');

    $this->employee = User::factory()->create();
    $this->employee->assignRole('employee');

    $this->jobPosting = JobPosting::factory()->create();
    $this->candidate = Candidate::factory()->create([
        'job_posting_id' => $this->jobPosting->id,
        'status' => 'reviewing',
    ]);
});

test('guests cannot access interviews', function () {
    $this->get(route('interviews.index'))->assertRedirect(route('login'));
    $this->get(route('interviews.create'))->assertRedirect(route('login'));
});

test('employees cannot access interviews', function () {
    $this->actingAs($this->employee);

    $this->get(route('interviews.index'))->assertForbidden();
    $this->get(route('interviews.create'))->assertForbidden();
});

test('administrators can view interviews index', function () {
    $this->actingAs($this->admin);

    Interview::factory()->count(3)->create(['candidate_id' => $this->candidate->id]);

    $this->get(route('interviews.index'))
        ->assertOk()
        ->assertViewIs('interviews.index')
        ->assertViewHas('interviews');
});

test('administrators can schedule interview', function () {
    $this->actingAs($this->admin);

    Mail::fake();

    $data = [
        'candidate_id' => $this->candidate->id,
        'scheduled_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
        'location' => '123 Main St, Office 456',
        'mode' => 'in-person',
        'notes' => 'Please bring your portfolio',
    ];

    $response = $this->post(route('interviews.store'), $data);

    $interview = Interview::where('candidate_id', $this->candidate->id)->first();
    $response->assertRedirect(route('interviews.show', $interview));

    $this->assertDatabaseHas('interviews', [
        'candidate_id' => $this->candidate->id,
        'location' => '123 Main St, Office 456',
    ]);

    // Verify candidate status updated
    $this->assertDatabaseHas('candidates', [
        'id' => $this->candidate->id,
        'status' => 'interview_scheduled',
    ]);

    // Verify email was sent
    Mail::assertSent(InterviewScheduledMail::class, function ($mail) use ($interview) {
        return $mail->interview->id === $interview->id;
    });
});

test('interview requires candidate', function () {
    $this->actingAs($this->admin);

    $this->post(route('interviews.store'), [
        'scheduled_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
        'mode' => 'in-person',
    ])->assertSessionHasErrors('candidate_id');
});

test('interview requires scheduled date', function () {
    $this->actingAs($this->admin);

    $this->post(route('interviews.store'), [
        'candidate_id' => $this->candidate->id,
        'mode' => 'in-person',
    ])->assertSessionHasErrors('scheduled_at');
});

test('interview requires mode', function () {
    $this->actingAs($this->admin);

    $this->post(route('interviews.store'), [
        'candidate_id' => $this->candidate->id,
        'scheduled_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
    ])->assertSessionHasErrors('mode');
});

test('interview mode must be valid', function () {
    $this->actingAs($this->admin);

    $this->post(route('interviews.store'), [
        'candidate_id' => $this->candidate->id,
        'scheduled_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
        'mode' => 'invalid-mode',
    ])->assertSessionHasErrors('mode');
});

test('administrators can view interview details', function () {
    $this->actingAs($this->admin);

    $interview = Interview::factory()->create(['candidate_id' => $this->candidate->id]);

    $this->get(route('interviews.show', $interview))
        ->assertOk()
        ->assertViewIs('interviews.show')
        ->assertViewHas('interview');
});

test('administrators can update interview', function () {
    $this->actingAs($this->admin);

    Mail::fake();

    $interview = Interview::factory()->create([
        'candidate_id' => $this->candidate->id,
        'location' => 'Old Location',
        'scheduled_at' => now()->addDays(5),
    ]);

    $newDateTime = now()->addDays(10)->format('Y-m-d H:i:s');

    $this->put(route('interviews.update', $interview), [
        'candidate_id' => $this->candidate->id,
        'scheduled_at' => $newDateTime,
        'location' => 'New Location',
        'mode' => 'video',
    ])->assertRedirect(route('interviews.show', $interview));

    $this->assertDatabaseHas('interviews', [
        'id' => $interview->id,
        'location' => 'New Location',
    ]);

    // Email should be sent when rescheduled
    Mail::assertSent(InterviewScheduledMail::class);
});

test('administrators can delete interview', function () {
    $this->actingAs($this->admin);

    $interview = Interview::factory()->create(['candidate_id' => $this->candidate->id]);

    $this->delete(route('interviews.destroy', $interview))
        ->assertRedirect(route('interviews.index'));

    $this->assertDatabaseMissing('interviews', [
        'id' => $interview->id,
    ]);
});

test('interviews can be filtered as upcoming', function () {
    $this->actingAs($this->admin);

    Interview::factory()->upcoming()->create(['candidate_id' => $this->candidate->id]);
    Interview::factory()->past()->create(['candidate_id' => $this->candidate->id]);

    $response = $this->get(route('interviews.index', ['filter' => 'upcoming']));

    $response->assertOk();
});

test('interviews can be filtered as past', function () {
    $this->actingAs($this->admin);

    Interview::factory()->upcoming()->create(['candidate_id' => $this->candidate->id]);
    Interview::factory()->past()->create(['candidate_id' => $this->candidate->id]);

    $response = $this->get(route('interviews.index', ['filter' => 'past']));

    $response->assertOk();
});

test('email notification sent when interview scheduled', function () {
    $this->actingAs($this->admin);

    Mail::fake();

    $this->post(route('interviews.store'), [
        'candidate_id' => $this->candidate->id,
        'scheduled_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
        'mode' => 'video',
    ]);

    Mail::assertSent(InterviewScheduledMail::class, function ($mail) {
        return $mail->hasTo($this->candidate->email);
    });
});
