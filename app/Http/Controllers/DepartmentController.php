<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function edit(Request $request): View
    {
        $query = Department::with('lead');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('description', 'like', "%{$request->search}%");
        }

        $departments = $query->paginate(10);
        $users = User::all();

        return view('departments.edit', compact('departments', 'users'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_lead_id' => 'nullable|exists:users,id',
        ]);

        $department->update($validated);

        return redirect()->route('departments.edit')
            ->with('success', __('Department updated successfully.'));
    }
}
