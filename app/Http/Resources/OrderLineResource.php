<?php

namespace App\Http\Resources;

use App\Produit;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderLineResource extends JsonResource
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
            'id' => $this->item->id,
            'quantity' => $this->quantity,
            'order_id' => $this->order_id,
            'delivered_quantity' => $this->delivered_quantity,
            'type' => $this->buyable_type,
            'libelle' => $this->item->libelle,
            'price' => round(($this->item->price ?? $this->item->unit_price) *$this->quantity,2),
            'product' => ProductResource::make($this->item)
        ];
    }
}
