<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class HolidayController extends Controller
{
    /**
     * Display a listing of holidays.
     *
     * Uses mock data when app()->environment('local'), and real DB otherwise.
     * Always passes a LengthAwarePaginator in $holidays so the view can call ->links().
     */
    public function index()
    {
        $holidays = Holiday::paginate(15);
        return view('vacation.holiday', compact('holidays'));
    }

    /**
     * For modal-based creation we don't need a separate page.
     * Redirect to index so modal-based flows stay consistent.
     */
    public function create()
    {
        return redirect()->route('holidays.index');
    }

    /**
     * Store a newly created holiday in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'holiday_date' => 'required|date',
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        Holiday::create($data);

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday created successfully.');
    }

    /**
     * Display the specified holiday.
     *
     * Update the view name if you keep holiday show templates elsewhere.
     */
    public function show(Holiday $holiday)
    {
        // Return a holiday show view. If you don't have a dedicated view, redirect to index.
        if (view()->exists('vacation.holiday_show')) {
            return view('vacation.holiday_show', compact('holiday'));
        }

        return redirect()->route('holidays.index');
    }
}
