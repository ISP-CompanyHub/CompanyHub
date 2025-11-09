<?php

namespace App\Http\Controllers;

use App\Models\SalaryComponent;
use App\Models\SalaryLog;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salaryLogs = SalaryLog::with('user', 'salaryComponents')->latest()->paginate(10);
        return view('salaries.index', compact('salaryLogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('salaries.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_from' => 'required|date',
            'period_until' => 'required|date|after_or_equal:period_from',
            'components' => 'required|array',
            'components.*.name' => 'required|string|max:255',
            'components.*.amount' => 'required|numeric',
        ]);

        $salaryLog = SalaryLog::create($request->only('user_id', 'period_from', 'period_until'));

        foreach ($request->components as $componentData) {
            $component = SalaryComponent::firstOrCreate(['name' => $componentData['name']]);
            $salaryLog->salaryComponents()->attach($component->id, ['amount' => $componentData['amount']]);
        }

        return redirect()->route('salaries.index')->with('success', 'Salary log created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SalaryLog $salary)
    {
        $salary->load('user', 'salaryComponents');
        return view('salaries.show', ['salaryLog' => $salary]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryLog $salary)
    {
        $users = User::all();
        $salaryComponents = $salary->salaryComponents->map(function($component) {
            return (object)[
                'id' => $component->id,
                'name' => $component->name,
                'amount' => $component->pivot->amount,
            ];
        });

        return view('salaries.edit', [
            'salaryLog' => $salary,
            'users' => $users,
            'salaryComponents' => $salaryComponents,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalaryLog $salary)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_from' => 'required|date',
            'period_until' => 'required|date|after_or_equal:period_from',
            'components' => 'required|array',
            'components.*.name' => 'required|string|max:255',
            'components.*.amount' => 'required|numeric',
        ]);

        $salary->update($request->only('user_id', 'period_from', 'period_until'));

        $componentsToSync = [];
        foreach ($request->components as $componentData) {
            $component = SalaryComponent::firstOrCreate(['name' => $componentData['name']]);
            $componentsToSync[$component->id] = ['amount' => $componentData['amount']];
        }
        $salary->salaryComponents()->sync($componentsToSync);

        return redirect()->route('salaries.show', $salary)->with('success', 'Salary log updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryLog $salary)
    {
        $salary->delete();

        return redirect()->route('salaries.index')->with('success', 'Salary log deleted successfully.');
    }

    public function monthly(Request $request)
    {
        $users = User::all();
        $reportData = null;

        if ($request->has('user_id') && $request->has('month') && $request->user_id) {
            $selectedDate = \Carbon\Carbon::parse($request->month);
            $monthStart = $selectedDate->copy()->startOfMonth();
            $monthEnd = $selectedDate->copy()->endOfMonth();
            $selectedUser = User::findOrFail($request->user_id);

            $salaryLogs = SalaryLog::where('user_id', $selectedUser->id)
                ->where(function ($query) use ($monthStart, $monthEnd) {
                    $query->where('period_from', '<=', $monthEnd)
                          ->where('period_until', '>=', $monthStart);
                })
                ->with('salaryComponents')
                ->get();

            if ($salaryLogs->isNotEmpty()) {
                $allComponents = $salaryLogs->flatMap(function ($log) {
                    return $log->salaryComponents;
                });

                $groupedComponents = $allComponents->groupBy('name')->map(function ($components, $name) {
                    return (object)[
                        'name' => $name,
                        'pivot' => (object)['amount' => $components->sum('pivot.amount')],
                    ];
                })->values();

                $netSalary = $groupedComponents->sum('pivot.amount');
                $grossSalary = $groupedComponents->where('pivot.amount', '>', 0)->sum('pivot.amount');

                $reportData = [
                    'selectedUser' => $selectedUser,
                    'groupedComponents' => $groupedComponents,
                    'netSalary' => $netSalary,
                    'grossSalary' => $grossSalary,
                ];
            }
        }

        return view('salaries.monthly', compact('users', 'reportData'));
    }
}
