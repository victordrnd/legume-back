<?php
namespace App\Http\Services;
use Carbon\Carbon;
use App\Schedule;
class ScheduleService
{
    private $slot_duration = 15;
    public function getAvailability()
    {
        $dt = Carbon::now()->addHours(2);
        $dt->minute = ceil($dt->minute / $this->slot_duration) * $this->slot_duration;
        $dt->second = 0;
        $start_date = $dt;
        $end_date = Carbon::now()->addDays(15);
        $slot_duration = $this->slot_duration;
        $dates = [];
        $slots = $start_date->diffInMinutes($end_date) / $slot_duration;
        $schedule = new Schedule;
        $schedule->schedule = $start_date->toTimeString();
        $schedule->availability = 3;
        $schedule->remaining = 3;
        $dates[$start_date->toDateString()][] = $schedule;
        for ($s = 0; $s <= $slots; $s++) {
            $horraire = $start_date->addMinute($slot_duration);
            if($horraire->hour <= 17 && $horraire->hour >= 8 && ($horraire->hour < 12 || $horraire->hour >=13)){
                $schedule = new Schedule;
                $schedule->schedule = $horraire->toTimeString();
                $schedule->availability = 3;
                $schedule->remaining = 3;
                $dates[$start_date->toDateString()][] = $schedule;
            }
        }

        return $dates;
    }
}
