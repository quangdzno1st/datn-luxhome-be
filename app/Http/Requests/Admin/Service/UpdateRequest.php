<?php

namespace App\Http\Requests\Admin\Service;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|gte:0',
            'type' => 'required',
            'description' => 'required',
            'hotel_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên dịch vụ là bắt buộc',
            'price.required' => 'Giá dịch vụ là bắt buộc',
            'price.numeric' => 'Giá dịch vụ phải là 1 số',
            'price.gte:0' => 'Giá dịch vụ phải lớn hơn bằng 0',
            'type.required' => 'Loại dịch vụ là bắt buộc',
            'description.required' => 'Loại dịch vụ là bắt buộc',
            'hotel_id.required' => 'Bạn chưa chọn khách sạn'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // dd($validator->errors()->toArray()); // Hiển thị lỗi chi tiết khi xác thực thất bại
        session()->flash('error', 'Sửa dịch vụ không thành công.');

        parent::failedValidation($validator);
    }

}
