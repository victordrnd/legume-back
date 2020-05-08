<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;
use App\Http\Services\ScheduleService;

class ScheduleController extends Controller
{
    private  $scheduleService;
    public function __construct(ScheduleService $scheduleService){
        $this->scheduleService = $scheduleService;
    }
   public function getAll(){
       return ["data" => $this->scheduleService->getAvailability()];
   }
}
