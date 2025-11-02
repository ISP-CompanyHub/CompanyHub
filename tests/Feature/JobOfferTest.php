<?php

use App\Mail\JobOfferMail;
use App\Models\Candidate;
use App\Models\JobOffer;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
        'status' => 'interviewed',
    ]);

    Storage::fake('local');
});

test('guests cannot access job offers', function () {
    $this->get(route('job-offers.index'))->assertRedirect(route('login'));
    $this->get(route('job-offers.create'))->assertRedirect(route('login'));
});

test('employees cannot access job offers', function () {
    $this->actingAs($this->employee);

    $this->get(route('job-offers.index'))->assertForbidden();
    $this->get(route('job-offers.create'))->assertForbidden();
});

test('managers can view job offers index', function () {
    $this->actingAs($this->manager);

    JobOffer::factory()->count(3)->create([
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
    ]);

    $this->get(route('job-offers.index'))
        ->assertOk()
        ->assertViewIs('job-offers.index')
        ->assertViewHas('jobOffers');
});

test('managers can create job offer', function () {
    $this->actingAs($this->manager);

    $data = [
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
        'salary' => 75000,
        'start_date' => now()->addDays(30)->format('Y-m-d'),
        'expires_at' => now()->addDays(60)->format('Y-m-d'),
    ];

    $response = $this->post(route('job-offers.store'), $data);

    $jobOffer = JobOffer::where('candidate_id', $this->candidate->id)->first();
    $response->assertRedirect(route('job-offers.show', $jobOffer));

    $this->assertDatabaseHas('job_offers', [
        'candidate_id' => $this->candidate->id,
        'salary' => 75000,
        'status' => 'draft',
    ]);

    // Check offer number was generated
    expect($jobOffer->offer_number)->toStartWith('JO-');
});

test('job offer requires candidate', function () {
    $this->actingAs($this->manager);

    $this->post(route('job-offers.store'), [
        'job_posting_id' => $this->jobPosting->id,
        'salary' => 75000,
        'start_date' => now()->addDays(30)->format('Y-m-d'),
    ])->assertSessionHasErrors('candidate_id');
});

test('job offer requires salary', function () {
    $this->actingAs($this->manager);

    $this->post(route('job-offers.store'), [
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
        'start_date' => now()->addDays(30)->format('Y-m-d'),
    ])->assertSessionHasErrors('salary');
});

test('job offer requires start date', function () {
    $this->actingAs($this->manager);

    $this->post(route('job-offers.store'), [
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
        'salary' => 75000,
    ])->assertSessionHasErrors('start_date');
});

test('managers can view job offer details', function () {
    $this->actingAs($this->manager);

    $jobOffer = JobOffer::factory()->create([
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
    ]);

    $this->get(route('job-offers.show', $jobOffer))
        ->assertOk()
        ->assertViewIs('job-offers.show')
        ->assertViewHas('jobOffer');
});

test('managers can update draft job offer', function () {
    $this->actingAs($this->manager);

    $jobOffer = JobOffer::factory()->draft()->create([
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
        'salary' => 70000,
    ]);

    $this->put(route('job-offers.update', $jobOffer), [
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
        'salary' => 80000,
        'start_date' => now()->addDays(30)->format('Y-m-d'),
        'expires_at' => now()->addDays(60)->format('Y-m-d'),
    ])->assertRedirect(route('job-offers.show', $jobOffer));

    $this->assertDatabaseHas('job_offers', [
        'id' => $jobOffer->id,
        'salary' => 80000,
    ]);
});

test('managers cannot update sent job offer', function () {
    $this->actingAs($this->manager);

    $jobOffer = JobOffer::factory()->sent()->create([
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
    ]);

    $this->put(route('job-offers.update', $jobOffer), [
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
        'salary' => 80000,
        'start_date' => now()->addDays(30)->format('Y-m-d'),
        'expires_at' => now()->addDays(60)->format('Y-m-d'),
    ])->assertRedirect(route('job-offers.show', $jobOffer))
        ->assertSessionHas('error');
});

test('managers can preview job offer PDF', function () {
    $this->actingAs($this->manager);

    $jobOffer = JobOffer::factory()->create([
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
    ]);

    $response = $this->get(route('job-offers.preview', $jobOffer));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/pdf');
});

test('managers can download job offer PDF', function () {
    $this->actingAs($this->manager);

    $jobOffer = JobOffer::factory()->create([
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
    ]);

    $response = $this->get(route('job-offers.download', $jobOffer));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/pdf');
    $response->assertHeader('Content-Disposition');
});

test('managers can send job offer via email', function () {
    $this->actingAs($this->manager);

    Mail::fake();

    $jobOffer = JobOffer::factory()->draft()->create([
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
    ]);

    $response = $this->post(route('job-offers.send', $jobOffer));

    $response->assertRedirect(route('job-offers.show', $jobOffer));

    // Verify job offer status updated
    $this->assertDatabaseHas('job_offers', [
        'id' => $jobOffer->id,
        'status' => 'sent',
    ]);

    // Verify candidate status updated
    $this->assertDatabaseHas('candidates', [
        'id' => $this->candidate->id,
        'status' => 'offer_sent',
    ]);

    // Verify email was sent
    Mail::assertSent(JobOfferMail::class, function ($mail) use ($jobOffer) {
        return $mail->jobOffer->id === $jobOffer->id
            && $mail->hasTo($this->candidate->email);
    });

    // Verify PDF was generated
    $jobOffer->refresh();
    expect($jobOffer->pdf_path)->not->toBeNull();
    expect($jobOffer->sent_at)->not->toBeNull();
});

test('managers cannot send job offer twice', function () {
    $this->actingAs($this->manager);

    Mail::fake();

    $jobOffer = JobOffer::factory()->sent()->create([
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
    ]);

    $response = $this->post(route('job-offers.send', $jobOffer));

    $response->assertRedirect();
    $response->assertSessionHas('error');

    Mail::assertNothingSent();
});

test('managers can delete job offer', function () {
    $this->actingAs($this->manager);

    $jobOffer = JobOffer::factory()->create([
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
    ]);

    $this->delete(route('job-offers.destroy', $jobOffer))
        ->assertRedirect(route('job-offers.index'));

    $this->assertDatabaseMissing('job_offers', [
        'id' => $jobOffer->id,
    ]);
});

test('offer number is unique per year', function () {
    $this->actingAs($this->manager);

    $jobOffer1 = JobOffer::factory()->create([
        'candidate_id' => $this->candidate->id,
        'job_posting_id' => $this->jobPosting->id,
    ]);

    $candidate2 = Candidate::factory()->create([
        'job_posting_id' => $this->jobPosting->id,
        'status' => 'interviewed',
    ]);

    $jobOffer2 = JobOffer::factory()->create([
        'candidate_id' => $candidate2->id,
        'job_posting_id' => $this->jobPosting->id,
    ]);

    expect($jobOffer1->offer_number)->not->toBe($jobOffer2->offer_number);
    expect($jobOffer1->offer_number)->toContain(now()->year);
    expect($jobOffer2->offer_number)->toContain(now()->year);
});
