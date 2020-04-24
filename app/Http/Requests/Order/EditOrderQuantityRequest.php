<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class EditOrderQuantityRequest extends FormRequest
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
            'items.*.id' => 'required|integer|exists:produits,id',
            'items.*.delivered_quantity' => 'required|integer'
        ];
    }
}
