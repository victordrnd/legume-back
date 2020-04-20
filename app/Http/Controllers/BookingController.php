<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\BookRequest;
use Illuminate\Http\Request;
use App\Booking;
use App\Status;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function getMyBookings(){
        $coming = Booking::where('user_id', auth()->user()->id)->where('schedule', '>=',Carbon::now())->with('status')->get();
        $past = Booking::where('user_id', auth()->user()->id)->where('schedule', '<',Carbon::now())->with('status')->get();
        $data = [
            'coming' => $coming,
            'past' => $past
        ];
        return $data;

    }

    /**
     * @var string date "2020-03-09
     * @var string time "12:00:00"
     */
    public function createBooking(BookRequest $request){
        $datetime = $request->date." ".$request->time;
        $count = Booking::where('schedule', $datetime)->count();
        if($count < 5){
            $booking = Booking::create([
                'schedule' => $datetime,
                'user_id' => auth()->user()->id,
                'status_id' => Status::where('slug', 'waiting')->first()->id
            ]);
        }else{
            return response()->json(['error' => "L'horraire demandé est déjà complet"], 422);
        }
        return $booking;
    }
}
