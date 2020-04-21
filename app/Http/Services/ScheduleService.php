<?php

namespace App\Http\Services;

use Carbon\Carbon;
use App\Schedule;
use App\Booking;
use Cmixin\BusinessTime;

class ScheduleService
{
    public function getAvailability()
    {
        BusinessTime::enable(Carbon::class, [
            'monday' => ['08:45-12:00', '13:15-18:00'],
            'tuesday' => ['08:45-12:00', '13:15-18:00'],
            'wednesday' => ['08:45-12:00', '13:15-18:00'],
            'thursday' => ['08:45-12:00', '13:15-18:00'],
            'friday' => ['08:45-12:00', '13:15-18:00'],
            'saturday' => ['08:59-12:00'],
            'sunday' => [],
            'exceptions' => [
                '01-01' => [],
                '12-25' => [],
            ],
            'holidays' => [],
        ]);
        $start_date = Carbon::now()->addHours(2);
        $start_date->minute = ceil($start_date->minute / Schedule::PERIOD_TIME) * Schedule::PERIOD_TIME;
        $start_date->second = 0;
        $dates = [];
        while($start_date <= Carbon::now()->addDays(15)) {
            $horraire = $start_date->addMinute(Schedule::PERIOD_TIME);
            if($horraire->isOpen()){
                $schedule = new Schedule;
                $schedule->schedule = $horraire->toTimeString();
                $schedule->availability = Booking::MAX_PER_PERIOD;
                $current_count = Booking::where('schedule', $horraire->toDateTimeString())->count();
                $schedule->remaining = Booking::MAX_PER_PERIOD - $current_count;
                $dates[$start_date->toDateString()][] = $schedule;
            }else{
                $start_date->nextOpen();
            }
        }
        return $dates;
    }
}
