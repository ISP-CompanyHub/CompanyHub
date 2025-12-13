<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalaryLogRequest;
use App\Http\Requests\UpdateSalaryLogRequest;
use App\Mail\SalarySlipMail;
use App\Models\SalaryLog;
use App\Models\User;
use App\Services\SalarySlipPdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SalaryController extends Controller
{
    public function __construct(
        protected SalarySlipPdfService $pdfService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $salaryLogs = SalaryLog::with(['user', 'salaryComponents'])->latest()->paginate(10);
        return view('salaries.index', compact('salaryLogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::all();
        return view('salaries.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalaryLogRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $salaryLog = SalaryLog::create([
            'user_id' => $validated['user_id'],
            'period_from' => $validated['period_from'],
            'period_until' => $validated['period_until'],
        ]);

        $gross = 0;
        $net = 0;

        foreach ($validated['components'] as $componentData) {
            $amount = $componentData['amount'];

            $salaryLog->salaryComponents()->create([
                'name' => $componentData['name'],
                'sum' => $amount,
            ]);

            if ($amount > 0) {
                $gross += $amount;
            }
            $net += $amount;
        }

        $salaryLog->update([
            'gross_salary' => $gross,
            'net_salary' => $net,
        ]);

        return redirect()->route('salaries.index')->with('success', 'Salary log created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SalaryLog $salary): View
    {
        $salary->load(['user', 'salaryComponents']);
        return view('salaries.show', ['salaryLog' => $salary]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryLog $salary): View
    {
        $users = User::all();
        $salary->load('salaryComponents');

        return view('salaries.edit', [
            'salaryLog' => $salary,
            'users' => $users,
            'salaryComponents' => $salary->salaryComponents,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalaryLogRequest $request, SalaryLog $salary): RedirectResponse
    {
        $validated = $request->validated();

        $salary->update([
            'user_id' => $validated['user_id'],
            'period_from' => $validated['period_from'],
            'period_until' => $validated['period_until'],
        ]);

        // Remove old components
        $salary->salaryComponents()->delete();

        $gross = 0;
        $net = 0;

        // Add new components
        foreach ($validated['components'] as $componentData) {
            $amount = $componentData['amount'];

            $salary->salaryComponents()->create([
                'name' => $componentData['name'],
                'sum' => $amount,
            ]);

            if ($amount > 0) {
                $gross += $amount;
            }
            $net += $amount;
        }

        $salary->update([
            'gross_salary' => $gross,
            'net_salary' => $net,
        ]);

        return redirect()->route('salaries.show', $salary)->with('success', 'Salary log updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryLog $salary): RedirectResponse
    {
        $salary->delete();

        return redirect()->route('salaries.index')->with('success', 'Salary log deleted successfully.');
    }

    public function monthly(Request $request): View
    {
        $users = User::all();
        $reportData = null;

        if ($request->has('user_id') && $request->has('month') && $request->user_id) {
            $selectedUser = User::findOrFail($request->user_id);
            $reportData = $this->calculateMonthlyData($selectedUser, $request->month);
        }

        return view('salaries.monthly', compact('users', 'reportData'));
    }

    public function sendEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|date_format:Y-m',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $reportData = $this->calculateMonthlyData($user, $validated['month']);

        if (!$reportData) {
            return back()->with('error', 'No salary data found for this month.');
        }

        $pdfPath = $this->pdfService->generate($user, $validated['month'], $reportData);

        Mail::to($user->email)->send(new SalarySlipMail($user, $validated['month'], $pdfPath));

        return back()->with('success', 'Salary slip sent successfully.');
    }

    private function calculateMonthlyData(User $user, string $month): ?array
    {
        $selectedDate = \Carbon\Carbon::parse($month);
        $monthStart = $selectedDate->copy()->startOfMonth();
        $monthEnd = $selectedDate->copy()->endOfMonth();

        $salaryLogs = SalaryLog::where('user_id', $user->id)
            ->where(function ($query) use ($monthStart, $monthEnd) {
                $query->where('period_from', '<=', $monthEnd)
                    ->where('period_until', '>=', $monthStart);
            })
            ->with('salaryComponents')
            ->orderBy('period_from')
            ->get();

        if ($salaryLogs->isEmpty()) {
            return null;
        }

        $netSalary = $salaryLogs->sum('net_salary');
        $grossSalary = $salaryLogs->sum('gross_salary');

        return [
            'selectedUser' => $user,
            'salaryLogs' => $salaryLogs,
            'netSalary' => $netSalary,
            'grossSalary' => $grossSalary,
        ];
    }
}