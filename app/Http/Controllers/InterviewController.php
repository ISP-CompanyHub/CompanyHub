<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInterviewRequest;
use App\Mail\InterviewScheduledMail;
use App\Models\Candidate;
use App\Models\Interview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class InterviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
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
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $candidate = null;
        if ($request->filled('candidate_id')) {
            $candidate = Candidate::findOrFail($request->candidate_id);
        }

        $candidates = Candidate::whereIn('status', ['reviewing', 'new'])
            ->with('jobPosting')
            ->get();

        return view('interviews.create', compact('candidates', 'candidate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInterviewRequest $request): RedirectResponse
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
     * Display the specified resource.
     */
    public function show(Interview $interview): View
    {
        $interview->load('candidate.jobPosting');

        return view('interviews.show', compact('interview'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Interview $interview): View
    {
        $candidates = Candidate::with('jobPosting')->get();

        return view('interviews.edit', compact('interview', 'candidates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreInterviewRequest $request, Interview $interview): RedirectResponse
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
     * Remove the specified resource from storage.
     */
    public function destroy(Interview $interview): RedirectResponse
    {
        $interview->delete();

        return redirect()
            ->route('interviews.index')
            ->with('success', 'Interview cancelled successfully!');
    }
}
