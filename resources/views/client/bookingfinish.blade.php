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
					<form id="booking" method="post" action="https://www.themeenergy.com/themes/html/book-your-travel/booking" class="static-content booking">
						<fieldset>
							<h2><span>03 </span>Confirmation</h2>
							<div class="text-wrap">
								<a href="#" class="gradient-button right print" title="Print booking" onclick="printpage()">Print booking</a>
								<p>Thank you. Your booking is now confirmed.</p>
							</div>
							
							<h3>Traveller info</h3>
							<div class="text-wrap">
								<div class="output">
									<p>Booking number:</p>
									<p>904054553</p>
									<p>Fist name: </p>
									<p>John</p>
									<p>Last name: </p>
									<p>Livingston</p>
									<p>E-mail address: </p>
									<p>mail@google.com</p>
									<p>Street Address and number:</p>
									<p>Some street name 55</p>
									<p>Town / City: </p>
									<p>Sunnytown</p>
									<p>ZIP code: </p>
									<p>9500 - 100</p>
									<p>Country:</p>
									<p>Neverland</p>
								</div>
							</div>
						
							<h3>Special requirements</h3>
							<div class="text-wrap">
								<p>I would like to book a twin room with a definite sea view please. Thank you and kind regards, John Livingston</p>
							</div>
							
							<h3>Payment</h3>
							<div class="text-wrap">
								<p>You have now confirmed and guaranteed your booking by credit card.All payments are to be made at the hotel during your stay, unless otherwise stated in the hotel policies or in the room conditions.Please note that your credit card may be pre-authorised prior to your arrival. </p>
								<p><strong class="dark">This hotel accepts the following forms of payment:</strong></p>
								<p>American Express, Visa, MasterCard</p>
							</div>
							
							<h3>Don’t forget</h3>
							<div class="text-wrap">
								<p>You can change or cancel your booking via our online self service tool myBookYourTravel:
								<a href="#">https://secure.bookyourtravel.com/myreservations.html?tmpl=profile/myreservations;bn=904054553;pincode=6525</a></p>
								<p><strong>We wish you a pleasant stay</strong><i>your BookYourTravel team</i></p>
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