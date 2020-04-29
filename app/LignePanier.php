<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LignePanier extends Model
{
    protected $fillable = ['panier_id', 'produit_id', 'quantity'];

    protected $hidden = ['updated_at', 'created_at'];

    public function product(){
        return $this->belongsTo(Produit::class, 'produit_id');
    }
}
