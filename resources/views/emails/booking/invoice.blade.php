<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Đặt Phòng Khách Sạn</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }

        .invoice-container {
            background-color: #fff;
            padding: 20px;
            margin: 0 auto;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        header h1 {
            color: #333;
            font-size: 24px;
        }

        section {
            margin-bottom: 20px;
        }

        h2 {
            font-size: 18px;
            color: #555;
            margin-bottom: 10px;
        }

        p, table {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f8f8f8;
        }

        .price-info .total th, .price-info .total td {
            font-size: 18px;
            font-weight: bold;
        }

        footer {
            text-align: center;
            padding-top: 20px;
            font-size: 14px;
            color: #777;
        }

    </style>
</head>
<body>
<div class="invoice-container">
    <header>
        <h1>HÓA ĐƠN ĐẶT PHÒNG KHÁCH SẠN</h1>
        <p>Cảm ơn bạn đã đặt phòng tại khách sạn của chúng tôi!</p>
    </header>

    <section class="hotel-info">
        <h2>Thông tin khách sạn</h2>
        <p>Tên khách sạn: {{ $order['hotel_name'] }}</p>
        <p> {{ $order['location'] }}</p>
        <p>Số điện thoại: {{ $order["hotel_phone"] }}</p>
    </section>

    <section class="guest-info">
        <h2>Thông tin khách hàng</h2>
        <p>Tên khách hàng: {{ $order["name"] }}</p>
        <p>Email: {{ $order["email"] }}</p>
        <p>Số điện thoại: {{ $order['phone'] }}</p>
    </section>

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
                        <td>{{ $order['description'] }}</td>
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
                            <th>Phòng</th>
                            <th>Giá (VND)</th>
                        </tr>
                        @foreach ($services as $service)
                            @php
                                $totalServiceAmount += $service['price'] * $service['room_count'];
                            @endphp
                            <tr>
                                <td>{{ $service['name'] }}</td>
                                <td>{{ $service['room_count'] }}</td>
                                <td>{{ $service['room_codes'] }}</td>
                                <td>{{ number_format($totalServiceAmount) . ' VND' }}</td>
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

    <footer>
        <p>Khách sạn ABC rất mong được đón tiếp quý khách. Chúc bạn có kỳ nghỉ tuyệt vời!</p>
    </footer>
</div>
</body>
</html>
