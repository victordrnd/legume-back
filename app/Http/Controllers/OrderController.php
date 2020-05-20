<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\EditOrderQuantityRequest;
use App\Http\Requests\Order\PrepareOrderRequest;
use App\Http\Requests\Order\SetOrderPreparatorRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\BookingResource;
use App\Order;
use App\OrderLine;
use App\Status;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function createOrder(CreateOrderRequest $req)
    {
        $validator = self::verifyIsValidProduct($req);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $order = Order::create();
        try {
            Booking::where('id', $req->booking_id)->where('user_id', auth()->user()->id)->firstOrFail()->update([
                'order_id' => $order->id,
                'status_id' => Status::where('slug', 'confirmed')->first()->id
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cette réservation ne vous appartient pas'], 422);
        }
        foreach ($req->items as $item) {
            $count = OrderLine::where('order_id', $order->id)->where('product_id', $item['id'])->where('buyable_type', $item['type'])->count();
            if ($count > 0) {
                $line = OrderLine::where('order_id', $order->id)
                    ->where('product_id', $item['id'])
                    ->where('buyable_type', $item['type'])
                    ->first();
                $line->quantity += $item['quantity'];
                $line->save();
            } else {
                OrderLine::create([
                    'order_id' => $order->id,
                    'quantity' => $item['quantity'],
                    'product_id' => $item['id'],
                    'delivered_quantity' => null,
                    'buyable_type' => $item['type']
                ]);
            }
        }
        return OrderResource::make($order);
    }

    public function prepareOrder(PrepareOrderRequest $req)
    {
        $order_ids = Order::where('preparator_id', auth()->user()->id)->pluck('id');
        $preparing = Booking::whereIn('order_id', $order_ids)->where('status_id', Status::where('slug', 'preparation')->first()->id)->first();
        if ($preparing) {
            if ($preparing->order_id != $req->order_id)
                return response()->json(['error' => 'Vous préparez déjà une commande', 'preparing' => $preparing], 403);
        }
        $order = Order::find($req->order_id);
        if ($order->preparator_id != auth()->user()->id && !is_null($order->preparator_id)) {
            $preparator = $order->preparator->fistname;
            return reponse()->json(['error', "$preparator prépare déjà cette commande"], 422);
        } else {
            $order->update([
                'preparator_id' => auth()->user()->id
            ]);
            $order->booking->update([
                'status_id' => Status::where('slug', 'preparation')->first()->id
            ]);
        }
        return OrderResource::make($order);
    }




    public function editQuantity(EditOrderQuantityRequest $req, Order $order)
    {
        $validator = self::verifyIsValidProduct($req);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        if ($order->booking->status->slug == 'preparation') {
            foreach ($req->items as $item) {
                OrderLine::where('product_id', $item['id'])
                    ->where('order_id', $order->id)
                    ->where('buyable_type', $item['type'])
                    ->update([
                        'delivered_quantity' => $item['delivered_quantity']
                    ]);
            }
        }else{
            return response()->json(['error' => 'Personne ne prépare cette commande'], 422);
        }
        $booking = $order->booking;
        $booking->status_id = Status::where('slug', 'prepared')->first()->id;
        $booking->save();
        return BookingResource::make(Booking::find($booking->id));
    }



    public function updateOrderProducts(Order $order, UpdateOrderRequest $req){
        $validator = self::verifyIsValidProduct($req);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        OrderLine::where('order_id', $order->id)->delete();
        foreach ($req->items as $item) {
            $count = OrderLine::where('order_id', $order->id)->where('product_id', $item['id'])->where('buyable_type', $item['type'])->count();
            if ($count > 0) {
                $line = OrderLine::where('order_id', $order->id)
                    ->where('product_id', $item['id'])
                    ->where('buyable_type', $item['type'])
                    ->first();
                $line->quantity += $item['quantity'];
                $line->save();
            } else {
                OrderLine::create([
                    'order_id' => $order->id,
                    'quantity' => $item['quantity'],
                    'product_id' => $item['id'],
                    'delivered_quantity' => null,
                    'buyable_type' => $item['type']
                ]);
            }
        }
        return OrderResource::make($order);
    }




    public static function verifyIsValidProduct(Request $req){
        $validator = Validator::make($req->all(), [
            'items' => [function ($attributes, $value, $fail) {
                foreach ($value as $item) {
                    $count = $item['type']::where('id', $item['id'])->count();
                    if ($count == 0) {
                        $fail("Le ".$item['type']." #".$item['id']." n'existe pas dans la base de données" );
                    }
                }
                return true;
            }]
        ]);
        return $validator;
    }
}
