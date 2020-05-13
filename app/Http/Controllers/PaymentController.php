<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\ConfirmPaymentRequest;
use Illuminate\Http\Request;
use Stripe;

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



    public function confirmPayment(ConfirmPaymentRequest $req){
        $intent = \Stripe\SetupIntent::retrieve($req->intent['id']);
        if ($intent['status'] == "succeeded") {
            $booking = Booking::find($req->booking_id);
            $booking->setup_intent = $intent->id;
            $booking->save();
        }
    }
}
