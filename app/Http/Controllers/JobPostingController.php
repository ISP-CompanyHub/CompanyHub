<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobPostingRequest;
use App\Http\Requests\UpdateJobPostingRequest;
use App\Models\JobPosting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JobPostingController extends Controller
{
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
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('job-postings.create');
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
            ->route('job-postings.show', $jobPosting)
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
}
