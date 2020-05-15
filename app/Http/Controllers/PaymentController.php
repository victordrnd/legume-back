<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\ConfirmPaymentRequest;
use Illuminate\Http\Request;
use Stripe;
use App\Booking;
use App\Http\Requests\Payment\ChargeRequest;
use App\Http\Resources\BookingResource;
use App\Status;

class PaymentController extends Controller
{


    public function createPaymentIntent()
    {
        try {
            $intent = \Stripe\SetupIntent::create([
                'usage' => 'off_session',
                'payment_method_types' => ['card'],
                'customer' => auth()->user()->stripe_id
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        return $intent;
    }



    public function confirmPayment(ConfirmPaymentRequest $req)
    {
        if (!$req->input('is_espece', false)) {
            $intent = \Stripe\SetupIntent::retrieve($req->intent['id']);
            if ($intent['status'] == "succeeded") {
                $booking = Booking::find($req->booking_id);
                $booking->setup_intent = $intent->id;
                $booking->save();
                return $booking;
            } else {
                return response()->json(['error' => "Une erreur est survenue"], 409);
            }
        } else {
            $booking = Booking::find($req->booking_id);
            $booking->setup_intent = 'cash';
            $booking->save();
            return $booking;
        }
    }

    public function charge(ChargeRequest $req)
    {
        $booking = Booking::where('id', $req->id)->with('user', 'order')->first();
        $finished = Status::where('slug', 'finished')->first()->id;
        if ($booking->setup_intent != 'cash' && $booking->status_id != $finished) {
            $setup_intent = \Stripe\SetupIntent::retrieve($booking->setup_intent);
            try {
                $pm = \Stripe\PaymentIntent::create([
                    'amount' => ceil($booking->price * 100),
                    'currency' => 'eur',
                    'payment_method_types' => ['card'],
                    'receipt_email' => $booking->user->email,
                    'customer' => $booking->user->stripe_id,
                    'payment_method' => $setup_intent->payment_method,
                    'statement_descriptor' => "remyvouslivre.fr",
                    'description' => 'Prélèvement commande #' . $booking->order->id . ' du ' . $booking->schedule
                ]);
                $pm->confirm([
                    'error_on_requires_action' => true,
                    'off_session' => true,
                ]);
                //$pm->capture();
            } catch (\Exception $e) {
                return response()->json(["error" => $e->getMessage()], 409);
            }
        }
        $booking->status_id = $finished;
        $booking->save();
        return $booking;
    }
}
