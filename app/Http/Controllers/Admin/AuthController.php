<?php

namespace App\Http\Controllers\Admin;

use App\Constant\Enum\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\ChangePasswordRequest;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Http\Requests\Admin\Auth\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
//    public $adminRepository;
//
//    public function __construct(AdminRepository $adminRepository)
//    {
//        $this->adminRepository = $adminRepository;
//    }

    public function login()
    {
        return view('admin.auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'], // Kiểm tra email hợp lệ
            'password' => 'required|min:6'    // Mật khẩu yêu cầu tối thiểu 6 ký tự
        ], [
            'email.required' => 'Vui lòng nhập email', // Thông báo lỗi nếu email không được nhập
            'email.email' => 'Địa chỉ email không hợp lệ', // Thông báo lỗi nếu email không đúng định dạng
            'password.required' => 'Vui lòng nhập mật khẩu', // Thông báo lỗi nếu mật khẩu không được nhập
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự' // Thông báo lỗi nếu mật khẩu ít hơn 6 ký tự
        ]);


        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            if (auth()->user()->type != User::CUSTOMER && auth()->user()->is_active == 1 ) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.statistical.index'));
            }

            auth()->logout();
            return back()->withErrors([
                'error' => 'Bạn không có quyền truy cập vào khu vực này'
            ]);
        }

        return back()->withErrors([
            'error' => 'Tài khoản hoặc mật khẩu không đúng'
        ])->withInput();
    }


    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.auth.login');
    }


    public function profile(Request $request)
    {
        $admin = auth()->user();
        return view('admin.content.auth.profile', compact('admin'));
    }


    public function update(UpdateRequest $request)
    {

        $admin = $this->adminRepository->find(auth()->user()->id);
        $data = $request->only(['name', 'email', 'username_bell', 'password_bell']);
        if (!empty($request->password)) {
            $data['password'] = $request->password;
        }
//        else{
//            return redirect()->back()->withErrors(['error' => 'Mật khẩu hiện tại không chính xác']);
//        }
        $this->adminRepository->edit($admin, $data);
        return redirect()->back()->with('success', 'Mật khẩu đã được cập nhật thành công!');
    }


    public function changePassword(ChangePasswordRequest $request)
    {
        if (!(Hash::check($request->old_password, auth()->user()->password))) {
            return response(['status' => false, 'message' => 'Mật khẩu cũ không đúng']);
        }
        $data = [
            'password' => $request->password
        ];
        $this->adminRepository->edit(auth()->user(), $data);
        return response(['status' => true]);
    }
}
