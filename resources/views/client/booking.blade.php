@extends('client.layouts.master')

@section('content')
    <!--main-->
	<main class="main">		
		<div class="wrap">
			<!--breadcrumbs-->
			<nav class="breadcrumbs">
				<!--crumbs-->
				<ul>
					<li><a href="#" title="Home">Trang chủ</a></li>
					<li><a href="#" title="Hotels">Khách sạn</a></li>
					<li>Thông tin hóa đơn</li>
				</ul>
				<!--//crumbs-->
			</nav>
			<!--//breadcrumbs-->
			<div class="row">
				<!--three-fourth content-->
				<div class="three-fourth">
					<form id="booking" method="post" action="{{ route('orders.store') }}" class="static-content booking">
						@csrf
						<fieldset>

							@if(session('error'))
								<div class="alert alert-danger">
									<ul>
										<li>{{ session('error') }}</li>
									</ul>
								</div>
							@endif

							<h2>Thông tin hóa đơn</h2>
							<div class="row">
								<div class="f-item one-half">
									<label for="first_name">Họ Và Tên*</label>
									<input type="text" id="first_name" name="user_name" value="{{ old('user_name') }}" />

									@error('user_name')
									<div class="text-danger" style="color:red">{{ $message }}</div>
									@enderror

								</div>
								<div class="f-item one-half">
									<label for="last_name">Địa Chỉ Email*</label>
									<input type="text" id="last_name" name="user_email" value="{{ old('user_email') }}" />

									@error('user_email')
									<div class="text-danger" style="color:red">{{ $message }}</div>
									@enderror
								</div>
							</div>
							
							<div class="row">
								<div class="f-item one-half">
									<label for="email">Số Điện Thoại*</label>
									<input type="number" id="email" name="user_phone_number" value="{{ old('user_phone_number') }}"/>

									@error('user_phone_number')
									<div class="text-danger" style="color:red">{{ $message }}</div>
									@enderror
								</div>
								<div class="f-item one-half">
									<label for="confirm_email">Mã Phiếu Giảm Giá (Nếu Có)</label>
										<select class="select" name="voucher_id">
											@foreach($vouchers as $voucher)
												<option value="{{ $voucher['id'] }}" >
													<div>Giảm giá tối đa </div>
												</option>
											@endforeach
										</select>
								</div>
								<span class="info"></span>
							</div>
							
							<div class="row">
								<div class="f-item full-width">
									<label>Ghi chú: </label>
									<textarea rows="10" cols="10" name="note" >{{ old('note') }}</textarea>
								</div>
								<span class="info"></span>
							</div>
							
							<div class="row">
								<div class="f-item full-width">
									<input type="submit" class="gradient-button" value="Thanh toán hóa đơn" id="next-step" />
								</div>
							</div>
						</fieldset>
					</form>
				</div>
				<!--//three-fourth content-->
				
				<!--right sidebar-->
				<aside class="one-fourth right-sidebar">
					<!--Booking details-->
					<article class="hotel-details booking-details">
						<h1>Best ipsum hotel 
							<span class="stars">
								<i class="material-icons">&#xE838;</i>
								<i class="material-icons">&#xE838;</i>
								<i class="material-icons">&#xE838;</i>
							</span>
						</h1>
						<span class="address">Marylebone, London</span>
						<span class="rating"> 8 /10</span>
						<dl class="booking-info">
							<dt>Rooms</dt>
							<dd>Standard twin room</dd>
							<dt>Room Description</dt>
							<dd>Room only</dd>
							<dt>Check-in Date</dt>
							<dd>14-11-12</dd>
							<dt>Check-out Date</dt>
							<dd>15-11-12</dd>
							<dt>Room(s)</dt>
							<dd>1 night, 1 room, max. 2 people. </dd>
						</dl>
						<div class="price">
							<p class="total">Total Price:  $ 55,00</p>
						</div>
					</article>
					<!--//Booking details-->
				</aside>
				<!--//right sidebar-->
			</div>
			<!--//main content-->
		</div>
	</main>
	<!--//main-->
@endsection