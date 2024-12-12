<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('orders.index');
        }
        return view('client.auth.register');
    }

    public function register(Request $request)
    {
        $message = [
            'name.required' => 'Vui lòng nhập tên tài khoản.',
            'name.max' => 'Tên tài khoản không được vượt quá :max ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.regex' => 'Sai định dạng email.',
            'email.max' => 'Email không được vượt quá :max ký tự.',
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.max' => 'Mật khẩu không được vượt quá :max ký tự.',
            'password.min' => 'Mật khẩu tối thiểu phải 6 ký tự'
        ];
        $data = $request->validate([
            'name' => ['required', 'string','max:255'],
            'email' => ['required', 'string', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/','unique:users,email', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(0[3|5|7|8|9])[0-9]{8}$/'],
            'password' => ['required', 'string','confirmed', 'max:255', 'min:6']
        ], $message);

        $data['type'] = 1;

        $data['is_active'] = 1;

        $data['password'] = bcrypt($data['password']);

        $data['group_id'] = 1;

        $data['total_amount_ordered'] = 0;

        $newUser = User::query()->create($data);

        // Auth::login($newUser);

        $request->session()->regenerate();

        return redirect()->route('client.login')->with('success', 'Đăng ký tài khoản thành công!');
    }
}
