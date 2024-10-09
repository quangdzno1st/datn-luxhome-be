<?php

namespace App\Http\Requests\Api\Service;

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
            'description' => 'nullable|string',
            'price' => 'required|numeric|gte:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên dịch vụ là bắt buộc',
            'price.required' => 'Giá dịch vụ là bắt buộc',
            'price.numeric' => 'Giá dịch vụ phải là 1 số',
            'price.gte:0' => 'Giá dịch vụ phải lớn hơn bằng 0'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $json = [
            'status' => false,
            'message' => $validator->errors()->first()
        ];
        $response = response( $json, 422 );
        throw (new ValidationException($validator, $response))->status(422);
    }
}
