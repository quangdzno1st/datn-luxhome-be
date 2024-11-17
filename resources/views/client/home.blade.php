@extends('client.layouts.master')

@section('content')
    <!--slider-->
    <div class="slider">
        <ul id="hero-gallery" class="cS-hidden">
            <li data-thumb="{{asset('theme/client/images/uploads/slider7.jpg')}}">
                <img src="{{asset('theme/client/images/uploads/slider7.jpg')}}" alt=""/>
            </li>
            <li data-thumb="{{asset('theme/client/images/uploads/slider3.jpg')}}">
                <img src="{{asset('theme/client/images/uploads/slider3.jpg')}}" alt=""/>
            </li>
            <li data-thumb="{{asset('theme/client/images/uploads/slider2.jpg')}}">
                <img src="{{asset('theme/client/images/uploads/slider2.jpg')}}" alt=""/>
            </li>
        </ul>
    </div>
    <!--//slider-->

    <!--search-->
    <div class="main-search">
        <div class="wrap">
            <form id="main-search" method="post" action="">
                <div class="row">

                    <div class="four-fourth">
                        <!--form hotel-->
                        <div class="form row" id="form1">

                            <!--column-->
                            <div class="column one-third">
                                <h5><span>01</span> Điểm đến - Khách sạn</h5>
                                <div class="row">
                                    <div class="full-width">
                                        <label for="destination1">Thành phố mà bạn muốn đến</label>
                                        <select class="select" name="city_id">
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--//column-->

                            <!--column-->
                            <div class="column  one-third">
                                <h5><span>02</span> Bạn đến khi nào?</h5>
                                <div class="row">
                                    <div class="f-item one-half datepicker">
                                        <label for="datepicker1">Ngày nhận</label>
                                        <div class="datepicker-wrap">
                                            <input type="text" required placeholder="" id="datepicker1"
                                                   name="start_date"/>
                                            <img src="https://www.themeenergy.com/themes/html/book-your-travel/images/ico/calendar.png"
                                                 class="ui-datepicker-trigger">
                                        </div>
                                    </div>
                                    <div class="f-item one-half datepicker">
                                        <label for="datepicker2">Ngày trả</label>
                                        <div class="datepicker-wrap">
                                            <input type="text" placeholder="" id="datepicker2" required
                                                   name="end_date"/>
                                            <img src="https://www.themeenergy.com/themes/html/book-your-travel/images/ico/calendar.png"
                                                 class="ui-datepicker-trigger">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--//column-->

                            <!--column-->
                            <div class="column one-third">
                                <h5><span>03</span> Thông tin</h5>
                                <div class="row">
                                    <div class="f-item one-half spinner">
                                        <label for="spinner2">Người lớn</label>
                                        <input type="number" placeholder="Số lượng người lớn" id="spinner2" required
                                               name="number_adult"/>
                                    </div>
                                    <div class="f-item one-half spinner">
                                        <label for="spinner3">Trẻ em</label>
                                        <input type="text" placeholder="Số lượng trẻ em" id="spinner3" name="number_child"/>
                                    </div>
                                </div>

                                <span class="text-center">*Trẻ em: Từ 2 - dưới 12 tuổi</span>
                            </div>
                            <!--//column-->
                        </div>
                        <!--//form hotel-->
                    </div>
                    <input type="submit" value="Tìm kiếm" class="gradient-button search-submit"
                           id="search-submit"/>
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
                        <h2>Khách sạn phổ biến</h2>
                    </header>

                    <div class="deals">
                        <div class="row">

                            @foreach($hotels as $key => $hotel)
                                <!--deal-->
                                <article class="one-fourth">
                                    <figure><a href="{{  route('home.hotel.detail', $hotel['id'])  }}" title="">
                                            <img src="{{ Storage::url('images'. '/' .$hotel['thumbnail']) }}"
                                                 alt="Image">
                                        </a>
                                    </figure>
                                    <div class="details">
                                        <h3>{{ $hotel['name']  }}
                                            <span class="stars">
                                                @for($i = 1; $i <= $hotel['star']; $i++)
                                                    <i class="material-icons">&#xE838;</i>
                                                @endfor
                                                @if( $key/3 ==0 )<i class="material-icons">&#xE838;</i>@endif
									        </span>
                                        </h3>
                                        <span class="address">{{$hotel['district']}} • {{$hotel['province']}}</span>
                                        <span class="rating">
                                             {{ $key / 3 == 0 ? '10/10' : '9/10' }}
                                        </span>
                                        <div class="description">
                                            <p class="text-clamp">{{$hotel['description']}} <a
                                                        href="{{ route('home.hotel.detail', $hotel['id'])  }} ">Xem
                                                    thêm</a></p>
                                        </div>
                                        <a href="{{route('hotel.booking', $hotel['id'])}}" title="Book now" class="gradient-button">Đặt ngay</a>
                                    </div>
                                </article>
                                <!--//deal-->
                            @endforeach
                        </div>
                    </div>
                    <!--//deals-->

                    <header class="s-title">
                        <h2>Những điểm đến hàng đầu</h2>
                    </header>

                    <!--top destinations-->
                    <div class="destinations">
                        <div class="row">
                            <!--column-->
                            @foreach(array_chunk($cities->toArray(), 10)[0] as $city)
                                <article class="one-fourth">
                                    <figure><a href="" title=""><img
                                                    src="{{ Storage::url('images'. '/' . $city['thumbnail']) }}"
                                                    alt=""/></a></figure>
                                    <div class="details">
                                        <a href="#" title="View all" class="gradient-button">Xem tất cả</a>
                                        <h4>{{ $city['name']}}</h4>
                                        <span class="count">{{ $city['hotel_qty'] }} Khách sạn</span>
                                        <div class="ribbon">
                                            <div class="half">
                                                <a href="hotels.html" title="View all" style="padding: 5px;">
                                                    <span class="small" style="text-align:center">Tổng lượt đặt (tháng)</span>
                                                    <span class="price" style="text-align: center; padding-top: 3px">{{ $totalOrderMap[$city['id']]['orders_this_month']}}</span>
                                                </a>
                                            </div>
                                            <div class="half">
                                                <a href="flights.html" title="View all" style="padding: 5px;">
                                                    <span class="small" style="text-align:center">Tổng lượt đặt</span>
                                                    <span class="price" style="text-align:center; padding-top: 3px">{{ $totalOrderMap[$city['id']]['total_orders']}}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
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
    <link rel="stylesheet" href="{{asset('theme/client/css/lightslider.min.css')}}"/>
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

            var today = new Date();
            var checkInDate = null;
            var checkOutDate = null;

            // Hàm thêm lớp CSS tùy chỉnh
            function highlightDays(date) {
                if (checkInDate && checkOutDate) {
                    if (date >= checkInDate && date <= checkOutDate) {
                        return [true, "highlight-range"]; // Thêm màu cho khoảng ngày
                    }
                }

                if (checkInDate && date.getTime() === checkInDate.getTime()) {
                    return [true, "highlight-selected"]; // Đánh dấu ngày Check-in
                }

                if (checkOutDate && date.getTime() === checkOutDate.getTime()) {
                    return [true, "highlight-selected"]; // Đánh dấu ngày Check-out
                }

                return [true, ""]; // Ngày không đặc biệt
            }

            // Khởi tạo datepicker cho Check-in
            $("#datepicker1").datepicker({
                dateFormat: "yy-mm-dd",
                minDate: today,
                beforeShowDay: function (date) {
                    if (checkOutDate && date > checkOutDate) {
                        return [false, ""];
                    }
                    return highlightDays(date);
                },
                onSelect: function (selectedDate) {
                    checkInDate = new Date(selectedDate);
                    checkInDate.setHours(0, 0, 0, 0); // Đặt về đầu ngày
                    var minCheckOutDate = new Date(checkInDate);
                    minCheckOutDate.setDate(minCheckOutDate.getDate() + 1);
                    $("#datepicker2").datepicker("option", "minDate", minCheckOutDate);
                    $("#datepicker2").datepicker("refresh"); // Làm mới datepicker
                }
            });

            // Khởi tạo datepicker cho Check-out
            $("#datepicker2").datepicker({
                dateFormat: "yy-mm-dd",
                minDate: new Date(today.getTime() + 24 * 60 * 60 * 1000),
                beforeShowDay: highlightDays,
                onSelect: function (selectedDate) {
                    checkOutDate = new Date(selectedDate);
                    checkOutDate.setHours(0, 0, 0, 0); // Đặt về đầu ngày
                }
            });
        })(jQuery);
    </script>


    <style>
        .text-clamp {
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Giới hạn 2 dòng */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis; /* Thêm dấu ba chấm */
            line-height: 20px;
        }

        .highlight-selected {
            font-weight: bold;
            color: #ffffff;
            background-color: #007bff !important;
        }

        /* Ngày trong khoảng từ Check-in đến Check-out */
        .highlight-range {
            background-color: #6adaf7 !important;
            color: #2086F3 !important;
        }

        .ui-datepicker .ui-datepicker-today {
            background: #ffad7b !important;
        }

    </style>
@endsection