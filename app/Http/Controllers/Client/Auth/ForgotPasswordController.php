<?php

namespace App\Http\Controllers\Client\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\SendMailForgotPassword;

class ForgotPasswordController extends Controller
{
    const PATH_VIEW = 'client.auth.';

    public function showFormForgot()
    {
        return view(self::PATH_VIEW . 'forgotpassword');
    }

    public function sendMailReset(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (empty($user)) {
            return back()->with('error', 'Tài khoản Email không tồn tại trong hệ thống.');
        }
        
        $token = base64_encode($user->email);

        $user->notify(new SendMailForgotPassword($token));

        return back()->with('success', "Chúng tôi đã gửi cho bạn liên kết đặt lại mật khẩu qua email.");
    }

    public function showFormResetPassword($token) {

        $email = base64_decode($token);

        return view(self::PATH_VIEW . "reset", compact('email'));
    }

    public function ResetUpdatePassword(Request $request) {
        // dd($request->toArray());
        $message = [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Sai định dạng email.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.max' => 'Mật khẩu không được vượt quá :max ký tự.'
        ];
        $validaton = $request->validate([
            "email" => 'required|email',
            'password' => ['required', 'string','confirmed', 'max:255']
        ], $message);

        $user = User::query()->where("email", $request->email)->first();

        if (empty($user)) {
            return back()->with('error', 'Tài khoản Email không tồn tại trong hệ thống.');
        }

        $user->update([
            "password" => bcrypt($request->password)
        ]);

        request()->session()->regenerate();

        return redirect()->route('client.login')->with('success', 'Thay đổi mật khẩu thành công, mời bạn đăng nhập!');
    }
}
