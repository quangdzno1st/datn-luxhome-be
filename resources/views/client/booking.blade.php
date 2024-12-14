@extends('client.layouts.master')

@section('title')
    Đặt phòng
@endsection

<style>
    .right-sidebar .trip-info div {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
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
                    <li>Thông tin hóa đơn</li>
                </ul>
                <!--//crumbs-->
            </nav>
            <!--//breadcrumbs-->
            <div class="row">
                <!--three-fourth content-->
                <div class="two-third">
                    <form id="booking" method="post" action="{{ route('orders.store') }}"
                          class="static-content booking">
                        @csrf
                        <fieldset>

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ session('error') }}</li>
                                    </ul>
                                </div>
                            @endif

                            <h2>Thông tin hóa đơn</h2>
                            <div class="row">
                                <div class="f-item one-half">
                                    <label for="first_name">Họ Và Tên*</label>
                                    <input type="text" id="first_name" name="user_name" value="{{ old('user_name') }}"/>

                                    @error('user_name')
                                    <div class="text-danger" style="color:red">{{ $message }}</div>
                                    @enderror

                                </div>
                                <div class="f-item one-half">
                                    <label for="last_name">Địa Chỉ Email*</label>
                                    <input type="text" id="last_name" name="user_email"
                                           value="{{ old('user_email') }}"/>

                                    @error('user_email')
                                    <div class="text-danger" style="color:red">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="f-item one-half">
                                    <label for="email">Số Điện Thoại*</label>
                                    <input type="number" id="email" name="user_phone_number"
                                           value="{{ old('user_phone_number') }}"/>

                                    @error('user_phone_number')
                                    <div class="text-danger" style="color:red">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="f-item one-half">
                                    <label for="confirm_email">Mã Phiếu Giảm Giá (Nếu Có)</label>
                                    <select class="select" name="voucher_id">
                                        <option value="0">Vui lòng chọn</option>
                                        @foreach($vouchers as $voucher)
                                            <option value="{{ $voucher['id'] }}">
                                                <div>Giảm giá tối đa</div>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="info"></span>
                            </div>

                            <div class="row">
                                <div class="f-item full-width">
                                    <label>Ghi chú: </label>
                                    <textarea rows="10" cols="10" name="note">{{ old('note') }}</textarea>
                                </div>
                                <span class="info"></span>
                            </div>

                            <div class="row">
                                <div class="f-item full-width">
                                    <input type="submit" class="gradient-button" value="Thanh toán hóa đơn"
                                           id="next-step"/>
                                </div>
                            </div>
                        </fieldset>
                    </form>
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
                            @php
                                $room = $roomBooking[0];
                            @endphp
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

                            @php
                                $total_amount = 0;
                            @endphp
                            @foreach($roomBooking as $room)
                                @php
                                    $total_amount += $room['price'];
                                @endphp
                                <div>
                                    <div style="display: flex; justify-content: space-between; align-items: center">
                                        <h5>{{ $room['code'] }}</h5>
                                        <h6 class="total-cost"> {{number_format($room['price'])}} đ /
                                            đêm</h6>
                                    </div>
                                    <p><i class="fas fa-bed"></i> x1 Phòng suite</p>
                                    <p><i class="fas fa-user"></i> Người lớn: {{ $room['number_adult'] }},
                                        Trẻ
                                        em: {{ $room['number_child'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        <h2>Dịch vụ</h2>
                        @if(!empty($servicesQty))
                            @foreach($servicesQty as $key => $qty)
                                @php
                                    $serviceInfo = $servicesInfo[$key];
                                @endphp
                                <div class="trip-info">
                                    <div class="service-info ">
                                        <span class="service-name"><strong>{{ $serviceInfo['name'] }}</strong></span>
                                        <span class="service-price"><strong>{{ number_format($serviceInfo['price']) }} đ</strong></span>
                                    </div>
                                    <div class="service-quantity">
                                        Số lượng: <span class="quantity-value">{{ $qty }}</span>
                                    </div>
                                    <div class="service-total ">
                                        Thành tiền: <span class="total-value">{{ number_format($qty * $serviceInfo['price']) }} đ</span>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <div class="price">
                            <p class="total">Tổng tiền: {{ number_format($total_amount) .' đ' }}</p>
                        </div>
                </aside>
                <!--//right sidebar-->
            </div>
            <!--//main content-->
        </div>
    </main>
    <!--//main-->
@endsection