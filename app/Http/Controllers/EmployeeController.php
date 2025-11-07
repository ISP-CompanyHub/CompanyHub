<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('job_title')) {
            $query->where('job_title', 'like', "%{$request->job_title}%");
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $jobTitles = User::select('job_title')->distinct()->pluck('job_title');
        $profiles = $query->paginate(10);

        return view('profiles.index', compact('profiles', 'jobTitles'));
    }

    public function show(User $employee)
    {
        $employee->load(['department']);

        return view('profiles.show', compact('employee'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('profiles.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'personal_id' => 'required|string|max:50',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);
        return redirect()->route('profiles.index')->with('success', 'Profile created successfully.');
    }

    public function edit(User $employee)
    {
        $departments = Department::all();
        return view('profiles.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'email' => 'required|email|max:255|unique:users,email,' . $employee->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $employee->update($validated);

        return redirect()->route('profiles.show','employee')
            ->with('success', __('Employee profile updated successfully.'));
    }
}
