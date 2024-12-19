@extends('client.layouts.master')

@section('title')
    Tài khoản của tôi
@endsection

<style>
    .no-hover:hover {
        pointer-events: none;
        /* Vô hiệu hóa sự kiện hover */
        color: inherit;
        /* Giữ nguyên màu văn bản (hoặc thay đổi theo nhu cầu) */
        background-color: inherit;
        /* Không thay đổi màu nền */
        text-decoration: none;
        /* Xóa gạch chân nếu cần */
    }

    .voucher-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px auto;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .voucher-table thead tr {
        background-color: #007bff;
        color: #fff;
    }

    .voucher-table th,
    .voucher-table td {
        text-align: center;
        padding: 15px;
        border: 1px solid #ddd;
    }

    .voucher-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .voucher-table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .voucher-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 5px;
    }

    .status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: bold;
    }

    .status.active {
        background-color: #28a745;
        color: #fff;
    }

    .status.expired {
        background-color: #dc3545;
        color: #fff;
    }

    .badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75rem;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.375rem;
        color: #fff
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
                    <li><a href="#" title="My Account">Tài khoản của tôi</a></li>
                </ul>
                <!--//crumbs-->
            </nav>
            <!--//breadcrumbs-->

            <div class="row">
                <!--three-fourth content-->
                <section class="">

                    <div style="display: flex; justify-content:space-between">
                        <h1>Tài khoản của tôi</h1>
                        @if (session('error'))
                            <div class="alert alert-danger">
                                <ul>
                                    <li>{{ session('error') }}</li>
                                </ul>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{{ session('success') }}</li>
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!--inner navigation-->
                    <nav class="inner-nav">
                        <ul>
                            <li><a href="#MyBookings" title="My Bookings">Lịch sử đặt phòng</a></li>
                            <li><a href="#MyReviews" title="My Reviews">Lịch sử đánh giá</a></li>
                            <li><a href="#MySettings" title="Settings">Cài đặt thông tin</a></li>
                            <li><a href="#MyVouchers" title="Voucher">Tất cả voucher của bạn</a></li>
                            <li><a href="#ChangePassword" title="Change Password">Đổi mật khẩu</a></li>
                        </ul>
                    </nav>
                    <!--//inner navigation-->

                    <!--My Bookings-->
                    <section id="MyBookings" class="tab-content">
                        <!--booking-->
                        @forelse($orders as $order)
                            <article class="bookings">
                                <h2><a href="#">{{ $order['hotel_name'] }}</a></h2>
                                <div class="b-info">
                                    <table>
                                        <tr>
                                            <th>Mã đơn đặt</th>
                                            <td>{{ $order['code'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ngày đặt phòng</th>
                                            <td>{{ \Carbon\Carbon::parse($order['start_date'])->locale('vi')->isoFormat('[Ngày] D [tháng] M [năm] YYYY') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ngày trả phòng</th>
                                            <td>{{ \Carbon\Carbon::parse($order['end_date'])->locale('vi')->isoFormat('[Ngày] D [tháng] M [năm] YYYY') }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Trạng thái đơn hàng</th>
                                            <td>
                                                <a href="{{ route('orders.detail', $order['id']) }}"
                                                    style="padding: 5px 20px; border-radius: 20px; color: #FFFFFF; background-color: {{ \App\Constant\Enum\StatusOrderEnum::parse($order['status'])->getStatusColor() }};">
                                                    {{ \App\Constant\Enum\StatusOrderEnum::parse($order['status'])->getName() }}
                                                </a>

                                                @if ($order['status'] == 3 && $order['status_payment'] == 2 && $order['is_rating'] == 2)
                                                    <a href="{{ route('orders.detail', $order['id']) }}"
                                                        style="margin: 5px;padding: 5px 20px; border-radius: 20px; color: #FFFFFF;
                                                    background-color: rgb(239, 202, 15)">
                                                        Chưa đánh giá
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Trạng thái thanh toán</th>
                                            <td>
                                                <a href="{{ route('orders.detail', $order['id']) }}"
                                                    style="padding: 5px 20px; border-radius: 20px; color: #FFFFFF;
                                                background-color: {{ \App\Constant\Enum\StatusPaymentOrderEnum::isChuaThanhToan($order['status_payment']) ? '#575145' : 'green' }}; ">
                                                    {{ \App\Constant\Enum\StatusPaymentOrderEnum::parse($order['status_payment'])->getName() }}
                                                </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Tổng tiền</th>

                                            @php
                                                $total_amount = $order['total_amount'];
                                                if ($order['discount_type'] == 1) {
                                                    $discountAmount = ($total_amount * $order['discount_value']) / 100;
                                                    if (
                                                        $order['max_price'] > 0 &&
                                                        $discountAmount > $order['max_price']
                                                    ) {
                                                        $discountAmount = $order['max_price'];
                                                    }
                                                } else {
                                                    $discountAmount = $order['discount_value'];
                                                }
                                                $total_amount = max($total_amount - $discountAmount, 0);

                                            @endphp
                                            <td><strong>{{ number_format($total_amount) . ' VND' }}</strong></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="actions">
                                    <a href="{{ route('orders.detail', $order['id']) }}" class="gradient-button">Chi
                                        tiết đặt phòng</a>
                                    @if ($order['start_date'] >= \Carbon\Carbon::now() && \App\Constant\Enum\StatusOrderEnum::isDangCho($order['status']))
                                        <a href="{{ route('orders.payment', $order['id']) }}" class="gradient-button">Thanh
                                            toán hóa đơn</a>
                                    @endif

                                    @if (
                                        $order['is_requried_cancel'] != 1 &&
                                            $order['start_date'] > \Carbon\Carbon::now() &&
                                            \App\Constant\Enum\StatusOrderEnum::isDaXacNhan($order['status']))
                                        <a href="#" class="gradient-button" data-bs-toggle="modal"
                                            data-bs-target="#myModal">
                                            Hủy đơn
                                        </a>

                                        <!-- The Modal -->
                                        <div class="modal fade" id="myModal">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hủy đơn đặt phòng</h4>
                                                        <a type="button" class="btn-close" data-bs-dismiss="modal"></a>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <p>
                                                            Yêu cầu hủy đặt phòng sẽ được gửi đến chủ khách
                                                            sạn {{ $order['hotel_name'] }}.
                                                        </p>
                                                        <p>Chính sách hủy phòng: </p>
                                                        <p>Hủy trong vòng từ 2-3 ngày trước ngày nhận phòng: </p>
                                                        <ul>
                                                            <li>
                                                                Phí hủy phòng là 50% tổng số tiền đã thanh toán.
                                                            </li>
                                                            <li>
                                                                Số tiền hoàn trả (nếu có) sẽ được chuyển khoản trong
                                                                vòng 7 ngày
                                                                làm việc.
                                                            </li>
                                                        </ul>
                                                        <p>
                                                            Hủy trong vòng 1 ngày trước ngày nhận phòng hoặc không đến:
                                                        </p>
                                                        <ul>
                                                            <li>Không hoàn trả bất kỳ khoản thanh toán nào.</li>
                                                        </ul>
                                                        <p>
                                                            Trường hợp đặc biệt:
                                                        </p>
                                                        <ul>
                                                            <li>
                                                                Nếu bạn cần thay đổi hoặc hủy đặt phòng do lý do bất khả
                                                                kháng (thiên tai, dịch bệnh, v.v.),
                                                                vui lòng liên hệ bộ phận hỗ trợ của khách sạn để được
                                                                xem xét và xử lý.
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <a href="{{ route('orders.cancel', $order['id']) }}" type="button"
                                                            class="btn btn-danger">Xác
                                                            nhận hủy
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </article>
                            <!--//booking-->
                        @empty
                            <article class="bookings">
                                <h3 style="text-align: center">Bạn chưa có đơn đặt nào!</h3>
                            </article>
                        @endforelse
                    </section>
                    <!--//My Bookings-->

                    <!--MyReviews-->
                    <section id="MyReviews" class="tab-content">
                        @forelse ($rates as $rate)
                            <article class="myreviews">
                                <h2>Đánh giá về khách sạn {{ $rate->hotel->name }}</h2>
                                <div class="reviews" style="padding-left: 2rem; margin-bottom: 10px">
                                    <div class="" style="width:100%">
                                        <p>
                                            <span style="font-weight:bold">Thời gian</span>:
                                            {{ Carbon\Carbon::parse($rate->created_at)->format('H:i:s d-m-Y') }}
                                        </p>
                                    </div>
                                    <div class="" style="width:100%; margin: 5px 0">
                                        <span style="font-weight:bold">Đánh giá</span>:
                                        <span class="badge"
                                            style="background-color: {{ App\Models\Rate::RATE[$rate->rate][1] }}">
                                            {{ App\Models\Rate::RATE[$rate->rate][0] }}
                                        </span>
                                    </div>
                                    <div class="" style="width:100%;">
                                        <p><span style="font-weight:bold">Nội dung</span>: {{ $rate->content }}</p>
                                    </div>
                                </div>
                                @if (!empty($rate->comment))
                                    <div style="margin-left: 2rem; border-radius:10px; background-color:#F0F0F0">
                                        <div style="padding-left: 1rem; font-weight: bold">Phản hồi của khách sạn:</div>
                                        <p style="padding-left: 1rem">{{ $rate->comment->content }}</p>
                                    </div>
                                @endif
                            </article>

                        @empty
                            <article class="myreviews">
                                <h3 style="text-align: center">Bạn chưa có đánh giá nào!</h3>
                            </article>
                        @endforelse
                    </section>
                    <!--//MyReviews-->

                    <!--MySettings-->
                    <section id="MySettings" class="tab-content">
                        <article class="mysettings">
                            <h2 style="display: flex; justify-content:space-between; align-items:center">
                                <span>Thông tin cá nhân</span>
                                <div>
                                    <div>Tiền đã chi: {{ number_format($user->total_amount_ordered, 0, ',', '.') }}VND
                                    </div>
                                    <div>Hạng: {{ $rank }}</div>
                                </div>
                            </h2>
                            <form action="{{ route('client.update.user') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <table>
                                    <tr>
                                        <th>Họ tên:</th>
                                        <td>{{ !empty($user->name) ? $user->name : '' }}
                                            @error('name')
                                                <p style="color: red">{{ $message }}</p>
                                            @enderror
                                            <!--edit fields-->
                                            <div class="edit_field" id="field1">
                                                <label for="new_name">Nhập tên mới<span class="text-danger">*</span></label>
                                                <input type="text" id="new_name" name="name"
                                                    value="{{ !empty($user->name) ? $user->name : '' }}" />
                                                <input type="submit" value="Lưu"
                                                    onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')"
                                                    class="gradient-button" id="submit1" />
                                                <a href="#">Hủy</a>
                                            </div>
                                            <!--//edit fields-->
                                        </td>
                                        <td><a href="#field1" class="gradient-button edit">Sửa</a></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ !empty($user->email) ? $user->email : '' }}
                                            @error('email')
                                                <p style="color: red">{{ $message }}</p>
                                            @enderror
                                            <!--edit fields-->
                                            <div class="edit_field" id="field2">
                                                <label for="email">Email mới<span class="text-danger">*</span></label>
                                                <input type="email" id="email" name="email"
                                                    value="{{ !empty($user->email) ? $user->email : '' }}" />
                                                <input type="submit" value="Lưu"
                                                    onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')"
                                                    class="gradient-button" id="submit2" />
                                                <a href="#">Hủy</a>
                                            </div>
                                            <!--//edit fields-->
                                        </td>
                                        <td><a href="#field2" class="gradient-button edit">Sửa</a></td>
                                    </tr>
                                    <tr>
                                        <th>Số điện thoại:</th>
                                        <td>{{ !empty($user->phone) ? $user->phone : '' }}
                                            @error('phone')
                                                <p style="color: red">{{ $message }}</p>
                                            @enderror
                                            <!--edit fields-->
                                            <div class="edit_field" id="field3">
                                                <label for="phone">Số điện thoại mới<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" id="phone" name="phone"
                                                    value="{{ !empty($user->phone) ? $user->phone : '' }}" />
                                                <input type="submit" value="Lưu"
                                                    onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')"
                                                    class="gradient-button" id="submit3" />
                                                <a href="#">Hủy</a>
                                            </div>
                                            <!--//edit fields-->
                                        </td>
                                        <td><a href="#field3" class="gradient-button edit">Sửa</a></td>
                                    </tr>

                                    <tr>
                                        <th>Ảnh đại diện:</th>
                                        <td><img width="100px"
                                                src="{{ !empty($user->avatar) ? \Storage::url($user->avatar) : asset('theme/client/images/uploads/avatar.jpg') }}"
                                                alt="helo">
                                            <!--edit fields-->
                                            <div class="edit_field" id="field7">
                                                <label for="avatar">Ảnh đại diện mới:</label> <br>
                                                <input type="file" id="avatar" name="avatar" /> <br>
                                                <input type="submit" value="Lưu"
                                                    onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')"
                                                    class="gradient-button" id="submit7" />
                                                <a href="#">Hủy</a>
                                            </div>
                                            <!--//edit fields-->
                                        </td>
                                        <td><a href="#field7" class="gradient-button edit">Sửa</a></td>
                                    </tr>

                                    <tr>
                                        <th>Địa chỉ:</th>
                                        <td>{{ !empty($user->address) ? $user->address : '' }}
                                            <!--edit fields-->
                                            <div class="edit_field" id="field5">
                                                <label for="new_address">Địa chỉ mới:</label>
                                                <input type="text" id="new_address" name="address"
                                                    value="{{ !empty($user->address) ? $user->address : '' }}" />
                                                <input type="submit" value="Lưu"
                                                    onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')"
                                                    class="gradient-button" id="submit5" />
                                                <a href="#">Hủy</a>
                                            </div>
                                            <!--//edit fields-->
                                        </td>
                                        <td><a href="#field5" class="gradient-button edit">Sửa</a></td>
                                    </tr>


                                </table>
                            </form>

                        </article>
                    </section>
                    <section id="MyVouchers" class="tab-content">
                        <article class="mysettings" style="overflow: auto">
                            <h2>Danh sách Voucher của tôi</h2>
                            <table class="voucher-table">
                                <thead style="white-space: nowrap">
                                    <tr>
                                        <th>Hình ảnh</th>
                                        <th>Mã Voucher</th>
                                        <th>Mô tả</th>
                                        <th>Từ ngày</th>
                                        <th>Đến ngày</th>
                                        <th>Giảm tối đa</th>
                                        <th>Tổng tiền đơn có thể sử dụng</th>
                                        <th>Trạng thái</th>
                                        <th>Giảm giá</th>

                                    </tr>
                                </thead>
                                <tbody style="white-space: nowrap">
                                    @foreach ($vouchers as $voucher)
                                        <tr>
                                            <td>
                                                <img src="{{ $voucher->thumbnail ? \Storage::url($voucher->thumbnail) : asset('theme/client/images/default-thumbnail.jpg') }}"
                                                    alt="Voucher Thumbnail" class="voucher-thumbnail">
                                            </td>
                                            <td>{{ $voucher->code }}</td>
                                            <td>{{ $voucher->description }}</td>

                                            <td>{{ !empty($voucher->start_date) ? \Carbon\Carbon::parse($voucher->start_date)->format('d/m/Y') : null }}
                                            </td>
                                            <td>{{ !empty($voucher->end_date) ? \Carbon\Carbon::parse($voucher->end_date)->format('d/m/Y') : null }}
                                            </td>
                                            <td>{{ number_format($voucher->max_price, 0, ',', '.') }}</td>
                                            <td>{{ number_format($voucher->conditional_total_amount, 0, ',', '.') }}</td>

                                            <td>
                                                @php
                                                    $currentDate = \Carbon\Carbon::today();
                                                    $startDate = \Carbon\Carbon::parse($voucher->start_date);
                                                    $endDate = \Carbon\Carbon::parse($voucher->end_date);
                                                @endphp

                                                <span
                                                    class="status
        {{ $voucher->status && $currentDate->between($startDate, $endDate) ? 'active' : 'expired' }}">
                                                    @if ($currentDate->between($startDate, $endDate))
                                                        Khả dụng
                                                    @elseif ($currentDate->isBefore($startDate))
                                                        Chưa khả dụng
                                                    @else
                                                        Hết hạn
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                @if ($voucher->discount_type)
                                                    {{ $voucher->discount_value }}%
                                                @else
                                                    {{ number_format($voucher->discount_value, 0, ',', '.') }}VNĐ
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </article>
                    </section>

                    <!--//MySettings-->

                    <!--MyPassword-->
                    <section id="ChangePassword" class="tab-content">
                        <article class="mysettings">
                            <h2>Đổi mật khẩu</h2>
                            <form action="{{ route('client.change.password.user') }}" method="post">
                                @csrf
                                <table>
                                    <tr>
                                        <th>Mật khẩu:</th>
                                        <td>*********
                                            @error('password')
                                                <p style="color: red">{{ $message }}</p>
                                            @enderror
                                            <!--edit fields-->
                                            <div class="edit_field" id="field4">
                                                <label for="old_password">Mật khẩu cũ<span
                                                        class="text-danger">*</span></label>
                                                <input type="password" id="old_password" name="old_password" />
                                                <label for="new_password">Mật khẩu mới<span
                                                        class="text-danger">*</span></label>
                                                <input type="password" id="new_password" name="password" />
                                                <label for="new_password">Xác nhận mật khẩu<span
                                                        class="text-danger">*</span></label>
                                                <input type="password" id="new_password" name="password_confirmation" />
                                                <input type="submit" value="Lưu"
                                                    onclick="return confirm('Bạn có chắc chắn muốn thay đổi mật khẩu không?')"
                                                    class="gradient-button" id="submit4" />
                                                <a href="#">Hủy</a>
                                            </div>
                                            <!--//edit fields-->
                                        </td>
                                        <td><a href="#field4" class="gradient-button edit">Sửa</a></td>
                                    </tr>

                                </table>
                            </form>

                        </article>
                    </section>
                    <!--//MyPassword-->

                </section>
                <!--//three-fourth content-->
            </div>
            <!--//main content-->
        </div>
    </main>
    <!--//main-->
@endsection
