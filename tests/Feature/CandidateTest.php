<?php

use App\Models\Candidate;
use App\Models\JobPosting;
use App\Models\User;

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('administrator');

    $this->manager = User::factory()->create();
    $this->manager->assignRole('manager');

    $this->employee = User::factory()->create();
    $this->employee->assignRole('employee');

    $this->jobPosting = JobPosting::factory()->create();
});

test('guests cannot access candidates', function () {
    $this->get(route('candidates.index'))->assertRedirect(route('login'));
    $this->get(route('candidates.create'))->assertRedirect(route('login'));
});

test('employees cannot access candidates', function () {
    $this->actingAs($this->employee);

    $this->get(route('candidates.index'))->assertForbidden();
    $this->get(route('candidates.create'))->assertForbidden();
});

test('administrators can view candidates index', function () {
    $this->actingAs($this->admin);

    Candidate::factory()->count(3)->create(['job_posting_id' => $this->jobPosting->id]);

    $this->get(route('candidates.index'))
        ->assertOk()
        ->assertViewIs('candidates.index')
        ->assertViewHas('candidates');
});

test('administrators can create candidate', function () {
    $this->actingAs($this->admin);

    $data = [
        'job_posting_id' => $this->jobPosting->id,
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'phone' => '+1234567890',
        'status' => 'new',
    ];

    $response = $this->post(route('candidates.store'), $data);

    $candidate = Candidate::where('email', 'john.doe@example.com')->first();
    $response->assertRedirect(route('candidates.show', $candidate));

    $this->assertDatabaseHas('candidates', [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
    ]);
});

test('candidate requires name', function () {
    $this->actingAs($this->admin);

    $this->post(route('candidates.store'), [
        'job_posting_id' => $this->jobPosting->id,
        'email' => 'test@example.com',
    ])->assertSessionHasErrors('name');
});

test('candidate requires email', function () {
    $this->actingAs($this->admin);

    $this->post(route('candidates.store'), [
        'job_posting_id' => $this->jobPosting->id,
        'name' => 'Test User',
    ])->assertSessionHasErrors('email');
});

test('candidate email must be valid', function () {
    $this->actingAs($this->admin);

    $this->post(route('candidates.store'), [
        'job_posting_id' => $this->jobPosting->id,
        'name' => 'Test User',
        'email' => 'invalid-email',
    ])->assertSessionHasErrors('email');
});

test('candidate requires job posting', function () {
    $this->actingAs($this->admin);

    $this->post(route('candidates.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ])->assertSessionHasErrors('job_posting_id');
});

test('administrators can view candidate details', function () {
    $this->actingAs($this->admin);

    $candidate = Candidate::factory()->create(['job_posting_id' => $this->jobPosting->id]);

    $this->get(route('candidates.show', $candidate))
        ->assertOk()
        ->assertViewIs('candidates.show')
        ->assertViewHas('candidate');
});

test('administrators can update candidate', function () {
    $this->actingAs($this->admin);

    $candidate = Candidate::factory()->create([
        'job_posting_id' => $this->jobPosting->id,
        'name' => 'Old Name',
    ]);

    $this->put(route('candidates.update', $candidate), [
        'job_posting_id' => $this->jobPosting->id,
        'name' => 'New Name',
        'email' => $candidate->email,
        'status' => 'reviewing',
    ])->assertRedirect(route('candidates.show', $candidate));

    $this->assertDatabaseHas('candidates', [
        'id' => $candidate->id,
        'name' => 'New Name',
    ]);
});

test('administrators can update candidate status', function () {
    $this->actingAs($this->admin);

    $candidate = Candidate::factory()->create([
        'job_posting_id' => $this->jobPosting->id,
        'status' => 'new',
    ]);

    $this->from(route('candidates.show', $candidate))
        ->patch(route('candidates.update-status', $candidate), [
            'status' => 'interviewed',
        ])->assertRedirect(route('candidates.show', $candidate));

    $this->assertDatabaseHas('candidates', [
        'id' => $candidate->id,
        'status' => 'interviewed',
    ]);
});

test('administrators can delete candidate', function () {
    $this->actingAs($this->admin);

    $candidate = Candidate::factory()->create(['job_posting_id' => $this->jobPosting->id]);

    $this->delete(route('candidates.destroy', $candidate))
        ->assertRedirect(route('candidates.index'));

    $this->assertDatabaseMissing('candidates', [
        'id' => $candidate->id,
    ]);
});

test('candidates can be searched by name', function () {
    $this->actingAs($this->admin);

    Candidate::factory()->create([
        'job_posting_id' => $this->jobPosting->id,
        'name' => 'Alice Johnson',
    ]);

    Candidate::factory()->create([
        'job_posting_id' => $this->jobPosting->id,
        'name' => 'Bob Smith',
    ]);

    $response = $this->get(route('candidates.index', ['search' => 'Alice']));

    $response->assertOk()
        ->assertSee('Alice Johnson');
});

test('candidates can be filtered by status', function () {
    $this->actingAs($this->admin);

    Candidate::factory()->create([
        'job_posting_id' => $this->jobPosting->id,
        'status' => 'new',
    ]);

    Candidate::factory()->create([
        'job_posting_id' => $this->jobPosting->id,
        'status' => 'interviewed',
    ]);

    $response = $this->get(route('candidates.index', ['status' => 'interviewed']));

    $response->assertOk();
});
