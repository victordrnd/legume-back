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
            'id' => $this->id,
            'quantity' => $this->quantity,
            'order_id' => $this->order_id,
            'delivered_quantity' => $this->delivered_quantity,
            'buyable_type' => $this->buyable_type,
            'product' => $this->item
        ];
    }
}
