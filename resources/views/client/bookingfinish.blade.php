@extends('client.layouts.master')

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
                    <div class="three-fourth">
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

                                <h3>Yêu cầu đặc biện</h3>
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
                    <aside class="one-fourth right-sidebar">
                        <!--Booking details-->
                        <article class="hotel-details booking-details">
                            <h1>{{ $order['hotel_name'] }}
                                <span class="stars">
                                    @for($i = 1; $i <= $order['star']; $i++)
                                        <i class="material-icons">&#xE838;</i>
                                    @endfor
							</span>
                            </h1>
                            <span class="address">{{ $order['district'] . ' • ' . $order['province']}}</span>
                            <span class="rating"> 9 /10</span>
                            <dl class="booking-info">
                                <dt>Ngày bắt đầu: <span
                                            style="font-weight: 500">{{ \Carbon\Carbon::parse($order['start_date'])->locale('vi')->isoFormat(' D [tháng] M [năm] YYYY') }}</span>
                                </dt>
                                <dt>Ngày kết thúc: <span
                                            style="font-weight: 500">{{ \Carbon\Carbon::parse($order['end_date'])->locale('vi')->isoFormat(' D [tháng] M [năm] YYYY') }}</span>
                                </dt>
                            </dl>
                            <div class="price">
                                <p class="total">Tổng tiền: {{ number_format($order['total_amount']) . ' đ' }}</p>
                            </div>
                        </article>
                        <!--//Booking details-->

                        <!--Need Help Booking?-->
                        <article class="widget">
                            <h4>Need Help Booking?</h4>
                            <p>Call our customer services team on the number below to speak to one of our advisors who
                                will help you with all of your holiday needs.</p>
                            <p class="number">1- 555 - 555 - 555</p>
                        </article>
                        <!--//Need Help Booking?-->
                    </aside>
                    <!--//right sidebar-->
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