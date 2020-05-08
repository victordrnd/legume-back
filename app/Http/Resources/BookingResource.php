<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            "schedule" => $this->schedule,
            "user" => $this->user,
            "status" => $this->status,
            "order_id" => $this->order_id,
            "order" => OrderResource::make($this->whenLoaded('order')),
            "created_at" => $this->created_at->toDateTimeString(),
            "updated_at" => $this->updated_at->toDateTimeString(),
            "schedule_diff" => $this->schedule->diffForHumans(),
            "created_at_diff" => $this->created_at->diffForHumans()
        ];
    }
}
