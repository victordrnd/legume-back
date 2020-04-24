<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resetpassword extends Model
{
    protected $fillable = ['token', 'user_id'];
}
