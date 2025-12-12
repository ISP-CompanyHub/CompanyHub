<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Vacation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VacationController extends Controller
{
    /**
     * Mock data
     * /
     */
    public function index()
    {
        $vacationRequests = Vacation::all();
        return view('vacation.index', compact('vacationRequests'));
    }
    public function create(): View
    {
        return view('vacation.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'submission_date' => 'nullable|date',
            'vacation_start' => 'required|date|before_or_equal:vacation_end',
            'vacation_end' => 'required|date|after_or_equal:vacation_start',
            'type' => 'required|string|max:255',
            'status' => ['required', 'string', Rule::in(['pending', 'approved', 'rejected'])],
            'comments' => 'required|string',
        ]);

        $data['submission_date'] = $data['submission_date'] ?? now();

        // Add this line to assign the current user's ID
        $data['user_id'] = auth()->id();

        Vacation::create($data);

        return redirect()->route('vacation.index')
            ->with('success', 'Vacation request created successfully.');
    }


    public function show(Vacation $vacationRequest)
    {

        return view('vacation.show', compact('vacationRequest'));
    }

    public function edit(Vacation $vacationRequest)
    {

        return view('vacation.edit', compact('vacationRequest'));
    }

    public function update(Request $request, Vacation $vacationRequest)
    {
        $data = $request->validate([
            'submission_date' => 'nullable|date',
            'vacation_start' => 'required|date|before_or_equal:vacation_end',
            'vacation_end' => 'required|date|after_or_equal:vacation_start',
            'type' => 'required|string|max:255',
            'status' => ['required', 'string', Rule::in(['pending', 'approved', 'rejected'])],
            'comments' => 'required|string',
        ]);

        if (empty($data['submission_date'])) {
            unset($data['submission_date']);
        }

        $vacationRequest->update($data);

        return redirect()->route('vacation.index')
            ->with('success', 'Vacation request updated successfully.');
    }

    public function approvals(Request $request)
    {

        if (! auth()->user()->can('view vacation requests') && ! auth()->user()->can('approve vacation requests')) {
            abort(403);
        }

        // Paginate separately for pending and approved lists:
        $perPage = 15;

        $pending = Vacation::where('status', 'pending')
            ->orderBy('submission_date', 'desc')
            ->paginate($perPage, ['*'], 'pending_page');

        $approved = Vacation::where('status', 'approved')
            ->orderBy('submission_date', 'desc')
            ->paginate($perPage, ['*'], 'approved_page');

        return view('vacation.approvals', compact('pending', 'approved'));
    }

    /**
     * Approve a vacation request.
     */
    public function approve(Request $request, Vacation $vacation)
    {
        // Check permission to approve
        if (! auth()->user()->can('approve vacation requests')) {
            abort(403);
        }

        // Only allow approving pending requests
        if ($vacation->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending requests can be approved.');
        }

        $vacation->update(['status' => 'approved']);

        return redirect()->route('vacation.approvals')->with('success', 'Vacation request approved.');
    }

    public function leaveBalanceForm(Request $request)
    {
        return view('vacation.leave_balance');
    }
    public function reject(Request $request, Vacation $vacation)
    {
        // Check permission to approve (using same permission for rejection)
        if (! auth()->user()->can('approve vacation requests')) {
            abort(403);
        }

        // Only allow rejecting pending requests
        if ($vacation->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending requests can be rejected.');
        }

        $vacation->update(['status' => 'rejected']);

        return redirect()->route('vacation.approvals')->with('success', 'Vacation request rejected.');
    }

}
