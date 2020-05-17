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
        $this->registerSchedule();
        $start_date = Carbon::now()->addHours(2);
        $start_date->minute = ceil($start_date->minute / Schedule::PERIOD_TIME) * Schedule::PERIOD_TIME;
        $start_date->second = 0;
        $dates = [];
        while($start_date <= Carbon::now()->addDays(15)) {
            $horraire = $start_date->addMinute(Schedule::PERIOD_TIME);
            if($horraire->isOpen()){
                $schedule = new Schedule;
                $current_count = Booking::where('schedule', $horraire->toDateTimeString())->count();
                $schedule->remaining = Booking::MAX_PER_PERIOD - $current_count;
                if($schedule->remaining > 0){
                    $schedule->schedule = $horraire->toTimeString();
                    $schedule->availability = Booking::MAX_PER_PERIOD;
                    $dates[$start_date->toDateString()][] = $schedule;
                }
            }else{
                $start_date->nextOpen();
            }
        }
        return $dates;
    }

    public function registerSchedule(){
        BusinessTime::enable(Carbon::class, [
            'monday' => [],
            'tuesday' => ['15:45-20:00'],
            'wednesday' => [],
            'thursday' => [],
            'friday' => ['15:45-20:00'],
            'saturday' => ['09:45-13:00'],
            'sunday' => [],
            'exceptions' => [
                '01-01' => [],
                '12-25' => [],
            ],
            'holidays' => [],
        ]);
    }
}
