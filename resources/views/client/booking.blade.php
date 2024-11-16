@extends('client.layouts.master')

@section('content')
    <!--main-->
	<main class="main">		
		<div class="wrap">
			<!--breadcrumbs-->
			<nav class="breadcrumbs">
				<!--crumbs-->
				<ul>
					<li><a href="#" title="Home">Home</a></li>
					<li><a href="#" title="Hotels">Hotels</a></li>
					<li><a href="#" title="United Kingdom">United Kingdom</a></li>
					<li><a href="#" title="London">London</a></li>  
					<li>Best ipsum hotel</li>                                       
				</ul>
				<!--//crumbs-->
				
				<!--top right navigation-->
				<ul class="top-right-nav">
					<li><a href="#" title="Back to results">Back to results</a></li>
					<li><a href="#" title="Change search">Change search</a></li>
				</ul>
				<!--//top right navigation-->
			</nav>
			<!--//breadcrumbs-->
			
			<div class="row">
				<!--three-fourth content-->
				<div class="three-fourth">
					<form id="booking" method="post" action="https://www.themeenergy.com/themes/html/book-your-travel/booking-step2.html" class="static-content booking">
						<fieldset>
							<h2><span>01 </span>Traveller info</h2>
							<div class="row">
								<div class="f-item one-half">
									<label for="first_name">Họ Và Tên*</label>
									<input type="text" id="first_name" name="first_name" />
								</div>
								<div class="f-item one-half">
									<label for="last_name">Địa Chỉ Email*</label>
									<input type="text" id="last_name" name="last_name" />
								</div>
							</div>
							
							<div class="row">
								<div class="f-item one-half">
									<label for="email">Số Điện Thoại*</label>
									<input type="email" id="email" name="email" />
								</div>
								<div class="f-item one-half">
									<label for="confirm_email">Mã Phiếu Giảm Giá (Nếu Có)</label>
									<input type="text" id="confirm_email" name="confirm_email" />
								</div>
								<span class="info"></span>
							</div>
							
							
							<div class="row">
								<div class="f-item full-width">
									<label>Ghi chú: <span>(Không đảm bảo)</span></label>
									<textarea rows="10" cols="10"></textarea>
								</div>
								<span class="info"></span>
							</div>
							
							<div class="row">
								<div class="f-item full-width">
									<input type="submit" class="gradient-button" value="Proceed to next step" id="next-step" />
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
							<p>VAT (20%) included</p>
						</div>
					</article>
					<!--//Booking details-->
					
					<!--Need Help Booking?-->
					<article class="widget">
						<h4>Need Help Booking?</h4>
						<p>Call our customer services team on the number below to speak to one of our advisors who will help you with all of your holiday needs.</p>
						<p class="number">1- 555 - 555 - 555</p>
					</article>
					<!--//Need Help Booking?-->
				</aside>
				<!--//right sidebar-->
			</div>
			<!--//main content-->
		</div>
	</main>
	<!--//main-->
@endsection