<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    CONST MAX_PER_PERIOD = 3;
    
    protected $fillable = ['schedule', 'user_id','order_id', 'status_id'];
    

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
