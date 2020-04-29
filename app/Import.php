<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $fillable = ['from', 'to'];

    public function products(){
        return $this->hasMany(Produit::class,'import_id');
    }

    public function paniers(){
        return $this->hasMany(Panier::class);
    }



    protected static function boot() {
        parent::boot();

        static::deleting(function($import) {
            $import->products->each(function($product) {
                $product->delete();
            });

            $import->paniers->each(function($panier) {
                $panier->delete();
            });
        });
    }
}
