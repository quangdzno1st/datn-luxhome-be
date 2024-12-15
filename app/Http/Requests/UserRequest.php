<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép mọi người thực hiện yêu cầu này
    }


    public function rules()
    {
        $userId = $this->route('user');
        $userType = $this->input('type');
        $userEdit = User::find($userId);

        $user = auth()->user();
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                'min:10',
                Rule::unique('users')->ignore($userId), // Kiểm tra tính duy nhất nhưng bỏ qua người dùng hiện tại
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId), // Kiểm tra tính duy nhất nhưng bỏ qua người dùng hiện tại
            ],
        ];

        if ($userType == User::CUSTOMER) {
            $rules['org_id'] = 'prohibited';
        }

        if (in_array($userType, [User::HOTELIER, User::STAFF])) {
            $rules['org_id'] = 'required';
        }

//dd($userEdit->id != $user->id && $user->user_type == User::ADMIN);
        if ($userEdit) {
            if ($userEdit->id != $user->id && $user->user_type == User::ADMIN && $userType != User::CUSTOMER) {
                abort(403);
            }
        }

        if (!$userId) {
            $rules['password'] = 'required|string|min:6';
        } else {
            $rules['password'] = 'nullable|string|min:6';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => "Vui lòng nhập tên!",
            'org_id.required' => "Vui lòng chọn khách sạn!",
            'name.max' => "Tên không được vượt quá 255 ký tự!",
            'phone.required' => "Vui lòng nhập số điện thoại!",
            'phone.regex' => "Số điện thoại không hợp lệ!",
            'phone.unique' => "Số điện thoại đã tồn tại!",
            'phone.min' => "Số điện thoại phải có ít nhất 10 chữ số!",
            'email.required' => "Vui lòng nhập email!",
            'email.email' => "Email không hợp lệ!",
            'email.unique' => "Email đã được sử dụng!",
            'password.required' => "Vui lòng nhập mật khẩu!",
            'password.min' => "Mật khẩu phải có ít nhất 8 ký tự!",
            'avatar.required' => "Vui lòng chọn hình ảnh!",
            'avatar.mimes' => "Hình ảnh phải có định dạng jpeg, png, jpg hoặc gif!",
            'org_id.prohibited' => "Bạn không được chọn khách sạn khi là người dùng!", // Thông báo lỗi tùy chỉnh
            'avatar.max' => "Kích thước hình ảnh không được vượt quá 2MB!"
        ];
    }
}
