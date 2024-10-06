<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

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
            'email' => 'required|email',
            'password' => 'nullable|min:6',
            'password_confirm'=>'same:password'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Địa chỉ email không đúng định dạng',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password_confirm.same'=>'Mật khẩu  không khớp với mật khẩu trước '
        ];
    }
}
