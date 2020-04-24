<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\EditOrderQuantityRequest;
use App\Http\Resources\OrderResource;
use App\Order;
use App\OrderLine;
use App\Status;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function createOrder(CreateOrderRequest $req){
        $order = Order::create();
        foreach($req->items as $item){
            OrderLine::create([
                'order_id' => $order->id,
                'quantity' => $item['quantity'],
                'product_id' => $item['id'],
                'delivered_quantity' => 0
            ]);
        }
        Booking::find($req->booking_id)->update([
            'order_id' => $order->id,
            'status_id' => Status::where('slug', 'confirmed')->first()->id
        ]);
        return OrderResource::make($order);
    }


    public function editQuantity(EditOrderQuantityRequest $req){
        foreach($req->items as $item){
            OrderLine::where('product_id', $item['id'])
                    ->where('order_id', $req->order_id)
                    ->update([
                        'delivered_quantity' => $item['delivered_quantity']
                    ]);
        }
        return OrderResource::make(Order::find($req->order_id));
    }

}
