<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class EditOrderQuantityRequest extends ApiRequest
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
            'order_id' => 'required|integer|exists:orders,id',
            'items.*.id' => 'required|integer',
            'items.*.delivered_quantity' => 'required|integer',
            'items.*.type' => 'required|in:App\\Produit,App\\Panier'
        ];
    }
}
