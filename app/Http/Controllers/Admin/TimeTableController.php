<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Http\Request;

class TimeTableController extends Controller
{
    /*public function index()
    {
        return view('admin.pages.timetable_management.index');
    }*/

    public function addNewBatch()
{
    $weekOffset = 0;
    $workingDays = [];
    $timeSlots = [];

    return view('admin.pages.timetable_management.index', compact(
        'weekOffset',
        'workingDays',
        'timeSlots'
    ));
}
   public function weeklyTraining(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->format('Y-m-d');
        $weekOffset = (int) ($request->week ?? 0);
        $sessions = (int) ($request->time ?? 8);

        $start = Carbon::parse($startDate)
            ->addWeeks($weekOffset)
            ->startOfWeek(Carbon::MONDAY);

        $end = $start->copy()->endOfWeek(Carbon::SATURDAY);

        $workingDays = [];

        while ($start <= $end) {

            $dayName = $start->format('l');

            // Sunday Skip
            if ($dayName != 'Sunday') {

                // 2,4,5 Saturday Skip
                if ($dayName == 'Saturday') {
                    $weekNumber = ceil($start->day / 7);
                    if (in_array($weekNumber, [2,4,5])) {
                        $start->addDay();
                        continue;
                    }
                }

                $workingDays[] = [
                    'day'  => $dayName,
                    'date' => $start->format('d-m-Y')
                ];
            }

            $start->addDay();
        }

        $timeSlots = $this->generateSlots('09:30', $sessions);

        return view('admin.pages.timetable_management.index', compact(
            'workingDays',
            'timeSlots',
            'weekOffset'
        ));
    }
        private function generateSlots($startTime, $totalSessions)
    {
        $sessionDuration = 60;
        $teaAfter = 2;
        $teaDuration = 15;
        $lunchAfter = 4;
        $lunchDuration = 60;

        $slots = [];
        $currentTime = Carbon::createFromFormat('H:i', $startTime);

        for ($i = 1; $i <= $totalSessions; $i++) {

            $endTime = $currentTime->copy()->addMinutes($sessionDuration);

            $slots[] = [
                'type' => 'session',
                'label' => 'Session ' . $i,
                'time' => $currentTime->format('H:i') . ' - ' . $endTime->format('H:i')
            ];

            $currentTime = $endTime;

            // Tea Break
            if ($i == $teaAfter) {
                $teaEnd = $currentTime->copy()->addMinutes($teaDuration);

                $slots[] = [
                    'type' => 'tea',
                    'label' => 'Tea Break',
                    'time' => $currentTime->format('H:i') . ' - ' . $teaEnd->format('H:i')
                ];

                $currentTime = $teaEnd;
            }

            // Lunch Break
            if ($i == $lunchAfter) {
                $lunchEnd = $currentTime->copy()->addMinutes($lunchDuration);

                $slots[] = [
                    'type' => 'lunch',
                    'label' => 'Lunch Break',
                    'time' => $currentTime->format('H:i') . ' - ' . $lunchEnd->format('H:i')
                ];

                $currentTime = $lunchEnd;
            }
        }

        return $slots;
    }

    public function storeBatch(Request $request)
    {
        $request->validate([
            'batch_name' => 'required|string|max:255',
            'batch_code' => 'required|string|max:50|unique:batches,batch_code',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:Active,Inactive,Completed',
            'description' => 'nullable|string|max:1000'
        ]);

        try {
            DB::table('batches')->insert([
                'batch_name' => $request->batch_name,
                'batch_code' => $request->batch_code,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'capacity' => $request->capacity,
                'status' => $request->status,
                'description' => $request->description,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->back()->with('success', 'Batch added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add batch: ' . $e->getMessage());
        }
    }

    /*function weeklyTraining(Request $request){
           $year  = $request->year ?? date('Y');
    $month = $request->month ?? date('m');
    $week  = $request->week ?? 1;

    $holidays = [
        $year.'-'.$month.'-10',
        $year.'-'.$month.'-21'
    ];

    $startOfMonth = Carbon::create($year, $month, 1);

    // Week start Monday se
    $weekStart = $startOfMonth->copy()->addWeeks($week - 1)->startOfWeek(Carbon::SUNDAY);
    $weekEnd   = $weekStart->copy()->endOfWeek(Carbon::SATURDAY);

    $period = CarbonPeriod::create($weekStart, $weekEnd);

    $workingDays = [];

    foreach ($period as $date) {

        if ($date->month != $month) {
            continue;
        }

        $dayName = $date->format('l');
        $weekNumber = ceil($date->day / 7);

        // Sunday remove
        if ($dayName == 'Sunday') {
            continue;
        }

        // Saturday check
        if ($dayName == 'Saturday' && !in_array($weekNumber, [1,3])) {
            continue;
        }

        // Holiday remove
        if (in_array($date->format('Y-m-d'), $holidays)) {
            continue;
        }

        $workingDays[] = [
            'day'  => $dayName,
            'date' => $date->format('d-m-Y')
        ];
    }

    return view('admin.pages.calender.weekly_training', compact(
        'workingDays','week','month','year'
    ));
        //return view('admin.pages.calender.weekly_training');
    }*/

        /*function weeklyTraining(Request $request){
               $timeSlots8 = [
    "09:30 - 10:30",
    "10:30 - 11:30",
    "11:30 - 12:30",
    "12:30 - 13:30",
    "13:30 - 14:30 (Lunch)",
    "14:30 - 15:30",
    "15:30 - 15:45 (Tea Break)",
    "15:45 - 16:45",
    "16:45 - 17:30"
];

// Tuesday 6 Session Pattern
$timeSlots6 = [
    "09:30 - 10:30",
    "10:30 - 11:30",
    "11:30 - 11:45 (Tea Break)",
    "11:45 - 12:45",
    "12:45 - 13:45 (Lunch)",
    "13:45 - 14:45",
    "14:45 - 15:45"
];
            $startDate = Carbon::parse($request->start_date ?? date('Y-m-01'));
            $endDate = Carbon::parse($request->end_date ?? date('Y-m-t'));
           $weekOffset = (int) ($request->week ?? 0);
    $weekStart = $startDate->copy()->addWeeks($weekOffset)->startOfWeek(Carbon::MONDAY);

            //$weekStart = $startDate->copy()->addWeeks($weekOffset)->startOfWeek(Carbon::MONDAY);
            $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SATURDAY);
             $holidays = [
                    '01-02-2026',
                    '15-02-2026',
                    '03-03-2026',
                    '21-03-2026',
                    '26-03-2026',
                    '31-03-2026',
                    '03-04-2026',
                    '14-04-2026',
                    '01-05-2026',
                    '28-05-2026',
                    '26-06-2026',
                    '15-08-2026',
                    '26-08-2026',
                    '28-08-2026',
                    '04-09-2026',
                    '14-09-2026',
                    '25-09-2026',
                    '02-10-2026',
                    '20-10-2026',
                    '26-10-2026',
                    '08-11-2026',
                    '09-11-2026',
                    '11-11-2026',
                    '15-11-2026',
                    '24-11-2026',
                    '25-12-2026',

                    

                ];
            $workingDays = [];
          $period = CarbonPeriod::create($weekStart, $weekEnd);

            
               
               
                  foreach ($period as $date) {

    if ($date->lt($startDate) || $date->gt($endDate)) {
        continue;
    }

    // Sunday remove
    if ($date->isSunday()) {
        continue;
    }

    // Saturday rule (only 1st & 3rd allowed)
    if ($date->isSaturday()) {

        $firstDayOfMonth = Carbon::create($date->year, $date->month, 1);

        $saturdayNumber = ceil(
            ($date->day + $firstDayOfMonth->dayOfWeek) / 7
        );

        if (!in_array($saturdayNumber, [1,3])) {
            continue;
        }
    }

    // Holiday remove
    if (in_array($date->format('Y-m-d'), $holidays)) {
        continue;
    }

    $workingDays[] = [
        'day'  => $date->format('l'),   // ✅ Day Name
        'date' => $date->format('d-m-Y')
    ];
}

            return view('admin.pages.calender.weekly_training',compact('workingDays','startDate','endDate','weekOffset', 'timeSlots8','timeSlots6'));
        }*/

          
}