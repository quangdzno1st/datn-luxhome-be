@extends('client.layouts.master')

<style>
    body {
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    .container {
        display: flex;
        justify-content: space-between;
        padding: 20px;
    }

    .left-panel,
    .right-panel {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        width: 100%;
    }

    .left-panel h2,
    .right-panel h2 {
        font-size: 18px;
        color: #a89c7c;
        margin-bottom: 10px;
    }

    .left-panel .service-item,
    .right-panel {
        margin: 20px 0;
        border-left: 5px solid #a89c7c;
        padding-left: 10px;
        border-radius: 0 5px 5px 0;
    }

    .left-panel .service-item p,
    .right-panel .trip-info p {
        margin: 5px 0;
        font-size: 14px;
    }

    .service-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .left-panel .service-item p:first-child {
        font-weight: bold;
    }

    .right-panel .trip-info p:first-child {
        font-weight: bold;
    }

    .right-panel .trip-info p i {
        margin-right: 5px;
    }

    .right-panel .trip-info .price {
        color: #f7941d;
        font-weight: bold;
    }

    .right-panel .continue-btn {
        background-color: #f7941d;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }

    .service-dropdown {
        position: relative;
        margin-top: 10px;
    }

    .continue-button {
        background-color: #f7941d;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }

    .service-dropdown .dropdown-content {
        display: none;
        position: absolute;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        padding: 10px;
        width: 300px;
        top: 30px;
        left: 0;
        max-height: 200px;
        overflow-y: auto;
        z-index: 10;
    }

    .service-dropdown .dropdown-content .dropdown-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .service-dropdown .dropdown-content .dropdown-item img {
        width: 50px;
        height: 50px;
        border-radius: 5px;
        margin-right: 10px;
    }

    .service-dropdown .dropdown-content .dropdown-item p {
        margin: 0;
        font-size: 14px;
    }

    .service-dropdown .dropdown-content .dropdown-item p:first-child {
        font-weight: bold;
    }

    .service-dropdown .dropdown-content .dropdown-item input {
        margin-left: auto;
    }

    .service-dropdown:hover .dropdown-content {
        display: block;
    }
</style>

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
                    <li>Đặt dịch vụ</li>
                </ul>
                <!--//crumbs-->
            </nav>
            <!--//breadcrumbs-->
            <div class="row">
                <!--three-fourth content-->
                <div class="">
                    <form id="booking" method="get" action="{{ route('orders.confirm') }}" class=" booking">
                        @csrf
                        <div class="" style="display: flex">
                            <div class="left-panel two-third" style="padding: 15px 20px">
                                <h2>
                                    Dịch vụ mua thêm
                                </h2>
                                @foreach($roomsOrder as $room)
                                    <div class="service-item">
                                        <div>
                                            <h3 style="color: #000000; margin-left: 0;">{{ $room['code'] }}</h3>
                                            <p style="padding-bottom: 0;">Loại
                                                phòng: {{ $room['catalogue_room_name'] }}</p>
                                        </div>
                                        <div class="service-dropdown">
                                            <a class="continue-button">
                                                Đặt dịch vụ
                                            </a>
                                            <div class="dropdown-content">
                                                @foreach($services as $service)
                                                    <div class="dropdown-item" style="justify-content: space-between;">
                                                        <div style="display: flex; align-items: center; justify-content: left;">
                                                            <img alt="{{ $service['service_name'] }}" height="70"
                                                                 src="https://storage.googleapis.com/a1aa/image/fTBDgwUBPzxJDiH8nDgitf4wy92lU2H3thdHLP91P0LfLrknA.jpg"
                                                                 width="100"/>
                                                            <div class="service-info">
                                                                <h4 style="color: #000; padding-bottom: 0;">
                                                                    {{ $service['service_name'] }}
                                                                </h4>
                                                                <p
                                                                        style="color: #666; font-size: 12px; margin-left: 0; padding-bottom: 0;">
                                                                    {{ number_format($service['service_price']) . ' đ' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="service-actions">
                                                            <input type="checkbox"
                                                                   name="services[{{ $room['room_id'] }}][{{ $service['service_id'] }}]"
                                                                   value="1">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!--//three-fourth content-->

                            <!--right sidebar-->
                            <aside class="one-third right-sidebar booking">
                                <!--Booking details-->
                                <article class="hotel-details booking-details">
                                    <h2 class="">
                                        Chuyến đi
                                    </h2>
                                    <div class="trip-info">
                                        <p><strong>{{ $room['hotel_name'] }}</strong></p>
                                        <p>
                                            <i class="far fa-calendar-alt"></i>
                                            {{ \Carbon\Carbon::parse($room['start_date'])->format('d/m/Y') }}
                                            -&gt;
                                            {{ \Carbon\Carbon::parse($room['end_date'])->format('d/m/Y') }}
                                        </p>
                                        <p>
                                            <i class="far fa-calendar-alt"></i>
                                            {{ (new DateTime($room['end_date']))->diff(new DateTime($room['start_date']))->days }}
                                            đêm
                                        </p>
                                        <div>
                                            <div style="display: flex; justify-content: space-between; align-items: center">
                                                <h5>{{ $room['code'] }}</h5>
                                                <h6 class="total-cost"> {{number_format($room['price'])}} đ / đêm</h6>
                                            </div>
                                            <p><i class="fas fa-bed"></i> x1 Phòng suite</p>
                                            <p><i class="fas fa-user"></i> Người lớn: {{ $room['number_adult'] }}, Trẻ
                                                em: {{ $room['number_child'] }}</p>
                                        </div>
                                    </div>
                                    <div class="total-cost">
                                        <p>Tổng cộng:
                                            <span></span>
                                        </p>
                                    </div>
                                    <button class="continue-button"
                                            style="width: 100%; background-color: #f7941d !important" href="#">
                                        Tiếp tục
                                    </button>
                                </article>
                                <!--//Booking details-->
                            </aside>
                            <!--//right sidebar-->
                    </form>
                </div>
            </div>
        </div>

        </div>
        <!--//main content-->
    </main>
    <!--//main-->
@endsection