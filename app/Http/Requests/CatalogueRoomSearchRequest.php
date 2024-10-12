<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CatalogueRoomSearchRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'start_date' => 'required|date_format:Y-m-d H:i:s|before:end_date',
            'end_date' => 'required|date_format:Y-m-d H:i:s|after:start_date',
            'number_of_people' => 'required|integer|min:1',
        ];
    }
}
