<?php

namespace App\Http\Requests\Booking;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class SetStatusRequest extends ApiRequest
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
            'status' => 'string|required|in:finished,canceled',
            'id' => 'integer|required|exists:bookings,id'
        ];
    }
}
