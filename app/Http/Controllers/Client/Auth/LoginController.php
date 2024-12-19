<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('orders.index');
        }
        return view('client.auth.login');
    }

    public function login(Request $request)
    {
        $message = [
            'email.required' => 'Vui lòng nhập số điện thoại',
            'password.required' => 'Vui lòng nhập mật khẩu'
        ];

        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], $message);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Lấy user hiện tại
            $user = Auth::user();
    
            // Kiểm tra xem trường is_active có bằng 1 hay không
            if ($user->is_active != 1) {
                // Nếu không kích hoạt, trả về thông báo lỗi
                Auth::logout(); // Đăng xuất người dùng nếu họ không được kích hoạt
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn chưa được kích hoạt.',
                ])->onlyInput('email');
            }
    
            // Nếu tất cả đều hợp lệ, tiến hành tạo lại session và chuyển hướng
            $request->session()->regenerate();
    
            return redirect()->route('home.index')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors([
            'email' => 'Tài khoản hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        return redirect()->route('client.login')->with('success', 'Đăng xuất tài khoản thành công!');
    }
}
