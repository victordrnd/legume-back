<?php

namespace App\Http\Resources;

use App\Produit;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $type = get_class($this->resource);
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,
            'price' => $type == Produit::class ? $this->unit_price :$this->price,
            'unit' => $type == Produit::class ? $this->unit : "Unité",
            'type' => $type,
            'origin' => $type == Produit::class ? $this->origin : "France",
        ];
    }
}
