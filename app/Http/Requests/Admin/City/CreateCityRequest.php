<?php

namespace App\Http\Requests\Admin\City;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

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
            'region_id' => 'required|exists:regions,id',
            'thumbnail' => 'required|mimes:jpeg,png,jpg,gif'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên thành phố đang trống',
            'name.unique' => 'Tên thành phố đã tồn tại',
            'region_id.required' => 'Chọn 1 miền',
            'region_id.exists' => 'Miền không tồn tại',
            'thumbnail.required' => 'Bạn chưa chọn ảnh cho thành phố',
            'thumbnail.mimes' => 'Ảnh không đúng định dạng'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'Thêm thành phố không thành công.');

        parent::failedValidation($validator);
    }
}
