<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\ChangePasswordRequest;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Http\Requests\Admin\Auth\UpdateRequest;
use App\Repositories\Admin\AdminRepository;
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
        if (auth('admin')->attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.room.index'));
        }

        return back()->withErrors([
            'username' => 'Tài khoản hoặc mật khẩu không đúng'
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
        $admin=auth()->user();
        return view('admin.content.auth.profile',compact('admin'));
    }



    public function update(UpdateRequest $request)
    {

        $admin= $this->adminRepository->find(auth()->user()->id);
        $data=$request->only(['name','email','username_bell','password_bell']);
        if(!empty($request->password )){
            $data['password'] = $request->password;
        }
//        else{
//            return redirect()->back()->withErrors(['error' => 'Mật khẩu hiện tại không chính xác']);
//        }
          $this->adminRepository->edit($admin,$data);
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
