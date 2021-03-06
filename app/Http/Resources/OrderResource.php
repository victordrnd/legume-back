<?php

namespace App\Http\Resources;

use App\Produit;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'preparator' => $this->preparator,
            'items' => OrderLineResource::collection($this->items),
            'total_price' => $this->items->sum(function($line){
                if($line->buyable_type == Produit::class)
                    return $line->product['unit_price'] * $line->quantity;
                else 
                    return $line->panier['price'] * $line->quantity;
            }),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'created' => $this->created_at->diffForHumans(),
        ];
    }
}
