<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\EditOrderQuantityRequest;
use App\Http\Requests\Order\PrepareOrderRequest;
use App\Http\Requests\Order\SetOrderPreparatorRequest;
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

    public function prepareOrder(PrepareOrderRequest $req){
        //TODO : check if current user is admin
        Order::where('id', $req->order_id)->update([
            'preparator_id' => auth()->user()->id
        ]);
        return OrderResource::make(Order::find($req->order_id));
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
