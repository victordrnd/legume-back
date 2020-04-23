<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Order;
use App\OrderLine;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function createOrder(CreateOrderRequest $req){
        $order = Order::create();
        foreach($req->items as $item){
            OrderLine::create([
                'order_id' => $order->id,
                'quantity' => $item['quantity'],
                'product_id' => $item['id']
            ]);
        }
        Booking::find($req->booking_id)->update([
            'order_id' => $order->id,
            'status_id' => Status::where('slug', 'confirmed')->first()->id
        ]);
        return OrderResource::make($order);
    }

}
