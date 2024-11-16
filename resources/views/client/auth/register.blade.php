@extends('client.layouts.master')

@section('content')
<div class="" style="display:block; margin: 150px 0 50px 0">
	<div class="lb-wrap" style="position:relative">
		<div class="lb-content">
			<form class="row">
				<h3 style="text-align:center">Đăng Ký</h3>
				<div class="f-item full-width" style="float: unset">
					<label for="email">Tên Đăng Nhập</label>
					<input type="text" id="" name="" />
				</div>
				<div class="f-item full-width" style="float: unset">
					<label for="email">Đia Chỉ Email</label>
					<input type="email" id="email" name="email" />
				</div>
				<div class="f-item full-width" style="float: unset">
					<label for="">Số Điện Thoại</label>
					<input type="text" id="" name="" />
				</div>
				<div class="f-item full-width" style="float: unset">
					<label for="">Mật Khẩu</label>
					<input type="password" id="password" name="password" />
				</div>
				<div class="f-item full-width" style="float: unset">
					<label for="">Xác Nhận Mật Khẩu</label>
					<input type="password" id="" name="" />
				</div>
				<div class="f-item full-width" style="float: unset">
					<p>Đã có tài khoản? <a href="register.html" title="Sign up">Đăng Nhập.</a></p>
					<input type="submit" id="login" name="login" value="Đăng Ký" class="gradient-button" />
				</div>
			</form>
		</div>
	</div>
</div>
@endsection