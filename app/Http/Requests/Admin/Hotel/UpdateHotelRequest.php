<?php

namespace App\Http\Requests\Admin\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHotelRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'slug' => 'max:255',
            'location' => 'required|max:255',
            'quantity_of_room' => 'required|numeric',
            'star'=> 'required|numeric',
            'city_id' => 'required|exists:cities,id',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'quantity_floor' => 'required|numeric'
        ];
    }
}
