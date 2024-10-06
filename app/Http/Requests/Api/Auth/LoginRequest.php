<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
             'phone' => ['required','regex:/^(03|05|07|08|09)(\d{8})$/'],
             'password' => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Vui lòng nhập số điện thoại đăng nhập',
            'phone.regex' => 'Số điện thoại không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $json = [
            'result' => false,
            'message' => $validator->errors()->first()
        ];
        $response = response( $json, 200 );
        throw (new ValidationException($validator, $response))->status(200);
    }
}
