<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\BookRequest;
use Illuminate\Http\Request;
use App\Booking;
use App\Http\Resources\BookingResource;
use App\Http\Services\ScheduleService;
use App\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookingController extends Controller
{
    
    private $scheduleService;
    public function __construct(ScheduleService $scheduleService){
        $this->scheduleService = $scheduleService;
    }
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
        $this->scheduleService->registerSchedule();
        $datetime = Carbon::parse($request->date." ".$request->time);
        $count = Booking::where('schedule', $datetime)->count();
        if($count < 5 && $datetime->isOpen()){
            $booking = Booking::create([
                'schedule' => $datetime->toDateTimeString(),
                'user_id' => auth()->user()->id,
                'status_id' => Status::where('slug', 'waiting')->first()->id
            ]);
        }else{
            return response()->json(['error' => "L'horraire demandé n'est pas disponible"], 422);
        }
        return $booking;
    }


    public function getAllBookings(Request $req){
        $bookings = Booking::where('schedule' ,'>=', Carbon::now())
                            ->where('status_id', '!=', Status::where('slug', 'canceled')->first()->id)
                            ->paginate($req->input('per_page', 15));
        return BookingResource::collection($bookings);
    }


    public function getBooking($id){
        try{
            $booking = Booking::where('id',$id)->with('order')->firstOrFail();
        }catch(ModelNotFoundException $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }
        return BookingResource::make($booking);
    }   



    public function cancelBooking($id){
        $booking = Booking::where('id',$id)->update([
            'status_id' => Status::where('slug', 'canceled')->first()->id
        ]);
        return BookingResource::make(Booking::find($id));
    }
}
