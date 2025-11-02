<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidateRequest;
use App\Http\Requests\UpdateCandidateRequest;
use App\Models\Candidate;
use App\Models\JobPosting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
}
