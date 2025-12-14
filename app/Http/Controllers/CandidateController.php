<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidateRequest;
use App\Http\Requests\StoreInterviewRequest;
use App\Http\Requests\UpdateCandidateRequest;
use App\Mail\InterviewScheduledMail;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\JobPosting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Candidate::with(['jobPosting', 'jobOffer'])->latest();

        // Filter by job posting
        if ($request->filled('job_posting_id')) {
            $query->where('job_posting_id', $request->job_posting_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $candidates = $query->paginate(15);
        $jobPostings = JobPosting::where('is_active', true)->get();

        return view('candidates.index', compact('candidates', 'jobPostings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $jobPostings = JobPosting::where('is_active', true)->get();

        return view('candidates.create', compact('jobPostings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCandidateRequest $request): RedirectResponse
    {
        $candidate = Candidate::create($request->validated());

        return redirect()
            ->route('candidates.show', $candidate)
            ->with('success', 'Candidate created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate): View
    {
        $candidate->load(['jobPosting', 'interviews', 'jobOffer']);

        return view('candidates.show', compact('candidate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidate $candidate): View
    {
        $jobPostings = JobPosting::where('is_active', true)->get();

        return view('candidates.edit', compact('candidate', 'jobPostings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCandidateRequest $request, Candidate $candidate): RedirectResponse
    {
        $candidate->update($request->validated());

        return redirect()
            ->route('candidates.show', $candidate)
            ->with('success', 'Candidate updated successfully!');
    }

    /**
     * Update candidate status.
     */
    public function updateStatus(Request $request, Candidate $candidate): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'string', 'in:new,reviewing,interview_scheduled,interviewed,offer_sent,accepted,rejected,withdrawn'],
        ]);

        $candidate->update(['status' => $request->status]);

        return back()->with('success', 'Candidate status updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate): RedirectResponse
    {
        $candidate->delete();

        return redirect()
            ->route('candidates.index')
            ->with('success', 'Candidate deleted successfully!');
    }

    // =====================
    // Interview Methods
    // =====================

    /**
     * Display a listing of interviews.
     */
    public function interviewIndex(Request $request): View
    {
        $query = Interview::with('candidate.jobPosting')->latest('scheduled_at');

        // Filter upcoming or past
        if ($request->filled('filter')) {
            if ($request->filter === 'upcoming') {
                $query->where('scheduled_at', '>=', now());
            } elseif ($request->filter === 'past') {
                $query->where('scheduled_at', '<', now());
            }
        }

        $interviews = $query->paginate(15);

        return view('interviews.index', compact('interviews'));
    }

    /**
     * Show the form for creating a new interview.
     */
    public function interviewCreate(Request $request): View
    {
        $candidate = null;
        if ($request->filled('candidate_id')) {
            $candidate = Candidate::findOrFail($request->candidate_id);
        }

        $candidates = Candidate::with('jobPosting')
            ->get();

        $selectedCandidateId = $request->input('candidate_id');

        return view('interviews.create', compact('candidates', 'candidate', 'selectedCandidateId'));
    }

    /**
     * Store a newly created interview in storage.
     */
    public function interviewStore(StoreInterviewRequest $request): RedirectResponse
    {
        $interview = Interview::create($request->validated());

        // Load relationships for email
        $interview->load('candidate.jobPosting');

        // Update candidate status
        $interview->candidate->update(['status' => 'interview_scheduled']);

        // Send email notification to candidate
        Mail::to($interview->candidate->email)->send(
            new InterviewScheduledMail($interview)
        );

        return redirect()
            ->route('interviews.show', $interview)
            ->with('success', 'Interview scheduled successfully! Email notification sent to '.$interview->candidate->email);
    }

    /**
     * Display the specified interview.
     */
    public function interviewShow(Interview $interview): View
    {
        $interview->load('candidate.jobPosting');

        return view('interviews.show', compact('interview'));
    }

    /**
     * Show the form for editing the specified interview.
     */
    public function interviewEdit(Interview $interview): View
    {
        $candidates = Candidate::with('jobPosting')->get();

        return view('interviews.edit', compact('interview', 'candidates'));
    }

    /**
     * Update the specified interview in storage.
     */
    public function interviewUpdate(StoreInterviewRequest $request, Interview $interview): RedirectResponse
    {
        // Check if the scheduled_at date/time changed
        $dateTimeChanged = $interview->isDirty('scheduled_at') ||
            $request->scheduled_at != $interview->scheduled_at;

        $interview->update($request->validated());

        // Load relationships
        $interview->load('candidate.jobPosting');

        // Send email if date/time or location changed
        if ($dateTimeChanged || $request->location != $interview->getOriginal('location')) {
            Mail::to($interview->candidate->email)->send(
                new InterviewScheduledMail($interview)
            );

            return redirect()
                ->route('interviews.show', $interview)
                ->with('success', 'Interview updated successfully! Email notification sent to '.$interview->candidate->email);
        }

        return redirect()
            ->route('interviews.show', $interview)
            ->with('success', 'Interview updated successfully!');
    }

    /**
     * Remove the specified interview from storage.
     */
    public function interviewDestroy(Interview $interview): RedirectResponse
    {
        $interview->delete();

        return redirect()
            ->route('interviews.index')
            ->with('success', 'Interview cancelled successfully!');
    }
}
