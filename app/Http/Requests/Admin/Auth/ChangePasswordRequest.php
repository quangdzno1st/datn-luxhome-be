<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ChangePasswordRequest extends FormRequest
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
            'old_password' => 'required|min:6',
            'password' => 'required|confirmed|different:old_password|min:6',
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ',
            'old_password.min' => 'Mật khẩu cũ không đúng',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.confirmed' => 'Mật khẩu xác nhận không đúng',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự',
            'password.different' => 'Mật khẩu mới phải khác mật khẩu cũ',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $json = [
            'status' => false,
            'message' => $validator->errors()->first()
        ];
        $response = response( $json, 200 );
        throw (new ValidationException($validator, $response))->status(200);
    }
}
