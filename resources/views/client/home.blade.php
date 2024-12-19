@extends('client.layouts.master')

@section('title')
    LuxHome - Trang Chủ
@endsection

@section('content')
<!--slider-->

<style></style>
<div class="slider">
    <ul id="hero-gallery" class="cS-hidden">
        @foreach ($banners as $banner)
            <li data-thumb="{{Storage::url($banner->image)}}">
                <img src="{{Storage::url($banner->image)}}" alt="" />
            </li>
        @endforeach
    </ul>
</div>
<!--//slider-->

<!--search-->
<div class="main-search" style="background-color: #f9f9f9; padding: 20px; border-radius: 10px;">
    <div class="wrap">
        <form id="main-search" method="get" action="{{ route('home.search') }}"
            style="max-width: 1300px; margin: auto;">
            <div class="row">
                <!-- Điểm đến -->
                <div class="column one-third" style="padding: 10px;">
                    <h5 style="font-size: 1.2rem; font-weight: bold; color: #333;"><span>01</span> Điểm đến - Khách sạn
                    </h5>
                    <div class="full-width">
                        <label for="destination1" style="font-weight: bold; margin-bottom: 5px; display: block;">Thành
                            phố mà bạn muốn đến</label>
                        <select class="select" name="city_id"
                            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Ngày đến -->
                <div class="column one-third" style="padding: 10px;">
                    <h5 style="font-size: 1.2rem; font-weight: bold; color: #333;"><span>02</span> Bạn đến khi nào?</h5>
                    <div class="row">
                        <div class="f-item one-half datepicker" style="padding-right: 10px;">
                            <label for="datepicker1" style="font-weight: bold; margin-bottom: 5px; display: block;">Ngày
                                bắt đầu</label>
                            <input type="text" id="datepicker1" name="start_date"
                                value="{{ old('start_date') ?? \Carbon\Carbon::now()->format('Y-m-d') }}"
                                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                            @error('start_date')
                                <div class="text-danger" style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="f-item one-half datepicker">
                            <label for="datepicker2" style="font-weight: bold; margin-bottom: 5px; display: block;">Ngày
                                kết thúc</label>
                            <input type="text" id="datepicker2" name="end_date"
                                value="{{ old('end_date') ?? \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                            @error('end_date')
                                <div class="text-danger" style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Thông tin người -->
                <div class="column one-third" style="padding: 10px;">
                    <h5 style="font-size: 1.2rem; font-weight: bold; color: #333;"><span>03</span> Thông tin</h5>
                    <div class="row">
                        <div class="f-item one-half spinner" style="padding-right: 10px;">
                            <label for="spinner2" style="font-weight: bold; margin-bottom: 5px; display: block;">Người
                                lớn</label>
                            <input type="number" id="spinner2" min="1" name="number_adult"
                                value="{{ old('number_adult') ?? 2 }}"
                                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                            @error('number_adult')
                                <div class="text-danger" style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="f-item one-half spinner">
                            <label for="spinner3" style="font-weight: bold; margin-bottom: 5px; display: block;">Trẻ
                                em</label>
                            <input type="number" id="spinner3" min="1" name="number_child"
                                value="{{ old('number_child') }}"
                                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                            @error('number_child')
                                <div class="text-danger" style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <p style="font-size: 0.9rem; color: #666; text-align: center; margin-top: 10px;">*Trẻ em: Từ 2 -
                        dưới 12 tuổi</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="text-align: center;">
                <input type="submit" value="Tìm kiếm"
                    style="background: linear-gradient(90deg, #36d1dc, #5b86e5); color: #fff; padding: 10px 20px; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer;">
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
                                                @php
                                                    $rates = $hotel->rates()->pluck('rate');
                                                    $total = $rates->sum();
                                                    $count = $rates->count();
                                                    $average = $count > 0 ? $total / $count : 0;
                                                    $rating = round($average * 2, 1);
                                                @endphp
                                                <!--deal-->
                                                <article class="one-fourth">
                                                    <figure><a href="{{route('hotel.show', ['hotel_id' => $hotel['id'], 'check' => 1, 'start_date' => \Carbon\Carbon::now()->format('Y-m-d'), 'end_date' => \Carbon\Carbon::tomorrow()->format('Y-m-d')])}}" title="">
                                                            <img src="{{ Storage::url($hotel['thumbnail']) }}" alt="Image">
                                                        </a>
                                                    </figure>
                                                    <div class="details">
                                                        <h3>{{ $hotel['name']  }}
                                                            <span class="stars">
                                                                @for($i = 1; $i <= $hotel['star']; $i++)
                                                                    <i class="material-icons">&#xE838;</i>
                                                                @endfor
                                                            </span>
                                                        </h3>
                                                        <span class="address">{{$hotel['district']}} • {{$hotel['province']}}</span>
                                                        <span class="rating">
                                                            {{ $rating }}
                                                        </span>
                                                        <div class="description text-clamp-5" style="padding: 3px">
                                                            <p class="">{!! $hotel['description'] !!} <a
                                                                    href="{{ route('home.hotel.detail', $hotel['id'])  }} ">Xem
                                                                    thêm</a></p>
                                                        </div>
                                                        <a href="{{route('hotel.show', ['hotel_id' => $hotel['id'], 'check' => 1, 'start_date' => \Carbon\Carbon::now()->format('Y-m-d'), 'end_date' => \Carbon\Carbon::tomorrow()->format('Y-m-d')])}}"
                                                            title="Book now" class="gradient-button">Xem ngay</a>
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
                        @php
                            $sortedCities = collect($cities)->sortByDesc(function ($city) use ($totalOrderMap) {
                                return $totalOrderMap[$city['id']]['orders_this_month'] ?? 0;
                            });
                        @endphp
                        @foreach(array_chunk($sortedCities->toArray(), 10)[0] as $city)
                            <article class="one-fourth">
                                <figure><a href="" title=""><img src="{{ Storage::url($city['thumbnail']) }}" alt="" /></a>
                                </figure>
                                <div class="details">
                                    <a href="{{ route('home.search', ['city_id' => $city['id'], 'type' => true, 'start_date' => \Carbon\Carbon::now()->format('Y-m-d'), 'end_date' => \Carbon\Carbon::tomorrow()->format('Y-m-d')]) }}"
                                        title="View all" class="gradient-button">Xem tất cả</a>

                                    <h4>{{ $city['name']}}</h4>
                                    <span class="count">Có tất cả <strong>{{ $city['hotel_qty'] }}</strong> khách sạn</span>
                                    <div class="ribbon">
                                        <div class="half">
                                            <a href="#" title="View all" style="padding: 5px;">
                                                <span class="small" style="text-align:center">Tổng lượt đặt (tháng)</span>
                                                <span class="price"
                                                    style="text-align: center; padding-top: 3px">{{ $totalOrderMap[$city['id']]['orders_this_month']}}</span>
                                            </a>
                                        </div>
                                        <div class="half">
                                            <a href="#" title="View all" style="padding: 5px;">
                                                <span class="small" style="text-align:center">Tổng lượt đặt</span>
                                                <span class="price"
                                                    style="text-align:center; padding-top: 3px">{{ $totalOrderMap[$city['id']]['total_orders']}}</span>
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
    .text-clamp-5 {
        display: -webkit-box;
        -webkit-line-clamp: 5;
        /* Giới hạn 2 dòng */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        /* Thêm dấu ba chấm */
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