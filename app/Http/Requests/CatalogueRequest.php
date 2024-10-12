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
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|boolean',
            'description' => 'required|string',
            'org_id' => 'string'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên',
            'hotel_id.required' => 'Vui lòng khách sạn',
            'price.required' => 'Vui lòng nhập giá',
            'status.required' => 'Vui lòng nhập trạng thái',
            'description.required' => 'Vui lòng nhập mô tả',
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
