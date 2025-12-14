<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobOfferRequest;
use App\Http\Requests\StoreJobPostingRequest;
use App\Http\Requests\UpdateJobPostingRequest;
use App\Mail\JobOfferMail;
use App\Models\Candidate;
use App\Models\JobOffer;
use App\Models\JobPosting;
use App\Services\JobOfferPdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class JobPostingController extends Controller
{
    public function __construct(
        protected JobOfferPdfService $pdfService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $jobPostings = JobPosting::latest()
            ->withCount('candidates')
            ->paginate(10);

        return view('job-postings.index', compact('jobPostings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobPostingRequest $request): RedirectResponse
    {
        $jobPosting = JobPosting::create([
            ...$request->validated(),
            'posted_at' => now(),
        ]);

        return redirect()
            ->route('job-postings.index', $jobPosting)
            ->with('success', 'Job posting created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobPosting $jobPosting): View
    {
        $jobPosting->load([
            'candidates' => function ($query) {
                $query->latest()->limit(10);
            },
        ]);

        return view('job-postings.show', compact('jobPosting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobPosting $jobPosting): View
    {
        return view('job-postings.edit', compact('jobPosting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobPostingRequest $request, JobPosting $jobPosting): RedirectResponse
    {
        $jobPosting->update($request->validated());

        return redirect()
            ->route('job-postings.show', $jobPosting)
            ->with('success', 'Job posting updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobPosting $jobPosting): RedirectResponse
    {
        $jobPosting->delete();

        return redirect()
            ->route('job-postings.index')
            ->with('success', 'Job posting deleted successfully!');
    }

    // =====================
    // Job Offer Methods
    // =====================

    /**
     * Display a listing of job offers.
     */
    public function offerIndex(Request $request): View
    {
        $jobOffers = JobOffer::with(['candidate', 'jobPosting'])
            ->latest()
            ->paginate(15);

        // Get candidates available for job offers
        $candidates = Candidate::where('status', 'interviewed')
            ->doesntHave('jobOffer')
            ->with('jobPosting')
            ->get();

        // Pre-selected candidate if coming from candidate page
        $selectedCandidateId = $request->input('candidate_id');

        return view('job-offers.index', compact('jobOffers', 'candidates', 'selectedCandidateId'));
    }

    /**
     * Show the form for creating a new job offer.
     */
    public function offerCreate(Request $request): View
    {
        $candidate = null;
        if ($request->filled('candidate_id')) {
            $candidate = Candidate::with('jobPosting')->findOrFail($request->candidate_id);
        }

        $candidates = Candidate::where('status', 'interviewed')
            ->doesntHave('jobOffer')
            ->with('jobPosting')
            ->get();

        return view('job-offers.create', compact('candidates', 'candidate'));
    }

    /**
     * Store a newly created job offer in storage and send it immediately.
     */
    public function offerStore(StoreJobOfferRequest $request): RedirectResponse
    {
        // Get the candidate to retrieve their job_posting_id
        $candidate = Candidate::findOrFail($request->candidate_id);

        // Generate unique offer number
        $year = now()->year;
        $count = JobOffer::whereYear('created_at', $year)->count() + 1;
        $offerNumber = "JO-{$year}-" . str_pad($count, 3, '0', STR_PAD_LEFT);

        $jobOffer = JobOffer::create([
            ...$request->validated(),
            'job_posting_id' => $candidate->job_posting_id,
            'offer_number' => $offerNumber,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        // Load relationships for email
        $jobOffer->load('candidate.jobPosting');

        // Generate PDF
        $pdfPath = $this->pdfService->generate($jobOffer);

        // Send email
        Mail::to($jobOffer->candidate->email)->send(
            new JobOfferMail($jobOffer, $pdfPath)
        );

        // Update job offer with PDF path
        $jobOffer->update(['pdf_path' => $pdfPath]);

        // Update candidate status
        $candidate->update(['status' => 'offer_sent']);

        return redirect()
            ->route('job-offers.show', $jobOffer)
            ->with('success', 'Job offer created and sent successfully to ' . $candidate->email . '!');
    }

    /**
     * Display the specified job offer.
     */
    public function offerShow(JobOffer $jobOffer): View
    {
        $jobOffer->load(['candidate', 'jobPosting']);

        return view('job-offers.show', compact('jobOffer'));
    }

    /**
     * Show the form for editing the specified job offer.
     */
    public function offerEdit(JobOffer $jobOffer): View|RedirectResponse
    {
        if ($jobOffer->status === 'sent') {
            return redirect()
                ->route('job-offers.show', $jobOffer)
                ->with('error', 'Cannot edit a sent job offer.');
        }

        $candidates = Candidate::with('jobPosting')->get();

        return view('job-offers.edit', compact('jobOffer', 'candidates'));
    }

    /**
     * Update the specified job offer in storage.
     */
    public function offerUpdate(StoreJobOfferRequest $request, JobOffer $jobOffer): RedirectResponse
    {
        if ($jobOffer->status === 'sent') {
            return redirect()
                ->route('job-offers.show', $jobOffer)
                ->with('error', 'Cannot update a sent job offer.');
        }

        // Get the candidate to retrieve their job_posting_id
        $candidate = Candidate::findOrFail($request->candidate_id);

        $jobOffer->update([
            ...$request->validated(),
            'job_posting_id' => $candidate->job_posting_id,
        ]);

        return redirect()
            ->route('job-offers.show', $jobOffer)
            ->with('success', 'Job offer updated successfully!');
    }

    /**
     * Preview job offer PDF.
     */
    public function offerPreview(JobOffer $jobOffer)
    {
        return $this->pdfService->preview($jobOffer);
    }

    /**
     * Download job offer PDF.
     */
    public function offerDownload(JobOffer $jobOffer)
    {
        return $this->pdfService->download($jobOffer);
    }

    /**
     * Remove the specified job offer from storage.
     */
    public function offerDestroy(JobOffer $jobOffer): RedirectResponse
    {
        $jobOffer->delete();

        return redirect()
            ->route('job-offers.index')
            ->with('success', 'Job offer deleted successfully!');
    }
}
