<?php

namespace App\Http\Requests\Api\Rate;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CreateRateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'rate' => 'required|integer|min:1|max:5',
            'content' => 'nullable|string',
        ];
    }
    public function messages()
    {
        return [
            'rate.required' => 'Bắt buộc chọn số sao',
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
