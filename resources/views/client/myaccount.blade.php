@extends('client.layouts.master')

<style>
    .no-hover:hover {
        pointer-events: none; /* Vô hiệu hóa sự kiện hover */
        color: inherit; /* Giữ nguyên màu văn bản (hoặc thay đổi theo nhu cầu) */
        background-color: inherit; /* Không thay đổi màu nền */
        text-decoration: none; /* Xóa gạch chân nếu cần */
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
                        @if (session('msg'))
                            <h1 style="color: #19b4ac; font-size:1rem; text-align:right">{{ session('msg') }}</h1>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                <ul>
                                    <li>{{session('error')}}</li>
                                </ul>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{{session('success')}}</li>
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!--inner navigation-->
                    <nav class="inner-nav">
                        <ul>
                            <li><a href="#MyBookings" title="My Bookings">Lịch sử đặt phòng</a></li>
                            <li><a href="#MyReviews" title="My Reviews">Lịch sử review</a></li>
                            <li><a href="#MySettings" title="Settings">Cài đặt thông tin</a></li>
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
                                            <th>Trạng thái thanh toán</th>
                                            <td>
                                                <span
                                                        style="padding: 8px 40px; border-radius: 20px; color: #FFFFFF;
                                                background-color: {{ \App\Constant\Enum\StatusOrderEnum::isDangCho($order['status']) ? '#575145' : '#d5b26b' }}; ">
                                                    {{ \App\Constant\Enum\StatusOrderEnum::parse($order['status'])->getName() }}
                                                </span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Tổng tiền</th>
                                            <td><strong>{{ number_format($order['total_amount']) . ' đ' }}</strong></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="actions">
                                    <a href="{{ route('orders.detail', $order['id']) }}" class="gradient-button">Chi tiết đặt phòng</a>
                                    @if ( $order['start_date'] >= \Carbon\Carbon::now() && \App\Constant\Enum\StatusOrderEnum::isDangCho($order['status']))
                                        <a href="{{ route('orders.payment', $order['id']) }}" class="gradient-button">Thanh
                                            toán hóa đơn</a>
                                    @endif

                                    @if( $order['is_requried_cancel'] != 1 && $order['start_date'] > \Carbon\Carbon::now() && \App\Constant\Enum\StatusOrderEnum::isDaXacNhan($order['status']))
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
                                                            sạn {{ $order['hotel_name']}}.
                                                        </p>
                                                        <p>Chính sách hủy phòng: </p>
                                                        <p>Hủy trong vòng từ 2-3 ngày trước ngày nhận phòng: </p>
                                                        <ul>
                                                            <li>
                                                                Phí hủy phòng là 50% tổng số tiền đã thanh toán.
                                                            </li>
                                                            <li>
                                                                Số tiền hoàn trả (nếu có) sẽ được chuyển khoản trong vòng 7 ngày
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
                                                                Nếu bạn cần thay đổi hoặc hủy đặt phòng do lý do bất khả kháng (thiên tai, dịch bệnh, v.v.),
                                                                vui lòng liên hệ bộ phận hỗ trợ của khách sạn để được xem xét và xử lý.
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <a href="{{ route('orders.cancel', $order['id']) }}" type="button" class="btn btn-danger">Xác
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
                        <article class="myreviews">
                            <h2>Your review of hotel Lorem ipsum hotel and spa</h2>
                            <div class="score">
                                <span class="achieved">8 </span>
                                <span> / 10</span>
                            </div>
                            <div class="reviews">
                                <div class="rev pro">
                                    <p>It was a warm friendly hotel. Very easy access to shops and
                                        underground stations. Staff very welcoming.</p>
                                </div>
                                <div class="rev con">
                                    <p>noisy neigbourghs spoilt the rather calm environment</p>
                                </div>
                            </div>
                        </article>

                        <article class="myreviews">
                            <h2>Your review of hotel Lorem ipsum hotel and spa</h2>
                            <div class="score">
                                <span class="achieved">8 </span>
                                <span> / 10</span>
                            </div>
                            <div class="reviews">
                                <div class="rev pro">
                                    <p>It was a warm friendly hotel. Very easy access to shops and
                                        underground stations. Staff very welcoming.</p>
                                </div>
                                <div class="rev con">
                                    <p>noisy neigbourghs spoilt the rather calm environment</p>
                                </div>
                            </div>
                        </article>

                        <article class="myreviews">
                            <h2>Your review of hotel Lorem ipsum hotel and spa</h2>
                            <div class="score">
                                <span class="achieved">8 </span>
                                <span> / 10</span>
                            </div>
                            <div class="reviews">
                                <div class="rev pro">
                                    <p>It was a warm friendly hotel. Very easy access to shops and
                                        underground stations. Staff very welcoming.</p>
                                </div>
                                <div class="rev con">
                                    <p>noisy neigbourghs spoilt the rather calm environment</p>
                                </div>
                            </div>
                        </article>
                    </section>
                    <!--//MyReviews-->

                    <!--MySettings-->
                    <section id="MySettings" class="tab-content">
                        <article class="mysettings">
                            <h2>Thông tin cá nhân</h2>
                            <form action="{{route('client.update.user')}}" method="post">
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
                                                <label for="new_name">Nhập tên mới:</label>
                                                <input type="text" id="new_name" name="name"
                                                       value="{{ !empty($user->name) ? $user->name : '' }}"/>
                                                <input type="submit" value="Lưu"
                                                       onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')"
                                                       class="gradient-button"
                                                       id="submit1"/>
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
                                                <label for="email">Email mới:</label>
                                                <input type="email" id="email" name="email"
                                                       value="{{ !empty($user->email) ? $user->email : '' }}"/>
                                                <input type="submit" value="Lưu"
                                                       onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')"
                                                       class="gradient-button"
                                                       id="submit2"/>
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
                                                <label for="phone">Số điện thoại mới:</label>
                                                <input type="text" id="phone" name="phone"
                                                       value="{{ !empty($user->phone) ? $user->phone : '' }}"/>
                                                <input type="submit" value="Lưu"
                                                       onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')"
                                                       class="gradient-button"
                                                       id="submit3"/>
                                                <a href="#">Hủy</a>
                                            </div>
                                            <!--//edit fields-->
                                        </td>
                                        <td><a href="#field3" class="gradient-button edit">Sửa</a></td>
                                    </tr>
                                    <tr>
                                        <th>Địa chỉ:</th>
                                        <td>{{ !empty($user->address) ? $user->address : '' }}
                                            <!--edit fields-->
                                            <div class="edit_field" id="field5">
                                                <label for="new_address">Địa chỉ mới:</label>
                                                <input type="text" id="new_address" name="address"
                                                       value="{{ !empty($user->address) ? $user->address : '' }}"/>
                                                <input type="submit" value="Lưu"
                                                       onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')"
                                                       class="gradient-button"
                                                       id="submit5"/>
                                                <a href="#">Hủy</a>
                                            </div>
                                            <!--//edit fields-->
                                        </td>
                                        <td><a href="#field5" class="gradient-button edit">Sửa</a></td>
                                    </tr>

                                    <tr>
                                        <th>CCCD:</th>
                                        <td>{{ !empty($user->cccd) ? $user->cccd : '' }}
                                            <!--edit fields-->
                                            <div class="edit_field" id="field6">
                                                <label for="cccd">CCCD mới:</label>
                                                <input type="text" id="cccd" name="cccd"
                                                       value="{{ !empty($user->cccd) ? $user->cccd : '' }}"/>
                                                <input type="submit" value="Lưu"
                                                       onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')"
                                                       class="gradient-button"
                                                       id="submit6"/>
                                                <a href="#">Hủy</a>
                                            </div>
                                            <!--//edit fields-->
                                        </td>
                                        <td><a href="#field6" class="gradient-button edit">Sửa</a></td>
                                    </tr>

                                </table>
                            </form>

                        </article>
                    </section>
                    <!--//MySettings-->

                    <!--MySettings-->
                    <section id="ChangePassword" class="tab-content">
                        <article class="mysettings">
                            <h2>Đổi mật khẩu</h2>
                            <form action="{{route('client.change.password.user')}}" method="post">
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
                                                <label for="new_password">Mật khẩu mới:</label>
                                                <input type="password" id="new_password" name="password"/>
                                                <label for="new_password">Xác nhận mật khẩu:</label>
                                                <input type="password" id="new_password" name="password_confirmation"/>
                                                <input type="submit" value="Lưu"
                                                       onclick="return confirm('Bạn có chắc chắn muốn thay đổi mật khẩu không?')"
                                                       class="gradient-button"
                                                       id="submit4"/>
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
                    <!--//MySettings-->

                </section>
                <!--//three-fourth content-->
            </div>
            <!--//main content-->
        </div>
    </main>
    <!--//main-->
@endsection
