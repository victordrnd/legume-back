<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['libelle', 'slug'];

    public function products(){
        return $this->hasMany(Produit::class);
    }
}
