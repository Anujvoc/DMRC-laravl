<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    /**
     * Display a listing of the holidays.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch holidays from database
        $holidays = DB::table('mastr_holidays')->orderBy('date', 'asc')->get();
        
        return view('admin.pages.master.holidays.index', compact('holidays'));
    }

    /**
     * Show the form for creating a new holiday.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.master.holidays.create');
    }

    /**
     * Show the form for editing the specified holiday.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $holiday = DB::table('mastr_holidays')->where('id', $id)->first();
        
        if (!$holiday) {
            return redirect()->route('admin.master.holidays')
                ->with('error', 'Holiday not found');
        }
        
        return view('admin.pages.master.holidays.edit', compact('holiday'));
    }

    /**
     * Store a newly created holiday in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'holiday_name' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today'
        ]);

        // Insert into database
        $holidayId = DB::table('mastr_holidays')->insertGetId([
            'holiday_name' => $validated['holiday_name'],
            'date' => $validated['date'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.master.holidays')
            ->with('success', 'Holiday created successfully!');
    }

    /**
     * Update the specified holiday in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'holiday_name' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today'
        ]);

        // Update database
        DB::table('mastr_holidays')->where('id', $id)->update([
            'holiday_name' => $validated['holiday_name'],
            'date' => $validated['date'],
            'updated_at' => now()
        ]);

        return redirect()->route('admin.master.holidays')
            ->with('success', 'Holiday updated successfully!');
    }

    /**
     * Remove the specified holiday from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete from database
        $deleted = DB::table('mastr_holidays')->where('id', $id)->delete();
        
        if ($deleted) {
            return redirect()->route('admin.master.holidays')
                ->with('success', 'Holiday deleted successfully!');
        } else {
            return redirect()->route('admin.master.holidays')
                ->with('error', 'Holiday not found or could not be deleted');
        }
    }

    /**
     * Display the specified holiday.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // For now, return sample data
        // In a real application, you would fetch from database
        $holiday = [
            'id' => $id,
            'name' => 'Sample Holiday',
            'date' => '2024-12-25',
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01'
        ];

        return response()->json([
            'success' => true,
            'data' => $holiday
        ]);
    }

    /**
     * Get holidays data for AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getHolidays(Request $request)
    {
        // For now, return sample data
        // In a real application, you would fetch from database
        $holidays = [
            [
                'id' => 1,
                'name' => 'New Year',
                'date' => '2024-01-01',
                'created_at' => '2024-01-01',
                'updated_at' => '2024-01-01'
            ],
            [
                'id' => 2,
                'name' => 'Republic Day',
                'date' => '2024-01-26',
                'created_at' => '2024-01-01',
                'updated_at' => '2024-01-01'
            ],
            [
                'id' => 3,
                'name' => 'Independence Day',
                'date' => '2024-08-15',
                'created_at' => '2024-01-01',
                'updated_at' => '2024-01-01'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $holidays
        ]);
    }
}
