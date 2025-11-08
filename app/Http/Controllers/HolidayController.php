<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class HolidayController extends Controller
{
    /**
     * Display a listing of holidays.
     *
     * Uses mock data when app()->environment('local'), and real DB otherwise.
     * Always passes a LengthAwarePaginator in $holidays so the view can call ->links().
     */
    public function index(Request $request)
    {
        // Local/dev: provide deterministic mock data so page works without seeding DB
        if (app()->environment('local')) {
            $items = collect([
                (object) ['id' => 1, 'holiday_date' => Carbon::parse('2025-01-01'), 'title' => "New Year's Day",             'type' => 'national'],
                (object) ['id' => 2, 'holiday_date' => Carbon::parse('2025-03-08'), 'title' => "International Women's Day", 'type' => 'observance'],
                (object) ['id' => 3, 'holiday_date' => Carbon::parse('2025-05-01'), 'title' => "Labor Day",                 'type' => 'national'],
                (object) ['id' => 4, 'holiday_date' => Carbon::parse('2025-07-04'), 'title' => "Independence Day",         'type' => 'national'],
                (object) ['id' => 5, 'holiday_date' => Carbon::parse('2025-08-15'), 'title' => "Company Day Off",          'type' => 'company'],
                (object) ['id' => 6, 'holiday_date' => Carbon::parse('2025-11-28'), 'title' => "Founders Day",             'type' => 'company'],
                (object) ['id' => 7, 'holiday_date' => Carbon::parse('2025-12-25'), 'title' => "Christmas Day",            'type' => 'national'],
            ])->sortByDesc(function ($h) {
                return $h->holiday_date->getTimestamp();
            })->values();

            $perPage = 20;
            $page = (int) $request->get('page', 1);
            $total = $items->count();
            $results = $items->forPage($page, $perPage)->values();

            $holidays = new LengthAwarePaginator(
                $results,
                $total,
                $perPage,
                $page,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );

            // Render the holidays view you moved under resources/views/vacation/holiday.blade.php
            return view('vacation.holiday', compact('holidays'));
        }

        // Production / non-local: use real data from DB
        $holidays = Holiday::orderBy('holiday_date', 'desc')->paginate(20);

        // Render the same view and pass the paginator
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
        $this->authorize('create', Holiday::class);

        $data = $request->validate([
            'holiday_date' => 'required|date',
            'title'        => 'required|string|max:255',
            'type'         => 'required|string|max:255',
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
