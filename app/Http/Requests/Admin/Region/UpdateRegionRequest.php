<?php

namespace App\Http\Requests\Admin\Region;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegionRequest extends FormRequest
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
            'name' => 'required|unique:regions,name,'.$id
        ];
    }

    public function  messages()
    {
        return [
            'name.required' => 'Name is require',
            'name.unique' => 'The name has been exists'
        ];
    }
}
