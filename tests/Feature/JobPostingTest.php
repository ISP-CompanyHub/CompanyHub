<?php

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
});

test('guests cannot access job postings', function () {
    $this->get(route('job-postings.index'))->assertRedirect(route('login'));
    $this->get(route('job-postings.create'))->assertRedirect(route('login'));
});

test('employees cannot access job postings', function () {
    $this->actingAs($this->employee);

    $this->get(route('job-postings.index'))->assertForbidden();
    $this->get(route('job-postings.create'))->assertForbidden();
});

test('administrators can view job postings index', function () {
    $this->actingAs($this->admin);

    JobPosting::factory()->count(3)->create();

    $this->get(route('job-postings.index'))
        ->assertOk()
        ->assertViewIs('job-postings.index')
        ->assertViewHas('jobPostings');
});

test('managers can view job postings index', function () {
    $this->actingAs($this->manager);

    JobPosting::factory()->count(3)->create();

    $this->get(route('job-postings.index'))
        ->assertOk()
        ->assertViewIs('job-postings.index')
        ->assertViewHas('jobPostings');
});

test('administrators can create job posting', function () {
    $this->actingAs($this->admin);

    $data = [
        'title' => 'Software Developer',
        'description' => 'We are looking for a talented developer',
        'location' => 'Remote',
        'employment_type' => 'Full-time',
        'salary_min' => 50000,
        'salary_max' => 80000,
        'is_active' => true,
    ];

    $response = $this->post(route('job-postings.store'), $data);

    $jobPosting = JobPosting::where('title', 'Software Developer')->first();
    $response->assertRedirect(route('job-postings.index'));

    $this->assertDatabaseHas('job_postings', [
        'title' => 'Software Developer',
        'location' => 'Remote',
    ]);
});
test('job posting requires title', function () {
    $this->actingAs($this->admin);

    $this->post(route('job-postings.store'), [
        'description' => 'Test description',
    ])->assertSessionHasErrors('title');
});

test('salary max must be greater than or equal to salary min', function () {
    $this->actingAs($this->admin);

    $this->post(route('job-postings.store'), [
        'title' => 'Test Job',
        'salary_min' => 80000,
        'salary_max' => 50000,
    ])->assertSessionHasErrors('salary_max');
});

test('administrators can view job posting details', function () {
    $this->actingAs($this->admin);

    $jobPosting = JobPosting::factory()->create();

    $this->get(route('job-postings.show', $jobPosting))
        ->assertOk()
        ->assertViewIs('job-postings.show')
        ->assertViewHas('jobPosting');
});

test('administrators can update job posting', function () {
    $this->actingAs($this->admin);

    $jobPosting = JobPosting::factory()->create([
        'title' => 'Old Title',
    ]);

    $this->put(route('job-postings.update', $jobPosting), [
        'title' => 'New Title',
        'description' => 'Updated description',
        'is_active' => true,
    ])->assertRedirect(route('job-postings.show', $jobPosting));

    $this->assertDatabaseHas('job_postings', [
        'id' => $jobPosting->id,
        'title' => 'New Title',
    ]);
});

test('administrators can delete job posting', function () {
    $this->actingAs($this->admin);

    $jobPosting = JobPosting::factory()->create();

    $this->delete(route('job-postings.destroy', $jobPosting))
        ->assertRedirect(route('job-postings.index'));

    $this->assertDatabaseMissing('job_postings', [
        'id' => $jobPosting->id,
    ]);
});

test('posted_at is automatically set when creating job posting', function () {
    $this->actingAs($this->admin);

    $this->post(route('job-postings.store'), [
        'title' => 'Test Job',
        'is_active' => true,
    ]);

    $jobPosting = JobPosting::where('title', 'Test Job')->first();
    expect($jobPosting->posted_at)->not->toBeNull();
});
