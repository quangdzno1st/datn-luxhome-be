@extends('client.layouts.master')

@section('title')
    Đặt phòng thành công
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
                    <li><a href="{{ route('home.index') }}" title="Home">Trang chủ</a></li>
                    <li><a href="#" title="Hotels">Khách sạn</a></li>
                    <li>Xác nhận thanh toán</li>
                </ul>
                <!--//crumbs-->
            </nav>
            <!--//breadcrumbs-->

            @if($order && $status)
                <div class="row">
                    <!--three-fourth content-->
                    <div class="two-third">
                        <form id="booking" method="post"
                              action="https://www.themeenergy.com/themes/html/book-your-travel/booking"
                              class="static-content booking">
                            <fieldset>
                                <h2><span></span>Xác nhận đơn đặt</h2>
                                <div class="text-wrap">
                                    <p>Cảm ơn. Việc đặt chỗ của bạn hiện đã được xác nhận.</p>
                                </div>

                                <h3>Thông tin đơn đặt</h3>
                                <div class="text-wrap">
                                    <div class="output">
                                        <p>Mã đơn đặt:</p>
                                        <p>{{ $order['code'] }}</p>
                                        <p>Tên người đặt: </p>
                                        <p>{{ $order['name'] }}</p>
                                        <p>Địa chỉ email: </p>
                                        <p> {{$order['email']}} </p>
                                        <p>Số điện thoại: </p>
                                        <p> {{$order['phone']}} </p>
                                    </div>
                                </div>

                                <h3>Yêu cầu đặc biệt</h3>
                                <div class="text-wrap">
                                    <p>{{ $order['note'] }}</p>
                                </div>

                                <h3>Thanh toán</h3>
                                <div class="text-wrap">
                                    <p>Bây giờ bạn đã xác nhận và đảm bảo đặt phòng của mình bằng thẻ tín dụng.
                                        Tất cả các khoản thanh toán sẽ được thực hiện tại khách sạn trong thời gian lưu
                                        trú của bạn,
                                        trừ khi có quy định khác trong chính sách khách sạn hoặc trong điều kiện phòng.
                                        Xin lưu ý rằng thẻ tín dụng của bạn có thể được ủy quyền trước trước đến khi bạn
                                        đến..
                                    </p>
                                </div>

                                <h3>Lưu ý</h3>
                                <div class="text-wrap">
                                    <p>Bạn không thể thay đổi hoặc hủy đặt chỗ của mình. Bạn sẽ không được hoàn tiền khi
                                        hủy chỗ của mình.</p>
                                    <p><strong>Chúng tôi chúc bạn có một kỳ nghỉ vui vẻ tại LUX - HOME</strong></p>
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
                                <p><strong>{{ $order['hotel_name'] }}</strong></p>
                                <p>
                                    <i class="far fa-calendar-alt"></i>
                                    {{ \Carbon\Carbon::parse($order['start_date'])->format('d/m/Y') }}
                                    -&gt;
                                    {{ \Carbon\Carbon::parse($order['end_date'])->format('d/m/Y') }}
                                </p>
                                <p>
                                    <i class="far fa-calendar-alt"></i>
                                    {{ (new DateTime($order['start_date']))->setTime(0,0,0)->diff((new DateTime($order['end_date']))->setTime(0,0,0))->days }}
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

                            <h2 @if(empty($servicesQty))
                                    style="border: none"
                                    @endif>Dịch vụ</h2>
                            @if(!empty($servicesQty))
                                @foreach($servicesQty as $key => $qty)
                                    @php
                                        $serviceInfo = $servicesInfo[$key];
                                        $total_amount += $serviceInfo['price']*$qty;
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

                            @php
                                if ($order['discount_type'] == 1) {
                                    $discountAmount = ($total_amount * $order['discount_value']) / 100;
                                    if ($order['max_price'] > 0 && $discountAmount > $order['max_price']) {
                                         $discountAmount = $order['max_price'];
                                     }
                                } else {
                                  $discountAmount = $order['discount_value'];
                                }
                            $total_amount =  max($total_amount - $discountAmount, 0);

                            @endphp

                            <div class="price">
                                <p class="total">Tổng tiền: {{ number_format($total_amount) .' đ' }}</p>
                            </div>
                    </aside>
                </div>
            @else
                <div class="static-content booking">
                    <h2><span></span>Xác nhận đơn đặt</h2>
                    <div class="text-wrap">
                        @if($order)
                            <p>Có vẻ như đơn đặt của bạn gặp một chút trục trặc, hãy quay lại và thanh toán</p>
                            <a href="{{ route('orders.payment', $order['id']) }}" class="gradient-button">Quay lại thanh
                                toán</a>
                        @else
                            <p>Bạn đang không thanh toán đơn hàng nào, hãy quay lại trang chủ</p>
                            <a href="{{ route('home.index') }}" class="gradient-button">Quay lại trang chủ</a>
                        @endif
                    </div>
                </div>
            @endif
            <!--//main content-->
        </div>
    </main>
    <!--//main-->
@endsection