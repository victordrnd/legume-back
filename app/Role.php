<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['libelle', 'slug', 'level'];
    protected $hidden = ['updated_at', 'created_at'];


    CONST ADMIN = 'administrator';
    CONST OPERATOR = 'operator';
    CONST USER = 'user';
}
