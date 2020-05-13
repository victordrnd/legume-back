<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    const MAX_PER_PERIOD = 3;

    protected $fillable = ['schedule', 'user_id', 'order_id', 'status_id', 'setup_intent'];


    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function getPriceAttribute()
    {
        return $this->order->items->sum(function ($line) {
            if ($line->buyable_type == Produit::class)
                return $line->product['unit_price'] * $line->quantity;
            else
                return $line->panier['price'] * $line->quantity;
        });
    }
}
