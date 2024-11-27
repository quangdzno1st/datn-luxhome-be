@extends('client.layouts.master')

@section('content')
<div class="" style="display:block; margin: 150px 0 50px 0">
	@if (session('msg'))
		<p style="color: #19b4ac; font-weight:bold; font-size:1rem; text-align:center">{{session('msg')}}</p>
	@endif
	@if (session('error'))
		<p style="color: red; font-weight:bold; font-size:1rem; text-align:center">{{session('error')}}</p>
	@endif
	
	<div class="lb-wrap" style="position:relative">
		<div class="lb-content">
			<form class="row" action="{{route('client.login')}}" method="POST">
				@csrf
				<h3 style="text-align:center">Đăng Nhập</h3>
				<div class="f-item full-width" style="float: unset">
					<label for="email">Địa Chỉ Email</label>
					<input type="text" id="email" name="email" value="{{old('email')}}"/>
					@error('email')
					<span style="color: red">{{$message}}</span>
					@enderror
				</div>
				<div class="f-item full-width" style="float: unset">
					<label for="password">Mật Khẩu</label>
					<input type="password" id="password" name="password" />
					@error('password')
					<span style="color: red">{{$message}}</span>
					@enderror
				</div>
				<div class="f-item checkbox full-width" style="float: unset">
					<input type="checkbox" id="remember_me" name="remember" />
					<label for="remember_me">Ghi nhớ mật khẩu</label>
				</div>
				<div class="f-item full-width" style="float: unset">
					<p><a href="{{route('client.password.reset')}}" title="Forgot password?">Quên Mật Khẩu?</a><br />
					Chưa có tài khoản? <a href="{{route('client.register')}}" title="Sign up">Đăng Ký.</a></p>
					<input type="submit" id="login" name="login" value="Đăng Nhập" class="gradient-button" />
				</div>
			</form>
		</div>
	</div>
</div>
@endsection