<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = ['libelle', 'origin', 'unit_price', 'unit', 'category_id', 'import_id'];

    protected $hidden = ['created_at', 'updated_at', 'import_id'];


    protected $appends = ['type'];



    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function lignes_panier(){
        return $this->hasMany(LignePanier::class);
    }

    public function order_lines(){
        return $this->hasMany(OrderLine::class, 'product_id');
    }

    public function getTypeAttribute(){
        return Produit::class;
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($product) {
            $product->lignes_panier->each(function($lignes_panier) {
                $lignes_panier->delete();
            });

            $product->order_lines->each(function($order_lines) {
                $order_lines->delete();
            });
        });
    }
}

