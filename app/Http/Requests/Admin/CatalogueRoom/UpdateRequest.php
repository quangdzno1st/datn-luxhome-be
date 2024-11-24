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
            'price' => 'required|numeric',
            'price_hour' => 'required|numeric',
            'number_adult' => 'required|numeric',
            'number_child' => 'required|numeric',
            'acreage' => 'required|numeric',
            'description' => 'required|string',
            'hotel_id' => 'required|string',
            'images' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif',
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
            'description.required' => 'Vui lòng nhập mô tả',
        ];
    }

    // protected function failedValidation(Validator $validator)
    // {
    //     $errors = $validator->errors()->toArray();

    //     throw new HttpResponseException(
    //         response()->json([
    //             'result' => false,
    //             'errors' => $errors,
    //         ], 422)
    //     );
    // }
}
