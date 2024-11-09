<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CatalogueRequest extends FormRequest
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
            'status' => 'required|string',
            'description' => 'nullable|string',
            'org_id' => 'string',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif',
            'view' => 'required|numeric',
            'like' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên',
            'org_id.required' => 'Vui lòng khách sạn',
            'price.required' => 'Vui lòng nhập giá',
            'status.required' => 'Vui lòng nhập trạng thái',
            'view.required' => 'Vui lòng nhập lượt xem',
            'like.required' => 'Vui lòng nhập lượt thích',
            'description.required' => 'Vui lòng nhập mô tả',
            'images.required' => 'Vui lòng nhập hình ảnh',
            'thumbnail.required' => 'Vui lòng nhập ảnh đại diện',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();

        throw new HttpResponseException(
            response()->json([
                'result' => false,
                'errors' => $errors,
            ], 422)
        );
    }
}
