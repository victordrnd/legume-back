<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['libelle', 'slug'];
    protected $hidden = ['created_at', 'updated_at'];

    public function products(){
        return $this->hasMany(Produit::class);
    }
}
