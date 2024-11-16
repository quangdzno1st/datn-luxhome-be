@extends('client.layouts.master')

@section('content')
    	<!--slider-->
	<div class="slider">
		<ul id="hero-gallery" class="cS-hidden">
			<li data-thumb="{{asset('theme/client/images/uploads/slider7.jpg')}}">
				<img src="{{asset('theme/client/images/uploads/slider7.jpg')}}" alt="" />
			</li>
			<li data-thumb="{{asset('theme/client/images/uploads/slider3.jpg')}}">
				<img src="{{asset('theme/client/images/uploads/slider3.jpg')}}" alt="" />
			</li>
			<li data-thumb="{{asset('theme/client/images/uploads/slider2.jpg')}}">
				<img src="{{asset('theme/client/images/uploads/slider2.jpg')}}" alt="" />
			</li>
		</ul>
	</div>
	<!--//slider-->

	<!--search-->
	<div class="main-search">
		<div class="wrap">
			<form id="main-search" method="post"
				action="">
				<div class="row">

					<div class="four-fourth">
						<!--form hotel-->
						<div class="form row" id="form1">
							
							<!--column-->
							<div class="column one-third">
								<h5><span>01</span> Where?</h5>
								<div class="row">
									<div class="f-item full-width">
										<label for="destination1">Your destination</label>
										<input type="text" placeholder="City, region, district or specific hotel"
											id="destination1" name="destination" />
									</div>
								</div>
							</div>
							<!--//column-->

							<!--column-->
							<div class="column  one-third">
								<h5><span>02</span> When?</h5>
								<div class="row">
									<div class="f-item one-half datepicker">
										<label for="datepicker1">Check-in date</label>
										<div class="datepicker-wrap"><input type="text" placeholder="" id="datepicker1"
												name="datepicker1" /></div>
									</div>
									<div class="f-item one-half datepicker">
										<label for="datepicker2">Check-out date</label>
										<div class="datepicker-wrap"><input type="text" placeholder="" id="datepicker2"
												name="datepicker2" /></div>
									</div>
								</div>
							</div>
							<!--//column-->

							<!--column-->
							<div class="column one-third">
								<h5><span>03</span> Who?</h5>
								<div class="row">
									<div class="f-item one-third spinner">
										<label for="spinner1">Rooms</label>
										<input type="text" placeholder="" id="spinner1" name="spinner1" />
									</div>
									<div class="f-item one-third spinner">
										<label for="spinner2">Adults</label>
										<input type="text" placeholder="" id="spinner2" name="spinner1" />
									</div>
									<div class="f-item one-third spinner">
										<label for="spinner3">Children</label>
										<input type="text" placeholder="" id="spinner3" name="spinner1" />
									</div>
								</div>
							</div>
							<!--//column-->
						</div>
						<!--//form hotel-->
					</div>
					<input type="submit" value="Proceed to results" class="gradient-button search-submit"
						id="search-submit" />
				</div>
			</form>
		</div>
	</div>
	<!--//search-->

		<!--main-->
		<main class="main">		
			<div class="wrap">
				<div class="row">
			
					<div class="full-width">
						<header class="s-title">
							<h2>Most popular Hotels</h2>
						</header>
						
						<div class="deals">
							<div class="row">
								<!--deal-->
								<article class="one-fourth">
									<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel1.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<h3>Best ipsum hotel 
											<span class="stars">
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
											</span>
										</h3>
										<span class="address">London  •  <a href="#">Show on map</a></span>
										<span class="rating"> 8 /10</span>
										<span class="price">Price per room per night from  <em>$ 50</em> </span>
										<div class="description">
											<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
										</div>
										<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
									</div>
								</article>
								<!--//deal-->
								
								<!--deal-->
								<article class="one-fourth">
									<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel2.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<h3>Tropicana hotel
											<span class="stars">
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
											</span>
										</h3>
										<span class="address">London  •  <a href="#">Show on map</a></span>
										<span class="rating"> 9 /10</span>
										<span class="price">Price per room per night from  <em>$ 80</em> </span>
										<div class="description">
											<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
										</div>
										<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
									</div>
								</article>
								<!--//deal-->
								
								<!--deal-->
								<article class="one-fourth">
									<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel3.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<h3>Spa Resort hotel
											<span class="stars">
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
											</span>
										</h3>
										<span class="address">London  •  <a href="#">Show on map</a></span>
										<span class="rating"> 8 /10</span>
										<span class="price">Price per room per night from  <em>$ 70</em> </span>
										<div class="description">
											<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
										</div>
										<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
									</div>
								</article>
								<!--//deal-->
	
								<!--deal-->
								<article class="one-fourth">
									<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel4.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<h3>Best ipsum hotel 
											<span class="stars">
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
											</span>
										</h3>
										<span class="address">London  •  <a href="#">Show on map</a></span>
										<span class="rating"> 8 /10</span>
										<span class="price">Price per room per night from  <em>$ 50</em> </span>
										<div class="description">
											<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
										</div>
										<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
									</div>
								</article>
								<!--//deal-->
								
								<!--deal-->
								<article class="one-fourth">
									<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel5.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<h3>Spa Resort hotel
											<span class="stars">
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
											</span>
										</h3>
										<span class="address">London  •  <a href="#">Show on map</a></span>
										<span class="rating"> 8 /10</span>
										<span class="price">Price per room per night from  <em>$ 70</em> </span>
										<div class="description">
											<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
										</div>
										<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
									</div>
								</article>
								<!--//deal-->
								
								<!--deal-->
								<article class="one-fourth">
									<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel6.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<h3>Tropicana hotel
											<span class="stars">
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
											</span>
										</h3>
										<span class="address">London  •  <a href="#">Show on map</a></span>
										<span class="rating"> 9 /10</span>
										<span class="price">Price per room per night from  <em>$ 80</em> </span>
										<div class="description">
											<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
										</div>
										<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
									</div>
								</article>
								<!--//deal-->
								
								<!--deal-->
								<article class="one-fourth">
									<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel1.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<h3>Best ipsum hotel 
											<span class="stars">
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
											</span>
										</h3>
										<span class="address">London  •  <a href="#">Show on map</a></span>
										<span class="rating"> 8 /10</span>
										<span class="price">Price per room per night from  <em>$ 50</em> </span>
										<div class="description">
											<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
										</div>
										<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
									</div>
								</article>
								<!--//deal-->
								
								<!--deal-->
								<article class="one-fourth">
									<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel2.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<h3>Tropicana hotel
											<span class="stars">
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
											</span>
										</h3>
										<span class="address">London  •  <a href="#">Show on map</a></span>
										<span class="rating"> 9 /10</span>
										<span class="price">Price per room per night from  <em>$ 80</em> </span>
										<div class="description">
											<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
										</div>
										<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
									</div>
								</article>
								<!--//deal-->
								
								<!--deal-->
								<article class="one-fourth">
									<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel3.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<h3>Spa Resort hotel
											<span class="stars">
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
											</span>
										</h3>
										<span class="address">London  •  <a href="#">Show on map</a></span>
										<span class="rating"> 8 /10</span>
										<span class="price">Price per room per night from  <em>$ 70</em> </span>
										<div class="description">
											<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
										</div>
										<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
									</div>
								</article>
								<!--//deal-->
								
								<!--deal-->
								<article class="one-fourth">
									<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel4.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<h3>Best ipsum hotel 
											<span class="stars">
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
											</span>
										</h3>
										<span class="address">London  •  <a href="#">Show on map</a></span>
										<span class="rating"> 8 /10</span>
										<span class="price">Price per room per night from  <em>$ 50</em> </span>
										<div class="description">
											<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
										</div>
										<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
									</div>
								</article>
								<!--//deal-->
								
								<!--deal-->
								<article class="one-fourth">
									<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel5.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<h3>Spa Resort hotel
											<span class="stars">
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
											</span>
										</h3>
										<span class="address">London  •  <a href="#">Show on map</a></span>
										<span class="rating"> 8 /10</span>
										<span class="price">Price per room per night from  <em>$ 50</em> </span>
										<div class="description">
											<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
										</div>
										<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
									</div>
								</article>
								<!--//deal-->
								
								<!--deal-->
								<article class="one-fourth">
									<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel6.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<h3>Tropicana hotel
											<span class="stars">
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
												<i class="material-icons">&#xE838;</i>
											</span>
										</h3>
										<span class="address">London  •  <a href="#">Show on map</a></span>
										<span class="rating"> 9 /10</span>
										<span class="price">Price per room per night from  <em>$ 80</em> </span>
										<div class="description">
											<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
										</div>
										<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
									</div>
								</article>
								<!--//deal-->
							</div>
						</div>
						<!--//deals-->
						
						<header class="s-title">
							<h2>Top destinations around the world</h2>
						</header>
						
						<!--top destinations-->
						<div class="destinations">
							<div class="row">
								<!--column-->
								<article class="one-fourth">
									<figure><a href="#" title=""><img src="{{asset('theme/client/images/uploads/paris.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<a href="#" title="View all" class="gradient-button">View all</a>
										<h4>Paris</h4>
										<span class="count">1529 Hotels</span>
										<div class="ribbon">
											<div class="half hotel">
												<a href="hotels.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 70</span>
												</a>
											</div>
											<div class="half flight">
												<a href="flights.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 150</span>
												</a>
											</div>
										</div>
									</div>
								</article>
								<!--//column-->
								
								<!--column-->
								<article class="one-fourth">
									<figure><a href="#" title=""><img src="{{asset('theme/client/images/uploads/amsterdam.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<a href="#" title="View all" class="gradient-button">View all</a>
										<h4>Amsterdam</h4>
										<span class="count">929 Hotels</span>
										<div class="ribbon">
											<div class="half hotel">
												<a href="hotels.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 70</span>
												</a>
											</div>
											<div class="half flight">
												<a href="flights.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 150</span>
												</a>
											</div>
										</div>
									</div>
								</article>
								<!--//column-->
								
								<!--column-->
								<article class="one-fourth">
									<figure><a href="#" title=""><img src="{{asset('theme/client/images/uploads/saint-petersburg.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<a href="#" title="View all" class="gradient-button">View all</a>
										<h4>St Petersburg</h4>
										<span class="count">658 Hotels</span>
										<div class="ribbon">
											<div class="half hotel">
												<a href="hotels.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 70</span>
												</a>
											</div>
											<div class="half flight">
												<a href="flights.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 150</span>
												</a>
											</div>
										</div>
									</div>
								</article>
								<!--//column-->
								
								<!--column-->
								<article class="one-fourth">
									<figure><a href="#" title=""><img src="{{asset('theme/client/images/uploads/prague.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<a href="#" title="View all" class="gradient-button">View all</a>
										<h4>Prague</h4>
										<span class="count">829 Hotels</span>
										<div class="ribbon">
											<div class="half hotel">
												<a href="hotels.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 70</span>
												</a>
											</div>
											<div class="half flight">
												<a href="flights.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 150</span>
												</a>
											</div>
										</div>
									</div>
								</article>
								<!--//column-->
								<!--column-->
								<article class="one-fourth">
									<figure><a href="#" title=""><img src="{{asset('theme/client/images/uploads/prague.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<a href="#" title="View all" class="gradient-button">View all</a>
										<h4>Prague</h4>
										<span class="count">829 Hotels</span>
										<div class="ribbon">
											<div class="half hotel">
												<a href="hotels.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 70</span>
												</a>
											</div>
											<div class="half flight">
												<a href="flights.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 150</span>
												</a>
											</div>
										</div>
									</div>
								</article>
								<!--//column-->
								
								<!--column-->
								<article class="one-fourth promo">
									<div class="ribbon-small">- 20%</div>
									<figure><a href="#" title=""><img src="{{asset('theme/client/images/uploads/paris.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<a href="#" title="View all" class="gradient-button">View all</a>
										<h4>Paris</h4>
										<span class="count">1529 Hotels</span>
										<div class="ribbon">
											<div class="half hotel">
												<a href="hotels.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 70</span>
												</a>
											</div>
											<div class="half flight">
												<a href="flights.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 150</span>
												</a>
											</div>
										</div>
									</div>
								</article>
								<!--//column-->
								
								<!--column-->
								<article class="one-fourth">
									<figure><a href="#" title=""><img src="{{asset('theme/client/images/uploads/amsterdam.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<a href="#" title="View all" class="gradient-button">View all</a>
										<h4>Amsterdam</h4>
										<span class="count">929 Hotels</span>
										<div class="ribbon">
											<div class="half hotel">
												<a href="hotels.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 70</span>
												</a>
											</div>
											<div class="half flight">
												<a href="flights.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 150</span>
												</a>
											</div>
										</div>
									</div>
								</article>
								<!--//column-->
								
								<!--column-->
								<article class="one-fourth">
									<figure><a href="#" title=""><img src="{{asset('theme/client/images/uploads/saint-petersburg.jpg')}}" alt="" /></a></figure>
									<div class="details">
										<a href="#" title="View all" class="gradient-button">View all</a>
										<h4>St Petersburg</h4>
										<span class="count">658 Hotels</span>
										<div class="ribbon">
											<div class="half hotel">
												<a href="hotels.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 70</span>
												</a>
											</div>
											<div class="half flight">
												<a href="flights.html" title="View all">
													<span class="small">from</span>
													<span class="price">&#36; 150</span>
												</a>
											</div>
										</div>
									</div>
								</article>
								<!--//column-->
							</div>
						</div>
						<!--//top destinations-->
					</div>
				</div>
			</div>
		</main>
		<!--//main-->
@endsection

@section('style-libs')
<link rel="stylesheet" href="{{asset('theme/client/css/lightslider.min.css')}}" />
@endsection

@section('script-libs')
<script type="text/javascript" src="{{asset('theme/client/js/lightslider.min.js')}}"></script>
<script type="text/javascript">
	(function ($) {
		$(document).ready(function () {
			$('.form').hide();
			$('#form1').show();
			$('.f-item:nth-child(1)').addClass('active');
			$('.f-item:nth-child(1) span').addClass('checked');

			$('#hero-gallery').lightSlider({
				gallery: true,
				item: 1,
				pager: false,
				gallery: false,
				slideMargin: 0,
				speed: 2000,
				pause: 6000,
				mode: 'fade',
				auto: true,
				loop: true,
				onSliderLoad: function () {
					$('#hero-gallery').removeClass('cS-hidden');
				}
			});
		});
	})(jQuery);
</script>
@endsection