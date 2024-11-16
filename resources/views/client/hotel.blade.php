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
					<li>Search results</li>
				</ul>
				<!--//crumbs-->

				<!--top right navigation-->
				<ul class="top-right-nav">
					<li><a href="search_results.html" title="Back to results">Back to results</a></li>
					<li><a href="#" title="Change search">Change search</a></li>
				</ul>
				<!--//top right navigation-->
			</nav>
			<!--//breadcrumbs-->

			<div class="row">
				<!--hotel three-fourth content-->
				<section class="three-fourth">
					<!--gallery-->
					<div class="gallery">
						<ul id="image-gallery" class="cS-hidden">
							<li data-thumb="{{asset('theme/client/images/uploads/hotel4.jpg')}}">
								<img src="{{asset('theme/client/images/uploads/hotel4.jpg')}}" alt="" />
							</li>
							<li data-thumb="{{asset('theme/client/images/uploads/hotel1.jpg')}}">
								<img src="{{asset('theme/client/images/uploads/hotel1.jpg')}}" alt="" />
							</li>
							<li data-thumb="{{asset('theme/client/images/uploads/hotel2.jpg')}}">
								<img src="{{asset('theme/client/images/uploads/hotel2.jpg')}}" alt="" />
							</li>
							<li data-thumb="{{asset('theme/client/images/uploads/hotel5.jpg')}}">
								<img src="{{asset('theme/client/images/uploads/hotel5.jpg')}}" alt="" />
							</li>
							<li data-thumb="{{asset('theme/client/images/uploads/hotel3.jpg')}}">
								<img src="{{asset('theme/client/images/uploads/hotel3.jpg')}}" alt="" />
							</li>
							<li data-thumb="{{asset('theme/client/images/uploads/hotel6.jpg')}}">
								<img src="{{asset('theme/client/images/uploads/hotel6.jpg')}}" alt="" />
							</li>
						</ul>
					</div>
					<!--//gallery-->

					<!--inner navigation-->
					<nav class="inner-nav">
						<ul>
							<li class="availability"><a href="#availability" title="Availability">Availability</a></li>
							<li class="description"><a href="#description" title="Description">Description</a></li>
							<li class="facilities"><a href="#facilities" title="Facilities">Facilities</a></li>
							<li class="location"><a href="#location" title="Location">Location</a></li>
							<li class="reviews"><a href="#reviews" title="Reviews">Reviews</a></li>
							<li class="things-to-do"><a href="#things-to-do" title="Things to do">Things to do</a></li>
						</ul>
					</nav>
					<!--//inner navigation-->

					<!--availability-->
					<section id="availability" class="tab-content">
						<article>
							<h2>Phòng trống</h2>
							<div class="text-wrap" style="display: flex">
								<div>
									<input type="date">
								</div>
								<div>
									<input type="date">
								</div>
								<div>
									<select name="" id="">
										<option value="">0</option>
										<option value="">1 người</option>
										<option value="">2 người</option>
										<option value="">3 người</option>
										<option value="">4 người</option>
										<option value="">5 người</option>
									</select>
								</div>
								<a href="#" class="gradient-button right" title="Change dates">Thay đổi tìm kiếm</a>
							</div>

							<h2>Room types</h2>
							<ul class="room-types">
								<!--room-->
								<li>
									<figure class="left" id="gallery1">
										<a href="{{asset('theme/client/images/uploads/room1.jpg')}}" data-sub-html="<p>Superior Double Room</p>">
											<img src="{{asset('theme/client/images/uploads/room1.jpg')}}" alt="" />
											<span class="image-overlay"></span>
										</a>
										<a href="{{asset('theme/client/images/uploads/room2.jpg')}}" data-sub-html="<p>Superior Double Room</p>">
											<img src="{{asset('theme/client/images/uploads/room2.jpg')}}" alt="" />
										</a>
									</figure>
									<div class="meta">
										<h3>Superior Double Room</h3>
										<p>Prices are per room20 % VAT Included in price</p>
										<p>Non-refundableFull English breakfast $ 24.80 </p>
										<div class="" style="margin-bottom: 10px">
											Chọn phòng:
											<select name="" id="">
												<option value="">0</option>
												<option value="">1 (1.000.000VNĐ)</option>
												<option value="">2 (2.000.000VNĐ)</option>
												<option value="">3 (3.000.000VNĐ)</option>
												<option value="">4 (4.000.000VNĐ)</option>
												<option value="">5 (5.000.000VNĐ)</option>
											</select>
										</div>
										<a href="javascript:void(0)" title="more info" class="more-info">+ Xem thêm</a>
									</div>
									<div class="room-information">
										<div class="row">
											<span class="first">Max:</span>
											<span class="second"><i class="material-icons">&#xE7FD;</i><i
													class="material-icons">&#xE7FD;</i></span>
										</div>
										<div class="row">
											<span class="first">Price:</span>
											<span class="second">$ 55</span>
										</div>
										<div class="row">
											<span class="first">Rooms:</span>
											<span class="second">01</span>
										</div>
										<a href="booking-step1.html" class="gradient-button" title="Book">Book now</a>
									</div>
									<div class="more-information">
										<p>Stylish and individually designed room featuring a satellite TV, mini bar and
											a 24-hour room service menu.</p>
										<p><strong>Room Facilities:</strong> Safety Deposit Box, Air Conditioning, Desk,
											Ironing Facilities, Seating Area, Heating, Shower, Bath, Hairdryer, Toilet,
											Bathroom, Pay-per-view Channels, TV, Telephone</p>
										<p><strong>Bed Size(s):</strong> 1 Double </p>
										<p><strong>Room Size:</strong> 16 square metres</p>
										
									</div>
								</li>
								<!--//room-->

								<!--room-->
								<li>
									<figure class="left" id="gallery2">
										<a href="{{asset('theme/client/images/uploads/room2.jpg')}}" data-sub-html="<p>Deluxe Single Room</p>">
											<img src="{{asset('theme/client/images/uploads/room2.jpg')}}" alt="" />
											<span class="image-overlay"></span>
										</a>
										<a href="{{asset('theme/client/images/uploads/room1.jpg')}}" data-sub-html="<p>Deluxe Single Room</p>">
											<img src="{{asset('theme/client/images/uploads/room1.jpg')}}" alt="" />
										</a>
									</figure>
									<div class="meta">
										<h3>Deluxe Single Room</h3>
										<p>Prices are per room20 % VAT Included in price</p>
										<p>Non-refundableFull English breakfast $ 24.80 </p>
										<a href="javascript:void(0)" title="more info" class="more-info">+ more info</a>
									</div>
									<div class="room-information">
										<div class="row">
											<span class="first">Max:</span>
											<span class="second"><i class="material-icons">&#xE7FD;</i></span>
										</div>
										<div class="row">
											<span class="first">Price:</span>
											<span class="second">$ 55</span>
										</div>
										<div class="row">
											<span class="first">Rooms:</span>
											<span class="second">01</span>
										</div>
										<a href="booking-step1.html" class="gradient-button" title="Book">Book now</a>
									</div>
									<div class="more-information">
										<p>Stylish and individually designed room featuring a satellite TV, mini bar and
											a 24-hour room service menu.</p>
										<p><strong>Room Facilities:</strong> Safety Deposit Box, Air Conditioning, Desk,
											Ironing Facilities, Seating Area, Heating, Shower, Bath, Hairdryer, Toilet,
											Bathroom, Pay-per-view Channels, TV, Telephone</p>
										<p><strong>Bed Size(s):</strong> 1 Double </p>
										<p><strong>Room Size:</strong> 16 square metres</p>
									</div>
								</li>
								<!--//room-->

								<!--room-->
								<li>
									<figure class="left" id="gallery3">
										<a href="{{asset('theme/client/images/uploads/room1.jpg')}}" data-sub-html="<p>Standard Family Room</p>">
											<img src="{{asset('theme/client/images/uploads/room1.jpg')}}" alt="" />
											<span class="image-overlay"></span>
										</a>
										<a href="{{asset('theme/client/images/uploads/room2.jpg')}}" data-sub-html="<p>Standard Family Room</p>">
											<img src="{{asset('theme/client/images/uploads/room2.jpg')}}" alt="" />
										</a>
									</figure>
									<div class="meta">
										<h3>Standard Family Room</h3>
										<p>Prices are per room20 % VAT Included in price</p>
										<p>Non-refundableFull English breakfast $ 24.80 </p>
										<a href="javascript:void(0)" title="more info" class="more-info">+ more info</a>
									</div>
									<div class="room-information">
										<div class="row">
											<span class="first">Max:</span>
											<span class="second"><i class="material-icons">&#xE7FD;</i><i
													class="material-icons">&#xE7FD;</i><i
													class="material-icons">&#xE7FD;</i></span>
										</div>
										<div class="row">
											<span class="first">Price:</span>
											<span class="second">$ 55</span>
										</div>
										<div class="row">
											<span class="first">Rooms:</span>
											<span class="second">01</span>
										</div>
										<a href="booking-step1.html" class="gradient-button" title="Book">Book now</a>
									</div>
									<div class="more-information">
										<p>Stylish and individually designed room featuring a satellite TV, mini bar and
											a 24-hour room service menu.</p>
										<p><strong>Room Facilities:</strong> Safety Deposit Box, Air Conditioning, Desk,
											Ironing Facilities, Seating Area, Heating, Shower, Bath, Hairdryer, Toilet,
											Bathroom, Pay-per-view Channels, TV, Telephone</p>
										<p><strong>Bed Size(s):</strong> 1 Double </p>
										<p><strong>Room Size:</strong> 16 square metres</p>
									</div>
								</li>
								<!--//room-->

								<!--room-->
								<li>
									<figure class="left" id="gallery4">
										<a href="{{asset('theme/client/images/uploads/room2.jpg')}}" data-sub-html="<p>Superior Double Room</p>">
											<img src="{{asset('theme/client/images/uploads/room2.jpg')}}" alt="" />
											<span class="image-overlay"></span>
										</a>
										<a href="{{asset('theme/client/images/uploads/room1.jpg')}}" data-sub-html="<p>Superior Double Room</p>">
											<img src="{{asset('theme/client/images/uploads/room1.jpg')}}" alt="" />
										</a>
									</figure>
									<div class="meta">
										<h3>Superior Double Room</h3>
										<p>Prices are per room20 % VAT Included in price</p>
										<p>Non-refundableFull English breakfast $ 24.80 </p>
										<a href="javascript:void(0)" title="more info" class="more-info">+ more info</a>
									</div>
									<div class="room-information">
										<div class="row">
											<span class="first">Max:</span>
											<span class="second"><i class="material-icons">&#xE7FD;</i><i
													class="material-icons">&#xE7FD;</i></span>
										</div>
										<div class="row">
											<span class="first">Price:</span>
											<span class="second">$ 55</span>
										</div>
										<div class="row">
											<span class="first">Rooms:</span>
											<span class="second">01</span>
										</div>
										<a href="booking-step1.html" class="gradient-button" title="Book">Book now</a>
									</div>
									<div class="more-information">
										<p>Stylish and individually designed room featuring a satellite TV, mini bar and
											a 24-hour room service menu.</p>
										<p><strong>Room Facilities:</strong> Safety Deposit Box, Air Conditioning, Desk,
											Ironing Facilities, Seating Area, Heating, Shower, Bath, Hairdryer, Toilet,
											Bathroom, Pay-per-view Channels, TV, Telephone</p>
										<p><strong>Bed Size(s):</strong> 1 Double </p>
										<p><strong>Room Size:</strong> 16 square metres</p>
									</div>
								</li>
								<!--//room-->
							</ul>
						</article>
					</section>
					<!--//availability-->

					<!--description-->
					<section id="description" class="tab-content">
						<article>
							<h2>General</h2>
							<div class="text-wrap">
								<p>The Best Ipsum hotel features over 1,000 luxuriously appointed, individually styled
									rooms, suites and apartments, each containing unique works of art. Accommodation at
									the hotel includes air conditioning in all the rooms, private bathroom with heated
									mirrors, hair dryer, power shower, BT Openzone Wi-Fi, coffee and tea making
									facilities, complimentary toiletries, Egyptian linen, flat Screen LCD TV with free
									view, work desk, 24 hour room service.</p>
							</div>

							<h2>Check-in</h2>
							<div class="text-wrap">
								<p>From 15:00 hours </p>
							</div>

							<h2>Check-out</h2>
							<div class="text-wrap">
								<p>Untill 12:00 hours </p>
							</div>

							<h2>Cancellation / Prepayment</h2>
							<div class="text-wrap">
								<p>Cancellation and prepayment policies vary according to room type. Please check the <a
										href="#">room conditions</a> when selecting your room. </p>
							</div>

							<h2>Children and extra beds</h2>
							<div class="text-wrap">
								<p><strong>Free!</strong> All children under 8 years stay free of charge when using
									existing beds.<strong>Free!</strong> All children under 2 years stay free of charge
									for children’s cots/cribs.All older children or adults are charged USD 40 per person
									per night for extra beds.The maximum number of extra beds/children’s cots permitted
									in a room is 1.Any type of extra bed or child’s cot/crib is upon request and needs
									to be confirmed by management.Supplements are not calculated automatically in the
									total costs and will have to be paid for separately when checking out.</p>
							</div>

							<h2>Pets</h2>
							<div class="text-wrap">
								<p>Pets are allowed. Charges may be applicable.</p>
							</div>

							<h2>Accepted credit cards</h2>
							<div class="text-wrap">
								<p>American Express, Visa, Euro/Mastercard, Diners ClubThe hotel reserves the right to
									pre-authorise credit cards prior to arrival.</p>
							</div>
						</article>
					</section>
					<!--//description-->

					<!--facilities-->
					<section id="facilities" class="tab-content">
						<article>
							<h2>Facilities</h2>
							<div class="text-wrap">
								<ul class="three-col">
									<li>Kitchenette</li>
									<li>Ironing board</li>
									<li>Catering services</li>
									<li>Beachfront</li>
									<li>Hotspots</li>
									<li>Exhibition/convention floor</li>
									<li>Restaurant</li>
									<li>Room service - full menu</li>
									<li>Courtyard</li>
									<li>Lounges/bars</li>
									<li>Laundry/Valet service</li>
									<li>Airport Shuttle Service</li>
									<li>Complimentary breakfast</li>
									<li>Valet cleaning</li>
									<li>Car hire</li>
								</ul>
							</div>

							<h2>Activities</h2>
							<div class="text-wrap">
								<p>Tennis court, Sauna, Fitness centre, Massage </p>
							</div>

							<h2>Internet</h2>
							<div class="text-wrap">
								<p><strong>Free!</strong> WiFi is available in all areas and is free of charge. </p>
							</div>

							<h2>Parking</h2>
							<div class="text-wrap">
								<p>Private parking is possible at a location nearby (reservation is not needed) and
									costs USD 28.80 per day.</p>
							</div>
						</article>
					</section>
					<!--//facilities-->

					<!--location-->
					<section id="location" class="tab-content">
						<article>
							<!--map-->
							<div class="gmap" id="map_canvas"></div>
							<!--//map-->
						</article>
					</section>
					<!--//location-->

					<!--reviews-->
					<section id="reviews" class="tab-content">
						<article>
							<h2>Hotel Score and Score Breakdown</h2>
							<div class="score">
								<span class="achieved">8 </span>
								<span> / 10</span>
								<p class="info">Based on 782 reviews</p>
								<p class="disclaimer">Guest reviews are written by our customers <strong>after their
										stay</strong> at Hotel Best Ipsum.</p>
							</div>

							<dl class="chart">
								<dt>Clean</dt>
								<dd><span id="data-one" style="width:80%;">8</span></dd>
								<dt>Comfort</dt>
								<dd><span id="data-two" style="width:60%;">6</span></dd>
								<dt>Location</dt>
								<dd><span id="data-three" style="width:80%;">8</span></dd>
								<dt>Staff</dt>
								<dd><span id="data-four" style="width:100%;">10</span></dd>
								<dt>Services</dt>
								<dd><span id="data-five" style="width:70%;">7</span></dd>
								<dt>Value for money</dt>
								<dd><span id="data-six" style="width:90%;">9</span></dd>
							</dl>
						</article>

						<article>
							<h2>Guest reviews</h2>
							<ul class="reviews">
								<!--review-->
								<li>
									<figure class="left">
										<img src="{{asset('theme/client/images/uploads/avatar.jpg')}}" alt="avatar" />
										<address><span>Anonymous</span><br />Solo TravellerNorway<br />22/06/2016
										</address>
									</figure>
									<div class="rev pro">
										<p>It was a warm friendly hotel. Very easy access to shops and underground
											stations. Staff very welcoming.</p>
									</div>
									<div class="rev con">
										<p>noisy neigbourghs spoilt the rather calm environment</p>
									</div>
								</li>
								<!--//review-->

								<!--review-->
								<li>
									<figure class="left">
										<img src="{{asset('theme/client/images/uploads/avatar.jpg')}}" alt="avatar" />
										<address><span>Anonymous</span><br />Solo TravellerNorway<br />22/06/2016
										</address>
									</figure>
									<div class="rev pro">
										<p>It was a warm friendly hotel. Very easy access to shops and underground
											stations. Staff very welcoming.</p>
									</div>
									<div class="rev con">
										<p>noisy neigbourghs spoilt the rather calm environment</p>
									</div>
								</li>
								<!--//review-->

								<!--review-->
								<li>
									<figure class="left">
										<img src="{{asset('theme/client/images/uploads/avatar.jpg')}}" alt="avatar" />
										<address><span>Anonymous</span>Solo TravellerNorway22/06/2016</address>
									</figure>
									<div class="rev pro">
										<p>It was a warm friendly hotel. Very easy access to shops and underground
											stations. Staff very welcoming.</p>
									</div>
									<div class="rev con">
										<p>noisy neigbourghs spoilt the rather calm environment</p>
									</div>
								</li>
								<!--//review-->

								<!--review-->
								<li>
									<figure class="left">
										<img src="{{asset('theme/client/images/uploads/avatar.jpg')}}" alt="avatar" />
										<address><span>Anonymous</span><br />Solo TravellerNorway<br />22/06/2016
										</address>
									</figure>
									<div class="rev pro">
										<p>It was a warm friendly hotel. Very easy access to shops and underground
											stations. Staff very welcoming.</p>
									</div>
									<div class="rev con">
										<p>noisy neigbourghs spoilt the rather calm environment</p>
									</div>
								</li>
								<!--//review-->

								<!--review-->
								<li>
									<figure class="left">
										<img src="{{asset('theme/client/images/uploads/avatar.jpg')}}" alt="avatar" />
										<address><span>Anonymous</span><br />Solo TravellerNorway<br />22/06/2016
										</address>
									</figure>
									<div class="rev pro">
										<p>It was a warm friendly hotel. Very easy access to shops and underground
											stations. Staff very welcoming.</p>
									</div>
									<div class="rev con">
										<p>noisy neigbourghs spoilt the rather calm environment</p>
									</div>
								</li>
								<!--//review-->
							</ul>
						</article>
					</section>
					<!--//reviews-->

					<!--things to do-->
					<section id="things-to-do" class="tab-content">
						<article>
							<h2>London</h2>
							<figure><img src="{{asset('theme/client/images/uploads/london1.jpg')}}" alt="Things to do - London general" />
							</figure>
							<p><strong>London is a diverse and exciting city with some of the best sights and
									attractions in the world. </strong></p>
							<p>See London from above on the London Eye; meet a celebrity at Madame Tussauds; examine
								some of the world’s most precious treasures at the British Museum or come face-to-face
								with the dinosaurs at the Natural History Museum.</p>

							<h2>Sports and nature</h2>
							<figure><img src="{{asset('theme/client/images/uploads/london2.jpg')}}"
									alt="Things to do - London Sports and nature" /></figure>
							<p><strong>London is one of the greenest capitals in the world, with plenty of green and
									open spaces. There are more than 3000 open spaces.</strong></p>
							<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod
								tincidunt ut laoreet dolore magna aliquam erat volutpat.Ut wisi enim ad minim veniam,
								quis nostrud exerci. </p>

							<h2>Nightlife</h2>
							<figure><img src="{{asset('theme/client/images/uploads/london3.jpg')}}" alt="Things to do - London Nightlife" />
							</figure>
							<p><strong>Looking for nightclubs in London? Take a look at our guide to London clubs.
									Browse for club ideas, regular club nights and one-off events. </strong></p>
							<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod
								tincidunt ut laoreet dolore magna aliquam erat volutpat.Ut wisi enim ad minim veniam,
								quis nostrud exerci. </p>

							<h2>Culture and history</h2>
							<figure><img src="{{asset('theme/client/images/uploads/london4.jpg')}}" alt="Things to do - London general" />
							</figure>
							<p><strong>For a display of British pomp and ceremony, watch the Changing the Guard ceremony
									outside Buckingham Palace.</strong></p>
							<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod
								tincidunt ut laoreet dolore magna aliquam erat volutpat.Ut wisi enim ad minim veniam,
								quis nostrud exerci. </p>
							<hr />
							<a href="#" class="gradient-button right" title="Read more">Read more</a>
						</article>
					</section>
					<!--//things to do-->
				</section>
				<!--//hotel content-->

				<!--sidebar-->
				<aside class="one-fourth right-sidebar">
					<!--hotel details-->
					<article class="hotel-details">
						<h1>Best ipsum hotel
							<span class="stars">
								<i class="material-icons">&#xE838;</i>
								<i class="material-icons">&#xE838;</i>
								<i class="material-icons">&#xE838;</i>
							</span>
						</h1>
						<span class="address">Marylebone, London</span>
						<span class="rating"> 8 /10</span>
						<div class="description">
							<p>The Best Ipsum hotel features over 1,000 luxuriously appointed, individually styled
								rooms, suites and apartments, each containing unique works of art. </p>
						</div>
						<div class="tags">
							<ul>
								<li><a href="#" title="Wellness">Wellness</a></li>
								<li><a href="#" title="Last minute">Last minute</a></li>
								<li><a href="#" title="Thailand">Thailand</a></li>
								<li><a href="#" title="SPA">SPA</a></li>
								<li><a href="#" title="Romantic">Romantic</a></li>
							</ul>
						</div>
					</article>
					<!--//hotel details-->

					<!--testimonials-->
					<article class="testimonials">
						<blockquote>Loved the staff and the location was just amazing... Perfect!” </blockquote>
						<span class="name">- Jane Doe, Solo Traveller</span>
					</article>
					<!--//testimonials-->

					<!--Need Help Booking?-->
					<article class="widget">
						<h4>Need Help Booking?</h4>
						<p>Call our customer services team on the number below to speak to one of our advisors who will
							help you with all of your holiday needs.</p>
						<p class="number">1- 555 - 555 - 555</p>
					</article>
					<!--//Need Help Booking?-->

					<!--Why Book with us?-->
					<article class="widget">
						<h4>Why Book with us?</h4>
						<h5>Low rates</h5>
						<p>Get the best rates, or get a refund.No booking fees. Save money!</p>
						<h5>Largest Selection</h5>
						<p>140,000+ hotels worldwide130+ airlinesOver 3 million guest reviews</p>
						<h5>We’re Always Here</h5>
						<p>Call or email us, anytimeGet 24-hour support before, during, and after your trip</p>
					</article>
					<!--//Why Book with us?-->

					<!--Popular hotels in the area-->
					<article class="widget">
						<h4>Popular hotels in the area</h4>
						<ul class="popular-hotels small-list">
							<li>
								<a href="#">
									<h3>Plaza Resort Hotel &amp; SPA
										<span class="stars">
											<i class="material-icons">&#xE838;</i>
											<i class="material-icons">&#xE838;</i>
											<i class="material-icons">&#xE838;</i>
											<i class="material-icons">&#xE838;</i>
										</span>
									</h3>
									<p>From <span class="price">$ 100 <small>/ per night</small></span></p>
									<span class="rating"> 8 /10</span>
								</a>
							</li>
							<li>
								<a href="#">
									<h3>Lorem Ipsum Inn
										<span class="stars">
											<i class="material-icons">&#xE838;</i>
											<i class="material-icons">&#xE838;</i>
											<i class="material-icons">&#xE838;</i>
											<i class="material-icons">&#xE838;</i>
											<i class="material-icons">&#xE838;</i>
										</span>
									</h3>
									<p>From <span class="price">$ 110 <small>/ per night</small></span></p>
									<span class="rating"> 7 /10</span>
								</a>
							</li>
							<li>
								<a href="#">
									<h3>Best Eastern London
										<span class="stars">
											<i class="material-icons">&#xE838;</i>
											<i class="material-icons">&#xE838;</i>
											<i class="material-icons">&#xE838;</i>
											<i class="material-icons">&#xE838;</i>
											<i class="material-icons">&#xE838;</i>
										</span>
									</h3>
									<p>From <span class="price">$ 125 <small>/ per night</small></span></p>
									<span class="rating"> 8 /10</span>
								</a>
							</li>
							<li>
								<a href="#">
									<h3>Plaza Resort Hotel &amp; SPA
										<span class="stars">
											<i class="material-icons">&#xE838;</i>
											<i class="material-icons">&#xE838;</i>
											<i class="material-icons">&#xE838;</i>
											<i class="material-icons">&#xE838;</i>
										</span>
									</h3>
									<p>From <span class="price">$ 100 <small>/ per night</small></span></p>
									<span class="rating"> 8 /10</span>
								</a>
							</li>
						</ul>
						<a href="#" title="Show all" class="show-all">Show all</a>
					</article>
					<!--//Popular hotels in the area-->

					<!--Deal of the day-->
					<article class="widget">
						<h4>Deal of the day</h4>
						<div class="deal-of-the-day">
							<figure><a href="hotel.html"><img src="{{asset('theme/client/images/uploads/hotel2.jpg')}}" alt="" /></a></figure>
							<h3><a href="hotel.html">Plaza Resort Hotel &amp; SPA
									<span class="stars">
										<i class="material-icons">&#xE838;</i>
										<i class="material-icons">&#xE838;</i>
										<i class="material-icons">&#xE838;</i>
										<i class="material-icons">&#xE838;</i>
									</span>
								</a></h3>
							<p>From <span class="price">$ 100 <small>/ per night</small></span></p>
							<span class="rating"> 8 /10</span>
						</div>
					</article>
					<!--//Deal of the day-->
				</aside>
				<!--//sidebar-->
			</div>
			<!--//row-->
		</div>
	</main>
	<!--//main-->
@endsection

@section('style-libs')
<link rel="stylesheet" href="{{asset('theme/client/css/lightslider.min.css')}}" />
<link rel="stylesheet" href="{{asset('theme/client/css/lightgallery.min.css')}}" />
@endsection

@section('script-libs')
<script type="text/javascript" src="{{asset('theme/client/js/infobox.js')}}"></script>
<script type="text/javascript" src="{{asset('theme/client/js/lightslider.min.js')}}"></script>
<script type="text/javascript" src="{{asset('theme/client/js/lightgallery-all.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#image-gallery').lightSlider({
            gallery: true,
            item: 1,
            thumbItem: 6,
            slideMargin: 0,
            speed: 500,
            auto: true,
            loop: true,
            onSliderLoad: function () {
                $('#image-gallery').removeClass('cS-hidden');
            }
        });

        $('#gallery1,#gallery2,#gallery3,#gallery4').lightGallery({
            download: false
        });
    });
</script>
@endsection