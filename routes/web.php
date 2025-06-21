<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Filament\Facades\Filament;

use App\Models\Staff;

Route::get('/calendar/staff-events', function () {
    $slotTimes = [
        'slot1' => ['08:00:00', '10:00:00'],
        'slot2' => ['10:00:00', '12:00:00'],
        'slot3' => ['14:00:00', '16:00:00'],
    ];

    $staffList = Staff::all();
    $events = [];

    foreach ($staffList as $staff) {
        foreach (['slot1', 'slot2', 'slot3'] as $slotKey) {
            if ($staff->$slotKey === '1' || $staff->$slotKey === 1 || $staff->$slotKey === true) {
                $events[] = [
                    'title' => "{$staff->name} ({$slotKey})",
                    'startRecur' => now()->toDateString(),
                    'daysOfWeek' => [dayToNumber($staff->day)],
                    'startTime' => $slotTimes[$slotKey][0],
                    'endTime' => $slotTimes[$slotKey][1],
                ];
            }
        }
    }

    return response()->json($events);
});

function dayToNumber($day)
{
    return [
        'Sunday' => 0,
        'Monday' => 1,
        'Tuesday' => 2,
        'Wednesday' => 3,
        'Thursday' => 4,
        'Friday' => 5,
        'Saturday' => 6,
    ][$day];
}


Route::get('/', function () {
    return view('landing');

});
