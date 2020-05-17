<?php

namespace App\Http\Resources;

use App\Produit;
use Illuminate\Http\Resources\Json\JsonResource;

class LignePanierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'quantity' => $this->quantity,
            'product' => ProductResource::make($this->product)
        ];
    }
}
