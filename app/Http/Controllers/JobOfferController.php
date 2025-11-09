<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobOfferRequest;
use App\Mail\JobOfferMail;
use App\Models\Candidate;
use App\Models\JobOffer;
use App\Services\JobOfferPdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class JobOfferController extends Controller
{
    public function __construct(
        protected JobOfferPdfService $pdfService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
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
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
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
     * Store a newly created resource in storage.
     */
    public function store(StoreJobOfferRequest $request): RedirectResponse
    {
        // Get the candidate to retrieve their job_posting_id
        $candidate = Candidate::findOrFail($request->candidate_id);

        // Generate unique offer number
        $year = now()->year;
        $count = JobOffer::whereYear('created_at', $year)->count() + 1;
        $offerNumber = "JO-{$year}-".str_pad($count, 3, '0', STR_PAD_LEFT);

        $jobOffer = JobOffer::create([
            ...$request->validated(),
            'job_posting_id' => $candidate->job_posting_id,
            'offer_number' => $offerNumber,
            'status' => 'draft',
        ]);

        return redirect()
            ->route('job-offers.show', $jobOffer)
            ->with('success', 'Job offer created successfully! You can now preview and send it.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobOffer $jobOffer): View
    {
        $jobOffer->load(['candidate', 'jobPosting']);

        return view('job-offers.show', compact('jobOffer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobOffer $jobOffer): View|RedirectResponse
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
     * Update the specified resource in storage.
     */
    public function update(StoreJobOfferRequest $request, JobOffer $jobOffer): RedirectResponse
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
     * Preview PDF.
     */
    public function preview(JobOffer $jobOffer)
    {
        return $this->pdfService->preview($jobOffer);
    }

    /**
     * Download PDF.
     */
    public function download(JobOffer $jobOffer)
    {
        return $this->pdfService->download($jobOffer);
    }

    /**
     * Send job offer via email.
     */
    public function send(JobOffer $jobOffer): RedirectResponse
    {
        if ($jobOffer->status === 'sent') {
            return back()->with('error', 'This job offer has already been sent.');
        }

        // Generate PDF
        $pdfPath = $this->pdfService->generate($jobOffer);

        // Send email
        Mail::to($jobOffer->candidate->email)->send(
            new JobOfferMail($jobOffer, $pdfPath)
        );

        // Update job offer
        $jobOffer->update([
            'status' => 'sent',
            'sent_at' => now(),
            'pdf_path' => $pdfPath,
        ]);

        // Update candidate status
        $jobOffer->candidate->update(['status' => 'offer_sent']);

        return redirect()
            ->route('job-offers.show', $jobOffer)
            ->with('success', 'Job offer sent successfully to '.$jobOffer->candidate->email.'!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobOffer $jobOffer): RedirectResponse
    {
        $jobOffer->delete();

        return redirect()
            ->route('job-offers.index')
            ->with('success', 'Job offer deleted successfully!');
    }
}
