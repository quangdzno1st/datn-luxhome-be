@extends('client.layouts.master')

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
                <section class="three-fourth">

                    <div style="display: flex; justify-content:space-between">
                        <h1>Tài khoản của tôi</h1>
                        @if (session('msg'))
                            <h1 style="color: #19b4ac; font-size:1rem; text-align:right">{{ session('msg') }}</h1>
                        @endif
                        @if (session('error'))
                            <h1 style="color: red; font-size:1rem; text-align:right">{{ session('error') }}</h1>
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


                        @foreach ($orders as $order)
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
                                                background-color: {{ \App\Constant\Enum\StatusOrderEnum::isChuaThanhToan($order['status']) ? '#575145' : '#d5b26b' }}; ">
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
                                    <a href="#" class="gradient-button">Chi tiết đặt phòng</a>
                                    @if (\App\Constant\Enum\StatusOrderEnum::isChuaThanhToan($order['status']))
                                        <a href="{{ route('orders.payment', $order['id']) }}" class="gradient-button">Thanh
                                            toán hóa đơn</a>
                                    @endif
                                </div>
                            </article>
                            <!--//booking-->
                        @endforeach

                        <div class="d-flex justify-content-center">
                            {{ $orders->links() }}
                        </div>
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
                                                    value="{{ !empty($user->name) ? $user->name : '' }}" />
                                                <input type="submit" value="Lưu" onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')" class="gradient-button"
                                                    id="submit1" />
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
                                                    value="{{ !empty($user->email) ? $user->email : '' }}" />
                                                <input type="submit" value="Lưu" onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')" class="gradient-button"
                                                    id="submit2" />
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
                                                    value="{{ !empty($user->phone) ? $user->phone : '' }}" />
                                                <input type="submit" value="Lưu" onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')" class="gradient-button"
                                                    id="submit3" />
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
                                                    value="{{ !empty($user->address) ? $user->address : '' }}" />
                                                <input type="submit" value="Lưu" onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')" class="gradient-button"
                                                    id="submit5" />
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
                                                    value="{{ !empty($user->cccd) ? $user->cccd : '' }}" />
                                                <input type="submit" value="Lưu" onclick="return confirm('Bạn có chắc chắn muốn cập nhật thông tin không?')" class="gradient-button"
                                                    id="submit6" />
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
                                                <input type="password" id="new_password" name="password" />
                                                <label for="new_password">Xác nhận mật khẩu:</label>
                                                <input type="password" id="new_password" name="password_confirmation" />
                                                <input type="submit" value="Lưu" onclick="return confirm('Bạn có chắc chắn muốn thay đổi mật khẩu không?')" class="gradient-button"
                                                    id="submit4" />
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

                <!--sidebar-->
                <aside class="one-fourth right-sidebar">
                    <!--Need Help Booking?-->
                    <article class="widget">
                        <h4>Need Help Booking?</h4>
                        <p>Call our customer services team on the number below to speak to one of our advisors who will
                            help you with all of your holiday needs.</p>
                        <p class="number">1- 555 - 555 - 555</p>
                    </article>
                    <!--//Need Help Booking?-->

                    <!--Why Book with us?-->
                    <article class="widget">
                        <h4>Why Book with us?</h4>
                        <h5>Low rates</h5>
                        <p>Get the best rates, or get a refund.<br>No booking fees. Save money!</p>
                        <h5>Largest Selection</h5>
                        <p>140,000+ hotels worldwide<br>130+ airlines<br>Over 3 million guest reviews</p>
                        <h5>We’re Always Here</h5>
                        <p>Call or email us, anytime<br>Get 24-hour support before, during, and after your trip</p>
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
