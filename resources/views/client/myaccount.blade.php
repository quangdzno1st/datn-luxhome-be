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

                    <h1>My account</h1>

                    <!--inner navigation-->
                    <nav class="inner-nav">
                        <ul>
                            <li><a href="#MyBookings" title="My Bookings">Lịch sử đặt phòng</a></li>
                            <li><a href="#MyReviews" title="My Reviews">Lịch sử review</a></li>
                            <li><a href="#MySettings" title="Settings">Cài đặt thông tin</a></li>
                        </ul>
                    </nav>
                    <!--//inner navigation-->

                    <!--My Bookings-->
                    <section id="MyBookings" class="tab-content">
                        <!--booking-->


                        @foreach($orders as $order)
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
                                            <td>{{ \Carbon\Carbon::parse($order['start_date'])->locale('vi')->isoFormat('[Ngày] D [tháng] M [năm] YYYY') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ngày trả phòng</th>
                                            <td>{{ \Carbon\Carbon::parse($order['end_date'])->locale('vi')->isoFormat('[Ngày] D [tháng] M [năm] YYYY') }}</td>
                                        </tr>

                                        <tr>
                                            <th>Trạng thái thanh toán</th>
                                            <td>
                                                <span style="padding: 8px 40px; border-radius: 20px; color: #FFFFFF;
                                                background-color: {{ \App\Constant\Enum\StatusOrderEnum::isChuaThanhToan($order['status']) ? '#575145' : '#d5b26b'}}; ">
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
                                    @if(\App\Constant\Enum\StatusOrderEnum::isChuaThanhToan($order['status']))
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
                                <div class="rev pro"><p>It was a warm friendly hotel. Very easy access to shops and
                                        underground stations. Staff very welcoming.</p></div>
                                <div class="rev con"><p>noisy neigbourghs spoilt the rather calm environment</p></div>
                            </div>
                        </article>

                        <article class="myreviews">
                            <h2>Your review of hotel Lorem ipsum hotel and spa</h2>
                            <div class="score">
                                <span class="achieved">8 </span>
                                <span> / 10</span>
                            </div>
                            <div class="reviews">
                                <div class="rev pro"><p>It was a warm friendly hotel. Very easy access to shops and
                                        underground stations. Staff very welcoming.</p></div>
                                <div class="rev con"><p>noisy neigbourghs spoilt the rather calm environment</p></div>
                            </div>
                        </article>

                        <article class="myreviews">
                            <h2>Your review of hotel Lorem ipsum hotel and spa</h2>
                            <div class="score">
                                <span class="achieved">8 </span>
                                <span> / 10</span>
                            </div>
                            <div class="reviews">
                                <div class="rev pro"><p>It was a warm friendly hotel. Very easy access to shops and
                                        underground stations. Staff very welcoming.</p></div>
                                <div class="rev con"><p>noisy neigbourghs spoilt the rather calm environment</p></div>
                            </div>
                        </article>
                    </section>
                    <!--//MyReviews-->

                    <!--MySettings-->
                    <section id="MySettings" class="tab-content">
                        <article class="mysettings">
                            <h2>Personal details</h2>
                            <table>
                                <tr>
                                    <th>First name:</th>
                                    <td>John
                                        <!--edit fields-->
                                        <div class="edit_field" id="field1">
                                            <label for="new_name">Your new name:</label>
                                            <input type="text" id="new_name"/>
                                            <input type="submit" value="save" class="gradient-button" id="submit1"/>
                                            <a href="#">Cancel</a>
                                        </div>
                                        <!--//edit fields-->
                                    </td>
                                    <td><a href="#field1" class="gradient-button edit">Edit</a></td>
                                </tr>
                                <tr>
                                    <th>Last name:</th>
                                    <td>Livingston
                                        <!--edit fields-->
                                        <div class="edit_field" id="field2">
                                            <label for="new_last_name">Your new name:</label>
                                            <input type="text" id="new_last_name"/>
                                            <input type="submit" value="save" class="gradient-button" id="submit2"/>
                                            <a href="#">Cancel</a>
                                        </div>
                                        <!--//edit fields-->
                                    </td>
                                    <td><a href="#field2" class="gradient-button edit">Edit</a></td>
                                </tr>
                                <tr>
                                    <th>E-mail address:</th>
                                    <td>mail@google.com
                                        <!--edit fields-->
                                        <div class="edit_field" id="field3">
                                            <label for="new_email">Your new email:</label>
                                            <input type="text" id="new_email"/>
                                            <input type="submit" value="save" class="gradient-button" id="submit3"/>
                                            <a href="#">Cancel</a>
                                        </div>
                                        <!--//edit fields-->
                                    </td>
                                    <td><a href="#field3" class="gradient-button edit">Edit</a></td>
                                </tr>
                                <tr>
                                    <th>Password:</th>
                                    <td>*********
                                        <!--edit fields-->
                                        <div class="edit_field" id="field4">
                                            <label for="new_password">Your new password:</label>
                                            <input type="password" id="new_password"/>
                                            <input type="submit" value="save" class="gradient-button" id="submit4"/>
                                            <a href="#">Cancel</a>
                                        </div>
                                        <!--//edit fields-->
                                    </td>
                                    <td><a href="#field4" class="gradient-button edit">Edit</a></td>
                                </tr>
                                <tr>
                                    <th>Street Address and number:</th>
                                    <td>Some street name 55
                                        <!--edit fields-->
                                        <div class="edit_field" id="field5">
                                            <label for="new_address">Your new address:</label>
                                            <input type="text" id="new_address"/>
                                            <input type="submit" value="save" class="gradient-button" id="submit5"/>
                                            <a href="#">Cancel</a>
                                        </div>
                                        <!--//edit fields-->
                                    </td>
                                    <td><a href="#field5" class="gradient-button edit">Edit</a></td>
                                </tr>

                                <tr>
                                    <th>Town / City:</th>
                                    <td>Sunnytown
                                        <!--edit fields-->
                                        <div class="edit_field" id="field6">
                                            <label for="new_city">Your new city:</label>
                                            <input type="text" id="new_city"/>
                                            <input type="submit" value="save" class="gradient-button" id="submit6"/>
                                            <a href="#">Cancel</a>
                                        </div>
                                        <!--//edit fields-->
                                    </td>
                                    <td><a href="#field6" class="gradient-button edit">Edit</a></td>
                                </tr>

                                <tr>
                                    <th>ZIP code:</th>
                                    <td>9500 - 100
                                        <!--edit fields-->
                                        <div class="edit_field" id="field7">
                                            <label for="new_zip">Your new ZIP code:</label>
                                            <input type="text" id="new_zip"/>
                                            <input type="submit" value="save" class="gradient-button" id="submit7"/>
                                            <a href="#">Cancel</a>
                                        </div>
                                        <!--//edit fields-->
                                    </td>
                                    <td><a href="#field7" class="gradient-button edit">Edit</a></td>
                                </tr>

                                <tr>
                                    <th>Country:</th>
                                    <td>Neverland
                                        <!--edit fields-->
                                        <div class="edit_field" id="field8">
                                            <label for="new_country">Your new country:</label>
                                            <input type="text" id="new_country"/>
                                            <input type="submit" value="save" class="gradient-button" id="submit8"/>
                                            <a href="#">Cancel</a>
                                        </div>
                                        <!--//edit fields-->
                                    </td>
                                    <td><a href="#field8" class="gradient-button edit">Edit</a></td>
                                </tr>
                            </table>

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