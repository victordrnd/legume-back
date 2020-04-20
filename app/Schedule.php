<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    CONST PERIOD_TIME = 15; 
    protected $fillable = ['schedule', 'remaining', 'availability'];
}
