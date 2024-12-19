<header class="header">
    <div class="wrap">
        <!--logo-->
        <div class="logo"><a href="{{ route('home.index') }}" title="Book Your Travel"><img width="145px" style="height: 67px" src="{{asset('theme/client/images/txt/logo.png')}}"
                    alt="Book Your Travel" /></a></div>
        <!--//logo-->

        <!--ribbon-->
        <div class="ribbon" style="min-height: 0 !important; text-align: center">
            <nav style="min-height: 0 !important">
              @if(\Illuminate\Support\Facades\Auth::check())
                    <ul class="">
                        <li class="active"><a href="{{ route('orders.index') }}" title="Settings">Cài Đặt</a></li>
                        @if (Auth::user()->type !== App\Models\User::CUSTOMER)
                            <li class="active"><a href="{{ route('admin.statistical.index') }}" title="Settings">Quản Trị</a></li>
                        @endif
                        <li class="active"><a href="{{route('client.logout')}}" title="Logout" onclick="return confirm('Bạn có muốn đăng xuất không?')">Đăng Xuất</a></li>
                    </ul>
                @else
                    <ul class="lang-nav">
                        <li class="active"><a href="{{route('client.login')}}" title="Login">Đăng Nhập</a></li>
                    </ul>
                    <ul class="currency-nav">
                        <li class="active"><a href="{{route('client.register')}}" title="Register">Đăng Ký</a></li>
                    </ul>
                @endif
            </nav>
        </div>
        <!--//ribbon-->

        <!--search-->
{{--        <div class="search contact">--}}
{{--            <div>Hỗ trợ 24/7</div>--}}
{{--            <div class="number">081948374</div>--}}
{{--        </div>--}}
        <!--//search-->

        <!--contact-->

        <!--//contact-->
    </div>

    <!--main navigation-->
{{--    <nav class="main-nav">--}}
{{--        <div class="wrap">--}}
{{--            <ul class="slimmenu" id="nav">--}}
{{--                <li><a href="{{ route('home.index') }}" title="Hotels">Trang Chủ</a>--}}
{{--                    --}}{{-- <ul>--}}
{{--                        <li><a href="#">Secondary navigation</a></li>--}}
{{--                        <li><a href="#">Example links</a>--}}
{{--                            <ul>--}}
{{--                                <li><a href="#">Third level navigation</a></li>--}}
{{--                                <li><a href="#">Example links</a>--}}
{{--                                    <ul>--}}
{{--                                        <li><a href="#">Fourth level navigation</a></li>--}}
{{--                                        <li><a href="#">Example links</a></li>--}}
{{--                                    </ul>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    </ul> --}}
{{--                </li>--}}
{{--                --}}{{-- <li><a href="flights.html" title="Flights">Flights</a></li>--}}
{{--                <li><a href="flight_and_hotels.html" title="Flight + Hotel">Flight + Hotel</a></li>--}}
{{--                <li><a href="self_caterings.html" title="Self catering">Self catering</a></li>--}}
{{--                <li><a href="cruises.html" title="Cruises">Cruises</a></li>--}}
{{--                <li><a href="car_rentals.html" title="Car rental">Car rental</a></li>--}}
{{--                <li><a href="locations.html" title="Locations">Locations</a>--}}
{{--                    <ul>--}}
{{--                        <li><a href="location.html" title="Single Location">Single Location</a>--}}
{{--                    </ul>--}}
{{--                </li>--}}
{{--                <li><a href="hot_deals.html" title="Hot deals">Hot deals</a></li>--}}
{{--                <li class="has-mega"><a href="#" title="Pages">Pages</a>--}}
{{--                    <ul class="mega">--}}
{{--                        <li>--}}
{{--                            <div class="row">--}}
{{--                                <div class="one-fifth">--}}
{{--                                    <p>COMMON PAGES</p>--}}
{{--                                    <ul>--}}
{{--                                        <li><a href="page_left_sidebar.html" title="Page with left sidebar">Page--}}
{{--                                                with left sidebar</a></li>--}}
{{--                                        <li><a href="page_right_sidebar.html" title="Page with right sidebar">Page--}}
{{--                                                with right sidebar</a></li>--}}
{{--                                        <li><a href="page_both_sidebars.html" title="Page with both sidebars">Page--}}
{{--                                                with both sidebars</a></li>--}}
{{--                                        <li><a href="page_no_sidebars.html" title="Page with no sidebars">Page with--}}
{{--                                                no sidebars</a></li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                                <div class="one-fifth">--}}
{{--                                    <p>LISTING LAYOUTS</p>--}}
{{--                                    <ul>--}}
{{--                                        <li><a href="hotels_2col.html" title="Listing 2 columns">Listing 2--}}
{{--                                                columns</a></li>--}}
{{--                                        <li><a href="hotels_3col.html" title="Listing 3 columns">Listing 3--}}
{{--                                                columns</a></li>--}}
{{--                                        <li><a href="hotels.html" title="Listing 4 columns">Listing 4 columns</a>--}}
{{--                                        </li>--}}
{{--                                        <li><a href="hotel.html" title="Single listing">Single listing</a></li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                                <div class="one-fifth">--}}
{{--                                    <p>USER PAGES</p>--}}
{{--                                    <ul>--}}
{{--                                        <li><a href="login.html" title="Login">Login</a></li>--}}
{{--                                        <li><a href="register.html" title="Register">Register</a></li>--}}
{{--                                        <li><a href="my_account.html" title="My Account">My account</a></li>--}}
{{--                                        <li><a href="error.html" title="Error 404">Error 404</a></li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                                <div class="one-fifth">--}}
{{--                                    <p>SPECIALTY PAGES</p>--}}
{{--                                    <ul>--}}
{{--                                        <li><a href="search_results.html" title="Search results hotels">Search--}}
{{--                                                results hotels</a></li>--}}
{{--                                        <li><a href="search_results_flights.html"--}}
{{--                                                title="Search results flights">Search results flights</a></li>--}}
{{--                                        <li><a href="get_inspired.html" title="Get inspired">Get inspired</a></li>--}}
{{--                                        <li><a href="get_inspired_results.html" title="Get inspired results">Get--}}
{{--                                                inspired results</a></li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                                <div class="one-fifth">--}}
{{--                                    <p>&nbsp;</p>--}}
{{--                                    <ul>--}}
{{--                                        <li><a href="booking-step1.html" title="Booking step 1">Booking step 1</a>--}}
{{--                                        </li>--}}
{{--                                        <li><a href="booking-step2.html" title="Booking step 2">Booking step 2</a>--}}
{{--                                        </li>--}}
{{--                                        <li><a href="booking-step3.html" title="Booking step 3">Booking step 3</a>--}}
{{--                                        </li>--}}
{{--                                        <li><a href="loading.html" title="Loading">Loading</a></li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li> --}}
{{--                <li><a href="contact.html" title="Contact">Liên Hệ</a></li>--}}
{{--                <li><a href="blog.html" title="Blog">Tin Tức</a>--}}
{{--                    --}}{{-- <ul>--}}
{{--                        <li><a href="blog_single.html" title="Single Post">Single Post</a>--}}
{{--                    </ul> --}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    </nav>--}}
    <!--//main navigation-->
</header>