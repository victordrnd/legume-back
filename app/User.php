<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['firstname', 'lastname', 'email', 'password', 'phone', 'country', 'role_id', 'stripe_id'];

    protected $hidden = ['password', 'stripe_id'];




    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }


    public function role(){
        return $this->belongsTo(Role::class);
    }



}
