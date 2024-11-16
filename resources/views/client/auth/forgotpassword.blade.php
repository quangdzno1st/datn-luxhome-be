@extends('client.layouts.master')

@section('content')
<div class="" style="display:block; margin: 150px 0 50px 0">
	<div class="lb-wrap" style="position:relative">
		<div class="lb-content">
			<form class="row">
				<h3 style="text-align:center">Quên Mật Khẩu</h3>
				<div class="f-item full-width" style="float: unset">
					<label for="email">Đia Chỉ Email</label>
					<input type="email" id="email" name="email" />
				</div>
				<div class="f-item full-width" style="float: unset">
					<input type="submit" id="login" name="login" value="Gửi" class="gradient-button" />
				</div>
			</form>
		</div>
	</div>
</div>
@endsection