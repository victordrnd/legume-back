<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\BookRequest;
use Illuminate\Http\Request;
use App\Booking;
use App\Http\Requests\Booking\SetStatusRequest;
use App\Http\Resources\BookingResource;
use App\Http\Services\ScheduleService;
use App\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class BookingController extends Controller
{

    private $scheduleService;
    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }


    public function getMyBookings()
    {
        Booking::where('user_id', auth()->user()->id)
            ->where('schedule', '<', Carbon::now())
            ->where('status_id', Status::where('slug', 'waiting')->first()->id)
            ->update(['status_id' => Status::where('slug', 'canceled')->first()->id]);
        $coming = Booking::where('user_id', auth()->user()->id)->where('schedule', '>=', Carbon::now())->orderBy('schedule', 'DESC')->with('order')->get();
        $past = Booking::where('user_id', auth()->user()->id)->where('schedule', '<', Carbon::now())->orderBy('schedule', 'DESC')->get();
        $data = [
            'coming' => BookingResource::collection($coming),
            'past' => BookingResource::collection($past)
        ];
        return $data;
    }

    /**
     * @var string date "2020-03-09
     * @var string time "12:00:00"
     */
    public function createBooking(BookRequest $request)
    {
        $this->scheduleService->registerSchedule();
        $datetime = Carbon::parse($request->date . " " . $request->time);
        $count = Booking::where('schedule', $datetime)->count();
        if ($count < Booking::MAX_PER_PERIOD && $datetime->isOpen() && $datetime > Carbon::now()) {
            Cache::forget('schedules');
            $booking = Booking::create([
                'schedule' => $datetime->toDateTimeString(),
                'user_id' => auth()->user()->id,
                'status_id' => Status::where('slug', 'waiting')->first()->id,
                'order_id' => null
            ]);
        } else {
            return response()->json(['error' => "L'horraire demandé n'est pas disponible"], 422);
        }
        return $booking;
    }


    public function getAllBookings(Request $req)
    {
        $bookings = Booking::where('schedule', '>=', Carbon::now())
            ->where('status_id', '!=', Status::where('slug', 'canceled')->first()->id)
            ->whereIn('status_id', Status::where('slug', 'confirmed')->orWhere('slug', 'preparation')->pluck('id'))
            ->orderBy('schedule', 'asc')
            ->with('order')
            ->paginate($req->input('per_page', 15));
        return BookingResource::collection($bookings);
    }


    public function getBooking(Booking $booking)
    {
        return BookingResource::make($booking->load('order'));
    }


    public function getBookingByOrderId(Booking $booking)
    {
        return BookingResource::make($booking->load('order'));
    }

    public function cancelBooking(Booking $booking)
    {
        $booking->delete();
        return response()->json(['success' => 'true']);
    }
}
