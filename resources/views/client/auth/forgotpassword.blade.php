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
			<form action="{{route('client.password.reset')}}" class="row" method="POST">
				@csrf
				<h3 style="text-align:center">Quên Mật Khẩu</h3>
				<div class="f-item full-width" style="float: unset">
					<label for="email">Đia Chỉ Email<span class="text-danger">*</span></label>
					<input type="email" id="email" name="email" placeholder="Nhập địa chỉ Email"/>
				</div>
				<div class="f-item full-width" style="float: unset">
					<input type="submit" id="login" name="login" value="Gửi" class="gradient-button" />
				</div>
			</form>
		</div>
	</div>
</div>
@endsection