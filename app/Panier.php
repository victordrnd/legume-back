<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    protected $fillable = ['import_id', 'libelle','price'];

    protected $appends = ['type'];


    public function getTypeAttribute(){
        return Panier::class;
    }
    public function products(){
        return $this->hasMany(LignePanier::class);
    }
}
