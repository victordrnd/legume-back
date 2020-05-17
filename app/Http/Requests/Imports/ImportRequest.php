<?php

namespace App\Http\Requests\Imports;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends ApiRequest
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
            'file' => 'required|file|mimes:xlsx,xls',
            'from' => 'required|date',
            'to' => 'date|required|after:from'
        ];
    }
}
