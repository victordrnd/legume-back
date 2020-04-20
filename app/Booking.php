<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    CONST MAX_PER_PERIOD = 5;
    
    protected $fillable = ['schedule', 'user_id', 'status_id'];
    

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
