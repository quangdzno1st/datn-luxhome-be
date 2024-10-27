<?php

namespace App\Http\Requests\Admin\City;

use Illuminate\Foundation\Http\FormRequest;

class CreateCityRequest extends FormRequest
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
            'name' => 'required|unique:cities',
            'region_id' => 'required|exists:regions,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is require',
            'name.unique' => 'Name has been exists',
            'region_id.required' => 'Region is required',
            'region_id.exists' => 'Region not found',
        ];
    }
}
