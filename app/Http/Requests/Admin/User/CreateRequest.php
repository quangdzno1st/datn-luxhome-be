<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'phone'=>'required|numeric|unique:users,phone',
            'password'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'Vui lòng nhập tên',
            'email.required'=>'Vui lòng nhập emai',
            'phone.required'=>'Vui lòng nhập số điện thoại',
            'phone.phone'=>'Số điện thoại phải là số',
            'phone.unique'=>'Số điện thoại đã được đăng ký',
            'email.unique'=>'Email đã được đăng ký',
            'emai.email'=>'Email không đúng định dạng',
            'password.required'=>'Vui lòng nhập mật khẩu',
        ];
    }
}
