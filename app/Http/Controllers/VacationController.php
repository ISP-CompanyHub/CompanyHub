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
        // For the quick preview you can just return the collection/array.
        return view('vacation.index', compact('vacationRequests'));
    }
    /**
     * Display a listing of the resource (combined vacations + holidays).
     */

    /**
    public function index()
    {
        $perPage = 15;
        $page = (int) request()->get('page', 1);

        $vacations = Vacation::orderBy('vacation_start', 'desc')->get()->map(function ($v) {
            return (object) [
                'uid' => 'vac-' . $v->id,
                'model' => 'vacation',
                'model_id' => $v->id,
                'submission_date' => $v->submission_date,
                'start' => $v->vacation_start,
                'end' => $v->vacation_end,
                'type' => $v->type,
                'status' => $v->status,
                'comments' => $v->comments,
            ];
        });

        $holidays = Holiday::orderBy('holiday_date', 'desc')->get()->map(function ($h) {
            return (object) [
                'uid' => 'hol-' . $h->id,
                'model' => 'holiday',
                'model_id' => $h->id,
                'submission_date' => null,
                'start' => $h->holiday_date,
                'end' => $h->holiday_date,
                'type' => $h->title,
                'status' => 'holiday',
                'comments' => $h->type ?? '',
            ];
        });

        $items = $vacations->concat($holidays)
            ->sortByDesc(function ($i) {
                return $i->start ? $i->start->getTimestamp() : 0;
            })->values();

        $total = $items->count();
        $results = $items->forPage($page, $perPage);

        $paginator = new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);

        return view('vacation.index', ['vacationRequests' => $paginator]);
    }
     */
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
        // Only allow users who can view/approve vacations to access this page.
        // You can change the permission name if you use a different one.
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
}
