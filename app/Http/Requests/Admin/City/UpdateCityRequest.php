<?php

namespace App\Http\Requests\Admin\City;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCityRequest extends FormRequest
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
        $id = $this->route('id');
        return [
            'name' => 'required|unique:cities,name,'.$id,
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
