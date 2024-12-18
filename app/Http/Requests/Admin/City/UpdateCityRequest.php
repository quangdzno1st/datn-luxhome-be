<?php

namespace App\Http\Requests\Admin\City;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

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
            'name' => 'required|unique:cities,name,' . $id,
            'region_id' => 'required|exists:regions,id',
            'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên thành phố đang trống',
            'name.unique' => 'Tên thành phố đã tồn tại',
            'region_id.required' => 'Chọn 1 miền',
            'region_id.exists' => 'Miền không tồn tại',
            'thumbnail.mimes' => 'Ảnh không đúng định dạng'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'Sửa thành phố không thành công.');

        parent::failedValidation($validator);
    }
}
