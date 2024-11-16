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
					<li><a href="#" title="Back to results">Back to results</a></li>
					<li><a href="#" title="Change search">Change search</a></li>
				</ul>
				<!--//top right navigation-->
			</nav>
			<!--//breadcrumbs-->
			
			<div class="row">
				<!--sidebar-->
				<aside class="one-fourth left-sidebar">
					<article class="widget refine-search-results">
						<h4>Refine search results</h4>
						<dl>
							<!--Price (per night)-->
							<dt>Price (per night)</dt>
							<dd>
								<div class="checkbox">
									<input type="checkbox" id="ch1" name="price" />
									<label for="ch1">0 - 49 $</label>
								</div>
								<div class="checkbox">
									<input type="checkbox" id="ch2" name="price" />
									<label for="ch2">50 - 99 $</label>
								</div>
								<div class="checkbox">
									<input type="checkbox" id="ch3" name="price" />
									<label for="ch3">100 -149 $</label>
								</div>
								<div class="checkbox">
									<input type="checkbox" id="ch4" name="price" />
									<label for="ch4">150 - 199 $</label>
								</div>
								<div class="checkbox">
									<input type="checkbox" id="ch5" name="price" />
									<label for="ch5">200 $ +</label>
								</div>
							</dd>
							<!--//Price (per night)-->
							
							<!--Star rating-->
							<dt>Star rating</dt>
							<dd>
								<div class="checkbox">
									<input type="checkbox" id="ch1" name="price" />
									<label for="ch1" class="stars" style="float:unset">
										<i class="material-icons">&#xE838;</i>
										<i class="material-icons">&#xE838;</i>
										<i class="material-icons">&#xE838;</i>
										<i class="material-icons">&#xE838;</i>
										<i class="material-icons">&#xE838;</i>
									</label>
								</div>
								<div class="checkbox">
									<input type="checkbox" id="ch2" name="price" />
									<label for="ch2" class="stars" style="float:unset">
										<i class="material-icons">&#xE838;</i>
										<i class="material-icons">&#xE838;</i>
										<i class="material-icons">&#xE838;</i>
										<i class="material-icons">&#xE838;</i>
									</label>
								</div>
								<div class="checkbox">
									<input type="checkbox" id="ch3" name="price" />
									<label for="ch3" class="stars" style="float:unset">
										<i class="material-icons">&#xE838;</i>
										<i class="material-icons">&#xE838;</i>
										<i class="material-icons">&#xE838;</i>
									</label>
								</div>
								<div class="checkbox">
									<input type="checkbox" id="ch4" name="price" />
									<label for="ch4" class="stars" style="float:unset">
										<i class="material-icons">&#xE838;</i>
										<i class="material-icons">&#xE838;</i>
									</label>
								</div>
								<div class="checkbox">
									<input type="checkbox" id="ch5" name="price" />
									<label for="ch5" class="stars" style="float:unset">
										<i class="material-icons">&#xE838;</i>
									</label>
								</div>
							</dd>
							<!--//Star rating-->
							
							<!--User rating-->
							<dt>User rating</dt>
							<dd>
								<div id="slider"></div>
								<span class="min">0</span><span class="max">10</span>
							</dd>
							<!--//User rating-->
							
						</dl>
					</article>
				</aside>
				<!--//sidebar-->
			
				<!--three-fourth content-->
				<div class="three-fourth">
					<div class="sort-by">
						<h3>Sort by</h3>
						<ul class="sort">
							<li>Popularity <a href="#" title="ascending" class="ascending">ascending</a><a href="#" title="descending" class="descending">descending</a></li>
							<li>Price <a href="#" title="ascending" class="ascending">ascending</a><a href="#" title="descending" class="descending">descending</a></li>
							<li>Stars <a href="#" title="ascending" class="ascending">ascending</a><a href="#" title="descending" class="descending">descending</a></li>
							<li>Rating <a href="#" title="ascending" class="ascending">ascending</a><a href="#" title="descending" class="descending">descending</a></li>
						</ul>
						
						<ul class="view-type">
							<li class="grid-view"><a href="#" title="grid view">grid view</a></li>
							<li class="list-view"><a href="#" title="list view">list view</a></li>
							<li class="location-view"><a href="#" title="location view">location view</a></li>
						</ul>
					</div>
					
					<div class="row deals  results">
						<!--deal-->
						<article class="one-third">
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
								<span class="price">Price per night from  <em>$ 50</em> </span>
								<div class="description">
									<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
								</div>
								<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
							</div>
						</article>
						<!--//deal-->
						
						<!--deal-->
						<article class="one-third">
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
								<span class="price">Price per night from  <em>$ 80</em> </span>
								<div class="description">
									<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
								</div>
								<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
							</div>
						</article>
						<!--//deal-->
						
						<!--deal-->
						<article class="one-third">
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
								<span class="price">Price per night from  <em>$ 70</em> </span>
								<div class="description">
									<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
								</div>
								<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
							</div>
						</article>
						<!--//deal-->
						
						<!--deal-->
						<article class="one-third">
							<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel4.jpg')}}" alt="" /></a></figure>
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
								<span class="price">Price per night from  <em>$ 70</em> </span>
								<div class="description">
									<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
								</div>
								<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
							</div>
						</article>
						<!--//deal-->
						
						<!--deal-->
						<article class="one-third">
							<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel5.jpg')}}" alt="" /></a></figure>
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
								<span class="price">Price per night from  <em>$ 50</em> </span>
								<div class="description">
									<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
								</div>
								<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
							</div>
						</article>
						<!--//deal-->
						
						<!--deal-->
						<article class="one-third">
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
								<span class="price">Price per night from  <em>$ 80</em> </span>
								<div class="description">
									<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
								</div>
								<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
							</div>
						</article>
						<!--//deal-->
						
						<!--deal-->
						<article class="one-third">
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
								<span class="price">Price per night from  <em>$ 50</em> </span>
								<div class="description">
									<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
								</div>
								<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
							</div>
						</article>
						<!--//deal-->
						
						<!--deal-->
						<article class="one-third">
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
								<span class="price">Price per night from  <em>$ 80</em> </span>
								<div class="description">
									<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
								</div>
								<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
							</div>
						</article>
						<!--//deal-->
						
						<!--deal-->
						<article class="one-third">
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
								<span class="price">Price per night from  <em>$ 70</em> </span>
								<div class="description">
									<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
								</div>
								<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
							</div>
						</article>
						<!--//deal-->
						
						<!--deal-->
						<article class="one-third">
							<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel4.jpg')}}" alt="" /></a></figure>
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
								<span class="price">Price per night from  <em>$ 50</em> </span>
								<div class="description">
									<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
								</div>
								<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
							</div>
						</article>
						<!--//deal-->
						
						<!--deal-->
						<article class="one-third">
							<figure><a href="hotel.html" title=""><img src="{{asset('theme/client/images/uploads/hotel5.jpg')}}" alt="" /></a></figure>
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
								<span class="price">Price per night from  <em>$ 50</em> </span>
								<div class="description">
									<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
								</div>
								<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
							</div>
						</article>
						<!--//deal-->
						
						<!--deal-->
						<article class="one-third">
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
								<span class="price">Price per night from  <em>$ 80</em> </span>
								<div class="description">
									<p>Overlooking the Aqueduct and Nature Park, Lorem Ipsum Hotel is situated 5 minutes’ walk from London’s Zoological Gardens and a metro station. <a href="hotel.html">More info</a></p>
								</div>
								<a href="hotel.html" title="Book now" class="gradient-button">Book now</a>
							</div>
						</article>
						<!--//deal-->
						
						<!--bottom navigation-->
						<div class="bottom-nav">
							<!--back up button-->
							<a href="#" class="scroll-to-top" title="Back up">Back up</a> 
							<!--//back up button-->
							
							<!--pager-->
							<div class="pager">
								<span><a href="#">First page</a></span>
								<span><a href="#">&lt;</a></span>
								<span class="current">1</span>
								<span><a href="#">2</a></span>
								<span><a href="#">3</a></span>
								<span><a href="#">4</a></span>
								<span><a href="#">5</a></span>
								<span><a href="#">6</a></span>
								<span><a href="#">7</a></span>
								<span><a href="#">8</a></span>
								<span><a href="#">&gt;</a></span>
								<span><a href="#">Last page</a></span>
							</div>
							<!--//pager-->
						</div>
						<!--//bottom navigation-->
					</div>
				</div>
				<!--//three-fourth content-->
			</div>
			<!--//main content-->
		</div>
	</main>
	<!--//main-->
@endsection

@section('script-libs')
<script type="text/javascript" src="{{asset('theme/client/js/jquery.raty.min.js')}}"></script>
<script type="text/javascript" src="{{asset('theme/client/js/search.js')}}"></script>
@endsection