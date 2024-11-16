@extends('client.layouts.master')

@section('content')
    <!--main-->
	<main class="main">		
		<div class="wrap">
			<!--breadcrumbs-->
			<nav class="breadcrumbs">
				<!--crumbs-->
				<ul>
					<li><a href="#" title="Home">Home</a></li>
					<li><a href="#" title="My Account">My Account</a></li>                                    
				</ul>
				<!--//crumbs-->
			</nav>
			<!--//breadcrumbs-->

			<div class="row">
				<!--three-fourth content-->
				<section class="three-fourth">
				
					<h1>Chi tiết đặt phòng</h1>
					
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
					<section id="MyBookings" class="tab-content">
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
                                        <td>Khách sạn ABC</td>
                                    </tr>
                                    <tr>
                                        <td>Ngày check-in</td>
                                        <td>2024-11-18</td>
                                    </tr>
                                    <tr>
                                        <td>Ngày check-out</td>
                                        <td>2024-11-20</td>
                                    </tr>
                                    <tr>
                                        <td>Voucher</td>
                                        <td>Giảm giá 10%</td>
                                    </tr>
                                </table>
                        
                                <h3>Thông Tin Loại Phòng</h3>
                                <table>
                                    <tr>
                                        <th>Loại Phòng</th>
                                        <th>Số Phòng</th>
                                        <th>Giá (VND)</th>
                                    </tr>
                                    <tr>
                                        <td>Deluxe</td>
                                        <td>431, 432</td>
                                        <td>1,500,000</td>
                                    </tr>
                                    <tr>
                                        <td>Standard</td>
                                        <td>433</td>
                                        <td>1,000,000</td>
                                    </tr>
                                </table>
                        
                                <h3>Thông Tin Dịch Vụ</h3>
                                <table>
                                    <tr>
                                        <th>Dịch Vụ</th>
                                        <th>Giá (VND)</th>
                                    </tr>
                                    <tr>
                                        <td>Ăn sáng</td>
                                        <td>200,000</td>
                                    </tr>
                                    <tr>
                                        <td>Đưa đón sân bay</td>
                                        <td>300,000</td>
                                    </tr>
                                </table>
                        
                                <h3>Tổng Tiền</h3>
                                <table>
                                    <tr>
                                        <td>Tổng tiền dịch vụ</td>
                                        <td>500,000 VND</td>
                                    </tr>
                                    <tr>
                                        <td>Tổng tiền đặt phòng</td>
                                        <td>2,500,000 VND</td>
                                    </tr>
                                    <tr>
                                        <td class="total">Tổng tiền thanh toán</td>
                                        <td class="total">3,000,000 VND</td>
                                    </tr>
                                </table>
							</div>
							
							<div class="actions">
								<a href="#" class="gradient-button">Đã đặt</a>
								<a href="#" style="background-color: red" class="gradient-button" >Hủy phòng</a>
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
					<article class="widget">
						<h4>Đánh giá</h4>
						<select name="" id="">
                            <option value="5">Rất tốt</option>
                            <option value="4">Tốt</option>
                            <option value="3">Tạm</option>
                            <option value="2">Kém</option>
                            <option value="1">Rất kém</option>
                        </select>
                        <label for="">Nhận xét</label>
                        <textarea name="" id="" cols="30" rows="10" placeholder="Nhận xét ý kiến của bạn"></textarea>
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