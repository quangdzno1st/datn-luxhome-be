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
                       {{-- @foreach($rates as $rate)
                            <li>
                                <figure class="left" style="display: flex; align-items: center">
                                    <img width="100px"
                                         src="data:image/png;base64,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"
                                         alt="avatar"/>
                                    <div style="">
                                        <p style="font-size: 14px; font-weight: bold">{{$rate?->user->name}}</p>
                                        <p>{{ $rate->created_at }}</p>
                                    </div>
                                </figure>
                                <div class="review" style="margin-top: 17px">
                                    <p class="review-content" style="font-size: 14px;">{{ $rate->content }}</p>

                                    <div class="" style="display: flex; align-items: center; margin-right: 10px;">
                                        @for($i = 1; $i<= $rate->rate; ++$i)
                                            <span style="color:yellow; font-size: 20px; font-weight: bold;"
                                                  class="star">&#9733;</span>
                                        @endfor
                                    </div>
                                </div>
                            </li>
                        @endforeach --}}
                    <section id="MyReviews" class="tab-content">
                        @foreach ($rates as $rate)     

                        <article class="myreviews">
                            <h2>Đánh giá về khách sạn {{$rate->hotel->name}}</h2>
                            <div class="reviews" style="padding-left: 2rem">
                                <div class="" style="width:100%">
                                    <p>{{Carbon\Carbon::parse($rate->created_at)->format('H:i:s d/m/Y'); }}</p>
                                </div>
                                <div class="" style="width:100%; font-weight:bold">
                                    <p>{{$rate->content}}</p>
                                </div>
                                <div class="" style="width:100%">
                                    <div class="" style="display: flex; align-items: center; margin-right: 10px;">
                                        @for($i = 1; $i<= $rate->rate; ++$i)
                                            <span style="color:yellow; font-size: 20px; font-weight: bold;"
                                                  class="star">&#9733;</span>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </article>

                        @endforeach
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
