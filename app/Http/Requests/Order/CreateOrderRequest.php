<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\ApiRequest;

class CreateOrderRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        return [
            'booking_id' => 'required|integer|exists:bookings,id',
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.type' => 'required|string|in:App\\Produit,App\\Panier'
        ];
    }
}
