@extends('client.layouts.master')

@section('content')
<div class="" style="display:block; margin: 150px 0 50px 0">
	<div class="lb-wrap" style="position:relative">
		<div class="lb-content">
			<form class="row">
				<h3 style="text-align:center">Đăng Nhập</h3>
				<div class="f-item full-width" style="float: unset">
					<label for="email">Đia Chỉ Email</label>
					<input type="email" id="email" name="email" />
				</div>
				<div class="f-item full-width" style="float: unset">
					<label for="password">Mật Khẩu</label>
					<input type="password" id="password" name="password" />
				</div>
				<div class="f-item checkbox full-width" style="float: unset">
					<input type="checkbox" id="remember_me" name="checkbox" />
					<label for="remember_me">Ghi nhớ mật khẩu</label>
				</div>
				<div class="f-item full-width" style="float: unset">
					<p><a href="#" title="Forgot password?">Quên Mật Khẩu?</a><br />
					Chưa có tài khoản? <a href="register.html" title="Sign up">Đăng Ký.</a></p>
					<input type="submit" id="login" name="login" value="Đăng Nhập" class="gradient-button" />
				</div>
			</form>
		</div>
	</div>
</div>
@endsection