<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = ['libelle', 'origin', 'unit_price', 'unit', 'category_id', 'import_id'];

    protected $hidden = ['created_at', 'updated_at', 'import_id'];


    public function category(){
        return $this->belongsTo(Category::class);
    }
}

