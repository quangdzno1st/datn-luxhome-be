<?php

namespace App\Http\Requests\Admin\Region;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CreateReigonRequest extends FormRequest
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
            'name' => 'required|unique:regions'
        ];
    }

    public function  messages()
    {
        return [
            'name.required' => 'Tên miền đang trống',
            'name.unique' => 'Tên miền đã tồn tại'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'Thêm miền không thành công.');

        parent::failedValidation($validator);
    }
}
