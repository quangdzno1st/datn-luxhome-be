<?php

namespace App\Http\Requests\Admin\CatalogueRoom;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'price_hour' => 'required|numeric|min:0',
            'number_adult' => 'required|numeric|min:0',
            'number_child' => 'required|numeric|min:0',
            'acreage' => 'required|numeric|min:0',
            'description' => 'required|string',
            'hotel_id' => 'required|string',
            'images' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,webp',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên',
            'hotel_id.required' => 'Vui lòng chọn khách sạn',
            'price.required' => 'Vui lòng nhập giá',
            'price_hour.required' => 'Vui lòng nhập giá theo giờ',
            'acreage.required' => 'Vui lòng nhập diện tích',
            'number_adult.required' => 'Vui lòng nhập sức chứa người lớn',
            'number_child.required' => 'Vui lòng nhập sức chứa trẻ em',
            'price.min' => 'Giá loại phòng phải lớn hơn bằng 0',
            'price_hour.min' => 'Giá phạt phải lớn hơn bằng 0',
            'acreage.min' => 'Diện tích phải lớn hơn bằng 0',
            'number_adult.min' => 'Sức chứa người lớn phải lớn hơn bằng 0',
            'number_child.min' => 'Sức chứa trẻ em phải lớn hơn bằng 0',
            'description.required' => 'Vui lòng nhập mô tả',
            'images.*.mimes' => 'Hình ảnh phải có định dạng jpeg, jpg, png, jpg, gif,webp',
            'thumbnail.mimes' => 'Ảnh đại diện phải có định dạng jpeg, jpg, png, jpg, gif,webp',
            'images.*.image' => 'Hình ảnh phải có định dạng jpeg, jpg, png, jpg, gif,webp',
            'thumbnail.image' => 'Ảnh đại diện phải có định dạng jpeg, jpg, png, jpg, gif,webp',
            'price.numeric' => 'Giá phải là 1 số',
            'number_adult.numeric' => 'Số lượng người lớn phải là 1 số',
            'number_child.numeric' => 'Số lượng trẻ em phải là 1 số',
            'acreage.numeric' => 'Diện tích phải là 1 số',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'Cập nhật loại phòng không thành công.');

        parent::failedValidation($validator);
    }
}
