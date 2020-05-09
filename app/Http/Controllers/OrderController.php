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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function createOrder(CreateOrderRequest $req)
    {
        $validator = Validator::make($req->all(), [
            'items' => [function ($attributes, $value, $fail) {
                foreach ($value as $item) {
                    $count = $item['type']::where('id', $item['id'])->count();
                    if ($count == 0) {
                        $fail("Un produit/panier n'existe pas");
                    }
                }
                return true;
            }]
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => "Un produit/panier n'existe pas dans la base de donnée"]);
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
        //TODO : check if current user is admin
        Order::where('id', $req->order_id)->update([
            'preparator_id' => auth()->user()->id
        ]);
        return OrderResource::make(Order::find($req->order_id));
    }

    public function editQuantity(EditOrderQuantityRequest $req)
    {
        foreach ($req->items as $item) {
            OrderLine::where('product_id', $item['id'])
                ->where('order_id', $req->order_id)
                ->update([
                    'delivered_quantity' => $item['delivered_quantity']
                ]);
        }
        return OrderResource::make(Order::find($req->order_id));
    }
}
