<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['preparator_id'];


    public function items(){
        return $this->hasMany(OrderLine::class);
    }


    public function preparator(){
        return $this->belongsTo(User::class);
    }


    public function booking(){
        return $this->hasOne(Booking::class);
    }
}
