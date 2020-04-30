<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    protected $fillable = ['product_id', 'quantity', 'delivered_quantity', 'buyable_type', 'order_id'];
    protected $hidden = ['created_at', 'updated_at'];


    public function item()
    {
        return $this->morphTo(__FUNCTION__, 'buyable_type', 'product_id');
    }


    public function product()
    {
        return $this->belongsTo(Produit::class, 'product_id');
    }


    public function panier()
    {
        return $this->belongsTo(Panier::class, 'product_id');
    }
}
