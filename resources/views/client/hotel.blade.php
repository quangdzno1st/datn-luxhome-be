@extends('client.layouts.master')

@section('content')
    <!--main-->
    <style>

        .text-clamp {
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Giới hạn 2 dòng */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis; /* Thêm dấu ba chấm */
            line-height: 20px;
        }

        .highlight-selected {
            font-weight: bold;
            color: #ffffff;
            background-color: #007bff !important;
        }

        /* Ngày trong khoảng từ Check-in đến Check-out */
        .highlight-range {
            background-color: #6adaf7 !important;
            color: #2086F3 !important;
        }

        .ui-datepicker .ui-datepicker-today {
            background: #ffad7b !important;
        }


        .room-selector {
            margin-bottom: 20px; /* Tạo khoảng cách giữa các phần */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9; /* Màu nền nhẹ nhàng */
        }

        .room-selector label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .select2-container {
            width: 100% !important; /* Đảm bảo Select2 chiếm toàn bộ chiều rộng */
        }

        /* Đảm bảo các phần tử form nằm ngang */
        .form-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        /* Cải thiện form-group để đảm bảo không gian giữa các trường */
        .form-group {
            margin-bottom: 15px;
        }

        /* Căn chỉnh các input fields */
        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        /* Thêm margin cho các input để tạo khoảng cách giữa các phần tử */
        .form-group label {
            font-weight: bold;
        }

        /* Style cho nút submit */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 15px;
            font-size: 16px;
            border-radius: 5px;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* Đảm bảo các phần tử form nằm ngang */
        .form-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        /* Cải thiện form-group để đảm bảo không gian giữa các trường */
        .form-group {
            margin-bottom: 15px;
        }

        /* Căn chỉnh các input fields */
        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        /* Thêm margin cho các input để tạo khoảng cách giữa các phần tử */
        .form-group label {
            font-weight: bold;
        }

        /* Style cho nút submit */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 15px;
            font-size: 16px;
            border-radius: 5px;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }


        /* Cải thiện nút submit */
        .submit {
            display: flex;
            align-items: center; /* Căn giữa chiều dọc */
            justify-content: center; /* Căn giữa chiều ngang */
            margin-top: 23px;
        }

        .submit .btn-primary {
            font-size: 10px; /* Tăng kích thước chữ */
            height: 40px; /* Đặt chiều cao cố định cho nút */
            border-radius: 8px; /* Bo góc cho nút */
        }

        .submit .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            height: 100%; /* Đảm bảo chiều cao đồng nhất */
        }

        .form-group input {
            height: 40px; /* Chiều cao cố định */
            box-sizing: border-box; /* Bao gồm padding và border */
        }

        .form-group .text-danger {
            font-size: 14px; /* Kích thước nhỏ để không ảnh hưởng layout */
            height: 20px; /* Chiều cao cố định cho thông báo lỗi */
            margin-top: 5px; /* Khoảng cách giữa input và lỗi */
            color: red; /* Màu chữ lỗi */
        }

        .submit button {
            height: 40px;
        }

        .alert-box {
            border: 1px solid red;
            border-radius: 5px;
            background-color: #fff5f5;
            padding: 20px;
            display: flex;
            align-items: center;
            max-width: 800px;
            margin: 20px auto;
        }
        .alert-icon {
            color: red;
            font-size: 24px;
            margin-right: 10px;
        }
        .alert-content {
            color: #333;
        }
        .alert-content strong {
            font-size: 16px;
        }
        .alert-content p {
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .facility-list {
            display: block; /* Mỗi mục sẽ nằm trên một dòng */
            margin: 0;
            padding: 0;
        }

        .facility-item {
            padding-left: 20px; /* Khoảng cách bên trái giống như li */
            position: relative;
            margin-bottom: 10px; /* Khoảng cách giữa các mục */
        }

        .facility-item::before {
            content: '\2022'; /* Mã Unicode cho dấu đầu dòng (•) */
            position: absolute;
            left: 0;
            font-size: 20px;
            color: #333; /* Màu sắc của dấu đầu dòng */
            top: 50%;
            transform: translateY(-50%);
        }

    </style>
    <main class="main">
        <div class="wrap">

            <!--breadcrumbs-->
            {{--            <nav class="breadcrumbs">--}}
            {{--                <!--crumbs-->--}}
            {{--                <ul>--}}
            {{--                    <li><a href="#" title="Home">Home</a></li>--}}
            {{--                    <li><a href="#" title="Hotels">Hotels</a></li>--}}
            {{--                    <li><a href="#" title="United Kingdom">United Kingdom</a></li>--}}
            {{--                    <li><a href="#" title="London">London</a></li>--}}
            {{--                    <li>Search results</li>--}}
            {{--                </ul>--}}
            {{--                <!--//crumbs-->--}}

            {{--                <!--top right navigation-->--}}
            {{--                <ul class="top-right-nav">--}}
            {{--                    <li><a href="search_results.html" title="Back to results">Back to results</a></li>--}}
            {{--                    <li><a href="#" title="Change search">Change search</a></li>--}}
            {{--                </ul>--}}
            {{--                <!--//top right navigation-->--}}
            {{--            </nav>--}}
            <!--//breadcrumbs-->

            <div class="">
                <!--hotel three-fourth content-->
                <section class="">
                    <!--gallery-->
                    <div class="gallery">
                        <ul id="image-gallery" class="cS-hidden">
                            @foreach($hotel->images as $image)
                                <li data-thumb="{{  Storage::url($image['path']) }}">
                                    <img src="{{  Storage::url($image['path']) }}" alt=""/>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!--//gallery-->

                    <!--inner navigation-->
                    <nav class="inner-nav">
                        <ul>
                            <li class="availability"><a href="#availability" title="Availability">Tình trạng phòng</a>
                            </li>
                            <li class="description"><a href="#description" title="Description">Mô tả</a></li>
                            <li class="facilities"><a href="#facilities" title="Facilities">Tiện nghi</a></li>
                            {{--                            <li class="location"><a href="#location" title="Location">Vị trí</a></li>--}}
{{--                            <li class="reviews"><a href="#reviews" title="Reviews">Đánh giá</a></li>--}}
                            {{--                            <li class="things-to-do"><a href="#things-to-do" title="Things to do">Hoạt động</a></li>--}}

                        </ul>
                    </nav>
                    <!--//inner navigation-->

                    <!--availability-->
                    <section id="availability" class="tab-content">
                        <article>
                            <h2>Phòng trống</h2>
                            <form id="main-search" method="get" action="{{ route('home.search') }}">
                                <div class="row">
                                    @php
                                    $data = session('search_data')[0] ?? [];
                                    @endphp

                                    <div class="col-md-12">
                                        <div class="form-row align-items-center gap-1">
                                            <div class="form-group col-md-3">
                                                <label for="datepicker1">Ngày bắt đầu</label>
                                                <input type="text" id="datepicker1" name="start_date"
                                                       class="form-control"
                                                       placeholder="Chọn ngày bắt đầu" value="{{ old('start_date') ?? $data['start_date'] }}"/>
                                                @error('start_date')
                                                <div class="text-danger">{{ $message }}</div>
                                                @else
                                                    <div class="text-danger" style="visibility: hidden;">&nbsp;</div>
                                                    @enderror
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="datepicker2">Ngày kết thúc</label>
                                                <input type="text" id="datepicker2" name="end_date" class="form-control"
                                                       placeholder="Chọn ngày kết thúc" value="{{ old('end_date') ?? $data['end_date'] }}"/>
                                                @error('end_date')
                                                <div class="text-danger">{{ $message }}</div>
                                                @else
                                                    <div class="text-danger" style="visibility: hidden;">&nbsp;</div>
                                                    @enderror
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="spinner2">Số người lớn</label>
                                                <input type="number" id="spinner2" name="number_adult"
                                                       class="form-control"
                                                       placeholder="Số  người lớn" value="{{ old('number_adult') ?? $data['number_adult_search'] }}"/>
                                                @error('number_adult')
                                                <div class="text-danger">{{ $message }}</div>
                                                @else
                                                    <div class="text-danger" style="visibility: hidden;">&nbsp;</div>
                                                    @enderror
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="spinner3">Số trẻ em</label>
                                                <input type="number" id="spinner3" name="number_child"
                                                       class="form-control"
                                                       placeholder="Số trẻ em" value="{{ old('number_child') ?? $data['number_child_search'] }}"/>
                                                <div class="text-danger" style="visibility: hidden;">&nbsp;</div>
                                            </div>

                                            <input type="hidden" name="check" value="1">
                                            <input type="hidden" name="hotel_id" value="{{$hotel->id}}">

                                            <div class="form-group col-md-2 submit" >
                                                <button type="submit" class="btn btn-primary w-100">Tiến hành tìm kiếm
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>


                            <h2>Loại phòng</h2>
                            <form action="{{route('ok')}}" method="POST">
                                @csrf
                                <ul class="room-types">

                                    @forelse($filteredData as $key => $data)
                                        <li>
                                            <figure class="left" id="gallery1">
                                                <a href="{{asset('theme/client/images/uploads/room1.jpg')}}"
                                                   data-sub-html="<p>Superior Double Room</p>">
                                                    <img src="{{asset('theme/client/images/uploads/room1.jpg')}}" alt=""/>
                                                    <span class="image-overlay" style="z-index: 0"></span>
                                                </a>
                                                <a href="{{asset('theme/client/images/uploads/room2.jpg')}}"
                                                   data-sub-html="<p>Superior Double Room</p>">
                                                    <img src="{{asset('theme/client/images/uploads/room2.jpg')}}" alt=""/>
                                                </a>
                                            </figure>
                                            <div class="meta">
                                                <h3>{{$data['name']}}</h3>

                                                <div style="display: flex; justify-content: space-between">
                                                    <p class="first">Giá:</p>
                                                    <strong class="second">{{number_format($data['price'])}} VND</strong>
                                                </div>
                                                <div style="margin-bottom: 10px">
                                                    Nhập số lượng phòng:
                                                    <input type="number" id="qty_room_{{$key}}" name=""
                                                           style="margin-top: 10px" class="qty-input"
                                                           placeholder="Số lượng phòng" value="0"/>

                                                    <input type="hidden" id="hidden_qty_{{$key}}" name="{{$data['id']}}"
                                                           value=""/>


                                                    <div id="error-message_{{$key}}" style="color: red; display: none;">Số lượng phòng không được vượt quá số phòng có sẵn!</div>
                                                </div>

                                                <a href="javascript:void(0)" title="more info" class="more-info">+ Xem thêm</a>
                                            </div>
                                            <div class="room-information">
                                                <div class="row">
                                                    <div class="">Người lớn: {{$data['number_adult']}}
{{--                                                        @for ( $i = 1; $i <= $data['number_adult']; $i++)--}}
{{--                                                            <i class="material-icons">&#xE7FD;</i>--}}
{{--                                                        @endfor</div>--}}
                                                </div>

                                                <div class="row " style="margin-top: 10px;">
                                                    <span class="first">Trẻ em: {{$data['number_child']}}</span>
                                                </div>
                                                <div class="row " style="margin-top: 10px;">
                                                    <span class="first">Phòng trống: {{$data['rooms_count']}}</span>
                                                </div>
                                            </div>
                                            <div class="more-information">
                                                @php
                                                    $facilities =  $data['attributeValues']
                                                          ->flatten()
                                                          ->unique()
                                                @endphp
                                                @foreach($facilities as $facilitie)
                                                    <div class="text-wrap">
                                                        <div style="margin-bottom: 10px"><strong>+ Tiện nghi</strong></div>
                                                        <div class="facility-list">
                                                            <div class="facility-item">{{$facilitie->value_text}}</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </li>
                                    @empty
                                        <div class="alert-box">
                                            <i class="fas fa-exclamation-circle alert-icon"></i>
                                            <div class="alert-content">
                                                <strong>Trang web chúng tôi không còn phòng tại chỗ nghỉ này từ ngày {{ request()->start_date }} đến T4, {{request()->end_date}}</strong>
                                                <p>Chọn ngày khác để xem phòng trống</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </ul>

                                <!-- Nút Đặt Ngay -->
                                <div class="submit-btn" style="display: flex; justify-content: center; margin: auto; margin-top: 20px;">
                                    <button type="submit" class="gradient-button" style="padding: 15px; font-size: 18px; width: 200px; height: 50px">Đặt ngay</button>
                                </div>

                            </form>


                        </article>
                    </section>
                    <!--//availability-->

                    <!--description-->
                    <section id="description" class="tab-content">
                        <article>
                            <h2>Mô tả khách sạn</h2>
                            <div class="text-wrap">
                                <p>{!! $hotel->description !!}</p>
                            </div>

                        </article>
                    </section>
                    <!--//description-->

                    <!--facilities-->
                    <section id="facilities" class="tab-content">
                        <article>
                            <h2>Tiện nghi</h2>
                            @php
                                $facilities =   $hotel->catalogues()
                                      ->with('attributeValues') // Lấy thông tin các attribute_value của các loại phòng
                                      ->get()
                                      ->pluck('attributeValues') // Lấy tất cả các giá trị attribute_value từ các catalogue
                                      ->flatten() // Làm phẳng các mảng để có danh sách các attribute_value
                                      ->unique()
                            @endphp
                            @foreach($facilities as $facilitie)
                                <div class="text-wrap">
                                    <ul class="three-col">
                                        <li>{{$facilitie->value_text}}</li>
                                    </ul>
                                </div>
                            @endforeach

                        </article>
                    </section>
                    <!--//facilities-->

                    <!--location-->
                    <section id="location" class="tab-content">
                        <article>
                            <!--map-->
                            <div class="gmap" id="map_canvas"></div>
                            <!--//map-->
                        </article>
                    </section>
                    <!--//location-->

                    <!--reviews-->
                    <section id="reviews" class="tab-content">
                        <article>
                            <h2>Hotel Score and Score Breakdown</h2>
                            <div class="score">
                                <span class="achieved">8 </span>
                                <span> / 10</span>
                                <p class="info">Based on 782 reviews</p>
                                <p class="disclaimer">Guest reviews are written by our customers <strong>after their
                                        stay</strong> at Hotel Best Ipsum.</p>
                            </div>

                            <dl class="chart">
                                <dt>Clean</dt>
                                <dd><span id="data-one" style="width:80%;">8</span></dd>
                                <dt>Comfort</dt>
                                <dd><span id="data-two" style="width:60%;">6</span></dd>
                                <dt>Location</dt>
                                <dd><span id="data-three" style="width:80%;">8</span></dd>
                                <dt>Staff</dt>
                                <dd><span id="data-four" style="width:100%;">10</span></dd>
                                <dt>Services</dt>
                                <dd><span id="data-five" style="width:70%;">7</span></dd>
                                <dt>Value for money</dt>
                                <dd><span id="data-six" style="width:90%;">9</span></dd>
                            </dl>
                        </article>

                        <article>
                            <h2>Guest reviews</h2>
                            <ul class="reviews">
                                <!--review-->
                                <li>
                                    <figure class="left">
                                        <img src="{{asset('theme/client/images/uploads/avatar.jpg')}}" alt="avatar"/>
                                        <address><span>Anonymous</span><br/>Solo TravellerNorway<br/>22/06/2016
                                        </address>
                                    </figure>
                                    <div class="rev pro">
                                        <p>It was a warm friendly hotel. Very easy access to shops and underground
                                            stations. Staff very welcoming.</p>
                                    </div>
                                    <div class="rev con">
                                        <p>noisy neigbourghs spoilt the rather calm environment</p>
                                    </div>
                                </li>
                                <!--//review-->

                                <!--review-->
                                <li>
                                    <figure class="left">
                                        <img src="{{asset('theme/client/images/uploads/avatar.jpg')}}" alt="avatar"/>
                                        <address><span>Anonymous</span><br/>Solo TravellerNorway<br/>22/06/2016
                                        </address>
                                    </figure>
                                    <div class="rev pro">
                                        <p>It was a warm friendly hotel. Very easy access to shops and underground
                                            stations. Staff very welcoming.</p>
                                    </div>
                                    <div class="rev con">
                                        <p>noisy neigbourghs spoilt the rather calm environment</p>
                                    </div>
                                </li>
                                <!--//review-->

                                <!--review-->
                                <li>
                                    <figure class="left">
                                        <img src="{{asset('theme/client/images/uploads/avatar.jpg')}}" alt="avatar"/>
                                        <address><span>Anonymous</span>Solo TravellerNorway22/06/2016</address>
                                    </figure>
                                    <div class="rev pro">
                                        <p>It was a warm friendly hotel. Very easy access to shops and underground
                                            stations. Staff very welcoming.</p>
                                    </div>
                                    <div class="rev con">
                                        <p>noisy neigbourghs spoilt the rather calm environment</p>
                                    </div>
                                </li>
                                <!--//review-->

                                <!--review-->
                                <li>
                                    <figure class="left">
                                        <img src="{{asset('theme/client/images/uploads/avatar.jpg')}}" alt="avatar"/>
                                        <address><span>Anonymous</span><br/>Solo TravellerNorway<br/>22/06/2016
                                        </address>
                                    </figure>
                                    <div class="rev pro">
                                        <p>It was a warm friendly hotel. Very easy access to shops and underground
                                            stations. Staff very welcoming.</p>
                                    </div>
                                    <div class="rev con">
                                        <p>noisy neigbourghs spoilt the rather calm environment</p>
                                    </div>
                                </li>
                                <!--//review-->

                                <!--review-->
                                <li>
                                    <figure class="left">
                                        <img src="{{asset('theme/client/images/uploads/avatar.jpg')}}" alt="avatar"/>
                                        <address><span>Anonymous</span><br/>Solo TravellerNorway<br/>22/06/2016
                                        </address>
                                    </figure>
                                    <div class="rev pro">
                                        <p>It was a warm friendly hotel. Very easy access to shops and underground
                                            stations. Staff very welcoming.</p>
                                    </div>
                                    <div class="rev con">
                                        <p>noisy neigbourghs spoilt the rather calm environment</p>
                                    </div>
                                </li>
                                <!--//review-->
                            </ul>
                        </article>
                    </section>
                    <!--//reviews-->

                    <!--things to do-->
                    <section id="things-to-do" class="tab-content">
                        <article>
                            <h2>London</h2>
                            <figure><img src="{{asset('theme/client/images/uploads/london1.jpg')}}"
                                         alt="Things to do - London general"/>
                            </figure>
                            <p><strong>London is a diverse and exciting city with some of the best sights and
                                    attractions in the world. </strong></p>
                            <p>See London from above on the London Eye; meet a celebrity at Madame Tussauds; examine
                                some of the world’s most precious treasures at the British Museum or come face-to-face
                                with the dinosaurs at the Natural History Museum.</p>

                            <h2>Sports and nature</h2>
                            <figure><img src="{{asset('theme/client/images/uploads/london2.jpg')}}"
                                         alt="Things to do - London Sports and nature"/></figure>
                            <p><strong>London is one of the greenest capitals in the world, with plenty of green and
                                    open spaces. There are more than 3000 open spaces.</strong></p>
                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod
                                tincidunt ut laoreet dolore magna aliquam erat volutpat.Ut wisi enim ad minim veniam,
                                quis nostrud exerci. </p>

                            <h2>Nightlife</h2>
                            <figure><img src="{{asset('theme/client/images/uploads/london3.jpg')}}"
                                         alt="Things to do - London Nightlife"/>
                            </figure>
                            <p><strong>Looking for nightclubs in London? Take a look at our guide to London clubs.
                                    Browse for club ideas, regular club nights and one-off events. </strong></p>
                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod
                                tincidunt ut laoreet dolore magna aliquam erat volutpat.Ut wisi enim ad minim veniam,
                                quis nostrud exerci. </p>

                            <h2>Culture and history</h2>
                            <figure><img src="{{asset('theme/client/images/uploads/london4.jpg')}}"
                                         alt="Things to do - London general"/>
                            </figure>
                            <p><strong>For a display of British pomp and ceremony, watch the Changing the Guard ceremony
                                    outside Buckingham Palace.</strong></p>
                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod
                                tincidunt ut laoreet dolore magna aliquam erat volutpat.Ut wisi enim ad minim veniam,
                                quis nostrud exerci. </p>
                            <hr/>
                            <a href="#" class="gradient-button right" title="Read more">Read more</a>
                        </article>
                    </section>
                    <!--//things to do-->
                </section>
                <!--//hotel content-->

                <!--sidebar-->
                <aside class="one-fourth right-sidebar">
                    <!--hotel details-->
                    {{--                    <article class="hotel-details">--}}
                    {{--                        <h1>{{$hotel->name}}--}}
                    {{--                            <span class="stars">--}}
                    {{--							  @for ($i = 1; $i <= 5; $i++)--}}
                    {{--                                    @if ($i <= $hotel->star)--}}
                    {{--                                        <i class="fa fa-star star-full"></i>--}}
                    {{--                                    @else--}}
                    {{--                                        <i class="fa fa-star-o star-empty"></i>--}}
                    {{--                                    @endif--}}
                    {{--                                @endfor--}}
                    {{--							</span>--}}
                    {{--                        </h1>--}}
                    {{--                        <span class="address">{{$hotel?->city?->name}}</span>--}}
                    {{--                        <span class="rating"> 8 /10</span>--}}
                    {{--                        <div class="description">--}}
                    {{--                            <p>{{ $hotel->description }}</p>--}}
                    {{--                        </div>--}}
                    {{--                        <div class="tags">--}}
                    {{--                            <ul>--}}
                    {{--                                <li><a href="#" title="Wellness">Wellness</a></li>--}}
                    {{--                                <li><a href="#" title="Last minute">Last minute</a></li>--}}
                    {{--                                <li><a href="#" title="Thailand">Thailand</a></li>--}}
                    {{--                                <li><a href="#" title="SPA">SPA</a></li>--}}
                    {{--                                <li><a href="#" title="Romantic">Romantic</a></li>--}}
                    {{--                            </ul>--}}
                    {{--                        </div>--}}
                    {{--                    </article>--}}
                    <!--//hotel details-->

                </aside>
                <!--//sidebar-->
            </div>
            <!--//row-->
        </div>
    </main>
    <!--//main-->
@endsection

@section('style-libs')
    <link rel="stylesheet" href="{{asset('theme/client/css/lightslider.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('theme/client/css/lightgallery.min.css')}}"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('theme/client/css/lightslider.min.css')}}"/>
@endsection

@section('script-libs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{asset('theme/client/js/infobox.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/client/js/lightslider.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/client/js/lightgallery-all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/client/js/lightslider.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            @foreach($filteredData as $key => $data)
            $('#qty_room_{{$key}}').on('input', function() {
                var qty = $(this).val();  // Lấy giá trị số lượng phòng nhập vào
                var availableRooms = {{$data['rooms_count']}};  // Lấy số phòng có sẵn từ server

                // Kiểm tra nếu số lượng phòng nhập vào vượt quá số phòng có sẵn
                if (qty > availableRooms) {
                    $('#error-message_{{$key}}').show();
                } else {
                    $('#error-message_{{$key}}').hide();
                }
            });
            @endforeach

            $('.qty-input').on('input', function () {
                // Lấy giá trị vừa nhập
                let inputValue = $(this).val();

                // Lấy id của input hiện tại
                let inputId = $(this).attr('id');

                let hiddenInputId = inputId.replace('qty_room_', 'hidden_qty_');
                $('#' + hiddenInputId).val(inputValue);
            });

            $('#image-gallery').lightSlider({
                gallery: true,
                item: 1,
                thumbItem: 6,
                slideMargin: 0,
                speed: 500,
                auto: true,
                loop: true,
                onSliderLoad: function () {
                    $('#image-gallery').removeClass('cS-hidden');
                }
            });

            $('#gallery1,#gallery2,#gallery3,#gallery4').lightGallery({
                download: false
            });

            $('.select-room').select2({
                placeholder: 'Chọn phòng',
                allowClear: true,
                width: '100%'
            });


            {{--$('#main-search').on('submit', function (e) {--}}
            {{--    e.preventDefault(); // Ngừng hành động submit mặc định của form--}}

            {{--    // Thu thập dữ liệu từ form--}}
            {{--    var formData = $(this).serialize();--}}

            {{--    // Gửi yêu cầu AJAX--}}
            {{--    $.ajax({--}}
            {{--        url: '{{route('home.search')}}', // URL của form (route)--}}
            {{--        method: 'GET', // Phương thức HTTP (GET hoặc POST)--}}
            {{--        data: formData, // Dữ liệu từ form--}}
            {{--        success: function(response) {--}}
            {{--            const rooms = response.data;--}}

            {{--            let roomListHtml = rooms.map((data, key) => {--}}
            {{--                // Dùng template để render HTML--}}
            {{--                return `--}}
            {{--            <li>--}}
            {{--                <figure class="left" id="gallery1">--}}
            {{--                    <a href="${data.image_url}" data-sub-html="<p>${data.name}</p>">--}}
            {{--                        <img src="${data.image_url}" alt=""/>--}}
            {{--                        <span class="image-overlay"></span>--}}
            {{--                    </a>--}}
            {{--                </figure>--}}
            {{--                <div class="meta">--}}
            {{--                    <h3>${data.name}</h3>--}}
            {{--                    <div style="display: flex; justify-content: space-between">--}}
            {{--                        <p class="first">Price:</p>--}}
            {{--                        <strong class="second">${data.price} VND</strong>--}}
            {{--                    </div>--}}
            {{--                    <div style="margin-bottom: 10px">--}}
            {{--                        Chọn phòng:--}}
            {{--                        <select name="rooms[]" id="rooms_${key}" class="select-room" multiple>--}}
            {{--                            ${data.available_rooms.map(room => `<option value="${room.room_id}">${room.code}</option>`).join('')}--}}
            {{--                        </select>--}}
            {{--                    </div>--}}
            {{--                    <a href="javascript:void(0)" title="more info" class="more-info">+ Xem thêm</a>--}}
            {{--                </div>--}}
            {{--                <div class="room-information">--}}
            {{--                    <div class="row">--}}
            {{--                        <span class="first">Max:</span>--}}
            {{--                        <span class="second">--}}
            {{--                            ${'<i class="material-icons">&#xE7FD;</i>'.repeat(data.number_adult)}--}}
            {{--                        </span>--}}
            {{--                    </div>--}}
            {{--                    <div class="row">--}}
            {{--                        <span class="first">Rooms:</span>--}}
            {{--                        <span class="second">${data.rooms_count}</span>--}}
            {{--                    </div>--}}
            {{--                    <a href="booking-step1.html" class="gradient-button" title="Book">Book now</a>--}}
            {{--                </div>--}}
            {{--                <div class="more-information">--}}
            {{--                    <p>${data.description}</p>--}}
            {{--                </div>--}}
            {{--            </li>--}}
            {{--        `;--}}
            {{--            }).join('');--}}

            {{--            // Cập nhật lại HTML cho danh sách phòng--}}
            {{--            $('#room-types').html(roomListHtml);--}}
            {{--            $('.room-types').html(roomListHtml);--}}

            {{--            // Khởi tạo lại select2 cho các <select> mới--}}
            {{--            $('.select-room').select2({--}}
            {{--                placeholder: 'Chọn phòng',--}}
            {{--                allowClear: true,--}}
            {{--                width: '100%'--}}
            {{--            });--}}
            {{--        },--}}
            {{--        error: function (xhr, status, error) {--}}
            {{--            console.log('Status:', status);--}}
            {{--            console.log('Error:', error);--}}
            {{--            console.log('Response:', xhr.responseText); // Kiểm tra thông tin chi tiết lỗi từ server--}}
            {{--            alert("Có lỗi xảy ra! Vui lòng kiểm tra lại dữ liệu.");--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}

        });

        (function ($) {
            $(document).ready(function () {
                $('.form').hide();
                $('#form1').show();
                $('.f-item:nth-child(1)').addClass('active');
                $('.f-item:nth-child(1) span').addClass('checked');

                $('#hero-gallery').lightSlider({
                    gallery: true,
                    item: 1,
                    pager: false,
                    gallery: false,
                    slideMargin: 0,
                    speed: 2000,
                    pause: 6000,
                    mode: 'fade',
                    auto: true,
                    loop: true,
                    onSliderLoad: function () {
                        $('#hero-gallery').removeClass('cS-hidden');
                    }
                });
            });

            var today = new Date();
            var checkInDate = null;
            var checkOutDate = null;

            // Hàm thêm lớp CSS tùy chỉnh
            function highlightDays(date) {
                if (checkInDate && checkOutDate) {
                    if (date >= checkInDate && date <= checkOutDate) {
                        return [true, "highlight-range"]; // Thêm màu cho khoảng ngày
                    }
                }

                if (checkInDate && date.getTime() === checkInDate.getTime()) {
                    return [true, "highlight-selected"]; // Đánh dấu ngày Check-in
                }

                if (checkOutDate && date.getTime() === checkOutDate.getTime()) {
                    return [true, "highlight-selected"]; // Đánh dấu ngày Check-out
                }

                return [true, ""]; // Ngày không đặc biệt
            }

            // Khởi tạo datepicker cho Check-in
            $("#datepicker1").datepicker({
                dateFormat: "yy-mm-dd",
                minDate: today,
                beforeShowDay: function (date) {
                    if (checkOutDate && date > checkOutDate) {
                        return [false, ""];
                    }
                    return highlightDays(date);
                },
                onSelect: function (selectedDate) {
                    checkInDate = new Date(selectedDate);
                    checkInDate.setHours(0, 0, 0, 0); // Đặt về đầu ngày
                    var minCheckOutDate = new Date(checkInDate);
                    minCheckOutDate.setDate(minCheckOutDate.getDate() + 1);
                    $("#datepicker2").datepicker("option", "minDate", minCheckOutDate);
                    $("#datepicker2").datepicker("refresh"); // Làm mới datepicker
                }
            });

            // Khởi tạo datepicker cho Check-out
            $("#datepicker2").datepicker({
                dateFormat: "yy-mm-dd",
                minDate: new Date(today.getTime() + 24 * 60 * 60 * 1000),
                beforeShowDay: highlightDays,
                onSelect: function (selectedDate) {
                    checkOutDate = new Date(selectedDate);
                    checkOutDate.setHours(0, 0, 0, 0); // Đặt về đầu ngày
                }
            });
        })(jQuery);
    </script>
@endsection