@extends('client.layouts.master')

@section('title')
    Chi tiết đơn hàng
@endsection

@section('content')
    <!--main-->
    <main class="main">
        <div class="wrap">
            <!--breadcrumbs-->
            <nav class="breadcrumbs">
                <!--crumbs-->
                <ul>
                    <li><a href="{{route('home.index')}}" title="Home">Trang chủ</a></li>
                    <li><a href="{{route('orders.index')}}" title="My Account">Tài khoản của tôi</a></li>
                    <li><a href="#" title="My Account">Chi tiết đơn hàng</a></li>
                </ul>
                <!--//crumbs-->
            </nav>
            <!--//breadcrumbs-->
            @if (session('success'))
                <div class="alert alert-success" style="display: flex;">
                    <ul>
                        <li>{{session('success')}}</li>
                    </ul>
                </div>
            @endif
            <div class="row">
                <!--three-fourth content-->
                <section class="three-fourth">

                    <!--inner navigation-->
                    {{-- <nav class="inner-nav">
                        <ul>
                            <li><a href="#MyBookings" title="My Bookings">My Bookings</a></li>
                            <li><a href="#MyReviews" title="My Reviews">My Reviews</a></li>
                            <li><a href="#MySettings" title="Settings">Settings</a></li>
                        </ul>
                    </nav> --}}
                    <!--//inner navigation-->

                    <!--My Bookings-->
                    <section id="MyBookings" class="tab-content" style="width:100%">
                        <!--booking-->
                        <article class="bookings">
                            <h2>Chi Tiết Đặt Phòng</h2>
                            <div class="b-info">


                                <table>
                                    <tr>
                                        <th>Mục</th>
                                        <th>Thông Tin</th>
                                    </tr>
                                    <tr>
                                        <td>Tên khách sạn</td>
                                        <td>{{ $order['hotel_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Mã đơn</td>
                                        <td>{{ $order['code'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Ngày đặt phòng</td>
                                        <td>{{ \Carbon\Carbon::parse($order['start_date'])->locale('vi')->isoFormat('[Ngày] D [tháng] M [năm] YYYY') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ngày trả phòng</td>
                                        <td> {{ \Carbon\Carbon::parse($order['end_date'])->locale('vi')->isoFormat('[Ngày] D [tháng] M [năm] YYYY') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Voucher</td>
                                        <td>{{ $order['voucher_description'] }}</td>
                                    </tr>
                                </table>

                                <h3>Thông Tin Loại Phòng</h3>
                                @php
                                    $totalRoomAmount = 0;
                                @endphp
                                <table>
                                    <tr>
                                        <th>Loại Phòng</th>
                                        <th>Số Phòng</th>
                                        <th>Giá (VND)</th>
                                    </tr>
                                    @foreach ($catalogueRooms as $catalogueRoom)
                                        @php
                                            $totalRoomAmount += $catalogueRoom['total_price'];
                                        @endphp
                                        <tr>
                                            <td>{{ $catalogueRoom['name'] }}</td>
                                            <td> {{ $catalogueRoom['room_names'] }}</td>
                                            <td>{{ number_format($catalogueRoom['total_price']) . ' đ' }}</td>
                                        </tr>
                                    @endforeach
                                </table>

                                @if (!empty($services))
                                    <h3>Thông Tin Dịch Vụ</h3>
                                    @php
                                        $totalServiceAmount = 0;
                                    @endphp
                                    <table>
                                        <tr>
                                            <th>Dịch Vụ</th>
                                            <th>Số lượng</th>
                                            <th>Giá (VND)</th>
                                        </tr>
                                        @foreach ($services as $service)
                                            @php
                                                $totalServiceAmount += $service['total_price'];
                                            @endphp
                                            <tr>
                                                <td>{{ $service['name'] }}</td>
                                                <td>{{ $service['service_quantity'] }}</td>
                                                <td>{{ number_format($service['total_price']) . ' đ' }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif

                                <h3>Tổng Tiền</h3>
                                <table>
                                    @if (!empty($service))
                                        <tr>
                                            <td>Tổng tiền dịch vụ</td>
                                            <td>
                                                {{ number_format($totalServiceAmount) . ' đ' }}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>Tổng tiền đặt phòng</td>
                                        <td> {{ number_format($totalRoomAmount) . ' đ' }} </td>
                                    </tr>
                                    <tr>
                                        <td>Tổng tiền được giảm</td>
                                        @php
                                            $totalAmount = ($totalServiceAmount ?? 0) + $totalRoomAmount;
                                            if ($order['discount_type'] == 1) {
                                                $discountAmount = ($totalAmount * $order['discount_value']) / 100;
                                                if ($order['max_price'] > 0 && $discountAmount > $order['max_price']) {
                                                     $discountAmount = $order['max_price'];
                                                 }
                                            } else {
                                              $discountAmount = $order['discount_value'];
                                            }
                                            $discountAmount = min($discountAmount, $totalAmount);
                                        $total_amount =  max($totalAmount - $discountAmount, 0);

                                        @endphp
                                        <td> {{ number_format($discountAmount) . ' VND' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="total">Tổng tiền thanh toán</td>
                                        <td class="total">
                                            {{ number_format($total_amount) . ' VND' }}
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="actions">
                                <a href="{{ route('orders.index') }}" class="gradient-button">Quay lại</a>
                            </div>
                        </article>
                        <!--//booking-->

                    </section>
                    <!--//My Bookings-->


                </section>
                <!--//three-fourth content-->

                <!--sidebar-->
                <aside class="one-fourth right-sidebar">
                    <!--Need Help Booking?-->
                    @if ($order['status'] == 3 && $order['status_payment'] == 2 && $order['is_rating'] == 2)
                        <article class="widget">
                            <h4>Đánh giá</h4>
                            <form action="{{ route('client.rating', $order['id']) }}" method="post">
                                @csrf
                                <select name="rate">
                                    <option value="5">Rất Hài Lòng</option>
                                    <option value="4">Hài Lòng</option>
                                    <option value="3">Trung Bình</option>
                                    <option value="2">Kém</option>
                                    <option value="1">Rất Kém</option>
                                </select>
                                <input type="hidden" name="hotel_id" value="{{ $order['org_id'] }}">
                                <textarea style="margin-top: 10px" name="content" cols="30" rows="10"
                                          placeholder="Nhận xét ý kiến của bạn"></textarea>
                                <button style="margin-top: 10px; border:none" type="submit">Đánh giá</button>
                            </form>
                        </article>
                    @endif
                    <!--//Need Help Booking?-->

                    <!--Why Book with us?-->
                    <article class="widget">
                        <h4>Tại sao đặt chỗ với chúng tôi?</h4>
                        <h5>Lựa chọn lớn nhất</h5>
                        <p>Hơn 140.000 khách sạn trên toàn thế giới<br>
                            Hơn 130 hãng hàng không<br>
                            Hơn 3 triệu lượt đánh giá của khách</p>
                        <h5>Chúng tôi luôn ở đây</h5>
                        <p>Gọi điện hoặc gửi email cho chúng tôi bất cứ lúc nào

                            Nhận hỗ trợ 24 giờ trước, trong và sau chuyến đi của bạn</p>
                    </article>
                    <!--//Why Book with us?-->

                </aside>
                <!--//sidebar-->
            </div>
            <!--//main content-->
        </div>
    </main>
    <!--//main-->
@endsection
