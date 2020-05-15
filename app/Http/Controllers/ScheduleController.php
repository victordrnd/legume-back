<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;
use App\Http\Services\ScheduleService;
use Illuminate\Support\Facades\Cache;

class ScheduleController extends Controller
{
    private  $scheduleService;
    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }


    public function getAll()
    {
        $cached = Cache::remember('schedules', 120, function () {
            return $this->scheduleService->getAvailability();
        });
        return ["data" => $cached];
    }
}
