<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    protected $fillable = ['product_id', 'quantity', 'order_id'];
    protected $hidden = ['created_at', 'updated_at'];
    public function product(){
        return $this->belongsTo(Produit::class);
    }
}
