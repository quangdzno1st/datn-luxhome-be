@extends('client.layouts.master')

@section('content')
<div class="" style="display:block; margin: 150px 0 50px 0">
	<div class="lb-wrap" style="position:relative">
		<div class="lb-content">
			<form class="row" action="{{route('client.register')}}" method="POST">
				@csrf
				<h3 style="text-align:center">Đăng Ký</h3>
				<div class="f-item full-width" style="float: unset">
					<label for="name">Tên Tài Khoản<span class="text-danger">*</span></label>
					<input type="text" id="name" name="name" value="{{old('name')}}"/>
					@error('name')
					<span style="color: red">{{$message}}</span>
					@enderror
				</div>
				<div class="f-item full-width" style="float: unset">
					<label for="email">Đia Chỉ Email<span class="text-danger">*</span></label>
					<input type="email" id="email" name="email" value="{{old('email')}}"/>
					@error('email')
					<span style="color: red">{{$message}}</span>
					@enderror
				</div>
				<div class="f-item full-width" style="float: unset">
					<label for="phone">Số Điện Thoại<span class="text-danger">*</span></label>
					<input type="text" id="phone" name="phone" value="{{old('phone')}}"/>
					@error('phone')
					<span style="color: red">{{$message}}</span>
					@enderror
				</div>
				<div class="f-item full-width" style="float: unset">
					<label for="password">Mật Khẩu<span class="text-danger">*</span></label>
					<input type="password" id="password" name="password" />
					@error('password')
					<span style="color: red">{{$message}}</span>
					@enderror
				</div>
				<div class="f-item full-width" style="float: unset">
					<label for="password_confirmation">Xác Nhận Mật Khẩu<span class="text-danger">*</span></label>
					<input type="password" id="password_confirmation" name="password_confirmation" />
				</div>
				<div class="f-item full-width" style="float: unset">
					<p>Đã có tài khoản? <a href="{{route('client.login')}}" title="Sign up">Đăng Nhập.</a></p>
					<input type="submit" id="login" name="login" value="Đăng Ký" class="gradient-button" />
				</div>
			</form>
		</div>
	</div>
</div>
@endsection