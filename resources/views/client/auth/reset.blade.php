@extends('client.layouts.master')

@section('content')
<div class="" style="display:block; margin: 150px 0 50px 0">
    @if (session('error'))
		<p style="color: red; font-weight:bold; font-size:1rem; text-align:center">{{session('error')}}</p>
	@endif
	@if (session('success'))
		<p style="color: #19b4ac; font-weight:bold; font-size:1rem; text-align:center">{{session('success')}}</p>
	@endif
	<div class="lb-wrap" style="position:relative">
		<div class="lb-content">
			<form class="row" action="{{route('client.reset.update')}}" method="POST">
                @csrf
				<h3 style="text-align:center">Đặt lại mật khẩu</h3>
				<div class="f-item full-width" style="float: unset">
					<label for="email">Địa Chỉ Email</label>
					<input type="email" id="email" name="email" value="{{$email}}" readonly/>
                    @error('email')
					<span style="color: red">{{$message}}</span>
					@enderror
				</div>
				<div class="f-item full-width" style="float: unset">
					<label for="password">Mật khẩu mới<span class="text-danger">*</span></label>
					<input type="password" id="password" name="password" />
                    @error('password')
					<span style="color: red">{{$message}}</span>
					@enderror
				</div>
				<div class="f-item full-width" style="float: unset">
					<label for="password_confirmation">Xác nhận mật khẩu<span class="text-danger">*</span></label>
					<input type="password" id="password_confirmation" name="password_confirmation" />
				</div>
				<div class="f-item full-width" style="float: unset">
					<input type="submit" id="login" name="login" value="Gửi" class="gradient-button" />
				</div>
			</form>
		</div>
	</div>
</div>
@endsection