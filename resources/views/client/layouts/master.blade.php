<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from www.themeenergy.com/themes/html/book-your-travel/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 14 Nov 2024 15:01:28 GMT -->

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="Book Your Travel - Online Booking HTML Template">
	<meta name="description" content="Book Your Travel - Online Booking HTML Template">
	<meta name="author" content="themeenergy.com">

	<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

	<title> @yield('title') </title>
	<!-- Latest compiled and minified CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Latest compiled JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


	<link rel="stylesheet" href="{{asset('theme/client/css/style.css')}}" />
	<link rel="stylesheet" href="{{asset('theme/client/css/theme-turqoise.css')}}" id="template-color" />

	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800|Roboto+Slab:400,700&amp;subset=latin,latin-ext,greek-ext,greek,cyrillic,vietnamese,cyrillic-ext">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<script src="{{asset('theme/client/fontawesome/e808bf9397.js')}}"></script>
	<link rel="shortcut icon" href="{{asset('theme/client/images/favicon.ico')}}" />
	<link rel="stylesheet" href="{{asset('theme/client/css/styler.css')}}" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


	@yield('style-libs')

    @yield('styles')

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
	<!--- loading animation -->
	<div class="loading">
		<div class="ball"></div>
		<div class="ball1"></div>
	</div>
	<!--- //loading animation -->

	<!--header-->
    @include('client.layouts.header')
	<!--//header-->

    @yield('content')

	<!--footer-->
	@include('client.layouts.footer')
	<!---footer-->

	<script src="{{asset('theme/client/ajax/libs/jquery/3.1.0/jquery.min.js')}}"></script>
	<script src="{{asset('theme/client/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('theme/client/js/jquery.uniform.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('theme/client/js/jquery.slimmenu.min.js')}}"></script>

	<script type="text/javascript" src="{{asset('theme/client/js/scripts.js')}}"></script>


	{{-- <!-- TEMPLATE STYLES -->
	<div id="template-styles">
		<h2>Template Styles <a href="#"><img class="s-s-icon" src="{{asset('theme/client/images/settings.png')}}" alt="Style switcher" /></a></h2>
		<div>
			<h3>Colors</h3>
			<ul class="colors">
				<li><a href="#" class="yellow" title="yellow"></a></li>
				<li><a href="#" class="orange" title="orange"></a></li>
				<li><a href="#" class="strawberry" title="strawberry"></a></li>
				<li><a href="#" class="pink" title="pink"></a></li>
				<li><a href="#" class="purple" title="purple"></a></li>
				<li><a href="#" class="blue" title="blue"></a></li>
				<li><a href="#" class="turqoise" title="turqoise"></a></li>
				<li><a href="#" class="black" title="black"></a></li>
			</ul>
		</div>
	</div>--}}
	<script src="{{asset('theme/client/js/styler.js')}}"></script>


    @yield('script-libs')

    @yield('scripts')
</body>

<!-- Mirrored from www.themeenergy.com/themes/html/book-your-travel/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 14 Nov 2024 15:02:00 GMT -->

</html>