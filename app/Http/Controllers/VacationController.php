<?php

namespace App\Http\Controllers;

use App\Models\Vacation;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VacationController extends Controller
{
    public function index(Request $request)
    {
        $vacationRequests = Vacation::all()->where('user_id', $request->user()->id);

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

    public function leaveBalanceGenerate(Request $request)
    {
        $request->validate([
            'vacation_start' => 'required|date',
            'vacation_end' => 'required|date|after_or_equal:vacation_start',
        ]);

        $start = Carbon::parse($request->vacation_start);
        $end = Carbon::parse($request->vacation_end);
        $user = Auth::user();

        // 1. Calculate Earned Days (Accrual)
        $monthsWorked = $start->floatDiffInRealMonths($end);
        $accruedDays = round($monthsWorked * 1.67, 2);

        // 2. Fetch ALL vacation records for history
        $allVacations = Vacation::where('user_id', $user->id)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('vacation_start', [$start, $end])
                    ->orWhereBetween('vacation_end', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('vacation_start', '<', $start)
                            ->where('vacation_end', '>', $end);
                    });
            })
            ->orderBy('vacation_start', 'asc')
            ->get();

        // 3. Calculate "Vacation Taken"
        $vacationDaysTaken = 0;
        $deductibleTypes = ['vacation', 'vacation leave', 'paid time off'];

        foreach ($allVacations as $vacation) {
            $vStart = Carbon::parse($vacation->vacation_start);
            $vEnd = Carbon::parse($vacation->vacation_end);

            // Clamp dates to the report window
            $effectiveStart = $vStart->max($start);
            $effectiveEnd = $vEnd->min($end);

            if ($effectiveEnd->gte($effectiveStart)) {
                if (in_array(strtolower($vacation->type), $deductibleTypes)) {
                    $days = $effectiveStart->diffInDays($effectiveEnd) + 1;
                    $vacationDaysTaken += $days;
                }
            }
        }

        // 4. Calculate Net Balance
        $netBalance = $accruedDays - $vacationDaysTaken;

        $results = [
            'user' => $user,
            'start' => $start,
            'end' => $end,
            'accrued_days' => $accruedDays,
            'taken_days' => $vacationDaysTaken,
            'net_balance' => $netBalance,
            'vacations' => $allVacations,
        ];

        // CHECK IF PDF GENERATION IS REQUESTED
        $pdfDownloadContent = null;
        if ($request->has('generate_pdf') && $request->input('generate_pdf') == '1') {
            $pdf = Pdf::loadView('vacation.leave_balance_pdf', ['results' => $results]);

            // Optional: Set paper size
            $pdf->setPaper('a4', 'portrait');

            // Get content instead of downloading immediately
            $pdfDownloadContent = base64_encode($pdf->output());
        }

        return view('vacation.leave_balance', [
            'results' => $results,
            'pdf_download_content' => $pdfDownloadContent,
        ]);
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
