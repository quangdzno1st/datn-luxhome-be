<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

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
//            'email' => 'required|email',
            'username' => 'required',
            'password' => 'required|min:6'
        ];
    }

    public function messages()
    {
        return [
//            'email.required' => 'Vui lòng nhập email',
//            'email.email' => 'Địa chỉ email không đúng định dạng',
            'username.required' => 'Vui lòng nhập tải khoản',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ];
    }
}
