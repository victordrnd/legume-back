<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ConfirmPaymentRequest extends ApiRequest
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
            'intent' => 'required',
            'intent.id' => 'required|string',
            'booking_id' => 'required|integer|exists:bookings,id',
            'is_espece' => 'sometimes|boolean'
        ];
    }
}
