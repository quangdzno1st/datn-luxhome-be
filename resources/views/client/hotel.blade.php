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

        .review {
            font-family: Arial, sans-serif;
            margin-bottom: 20px;
        }

        .review-content {
            font-size: 16px;
            margin-left: 10px; /* Để có khoảng cách giữa sao và nội dung */
        }

        .rating {
            font-size: 30px; /* Tăng kích thước sao */
            color: #FFD700; /* Màu vàng cho sao */
        }

        .star {
            margin-right: 5px;
            font-weight: bold; /* Làm sao đậm */
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

            <div class="row">
                <!--hotel three-fourth content-->
                <section class="three-fourth">
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


                </section>
                <!--//hotel content-->

                <!--sidebar-->
                <aside class="one-fourth right-sidebar">
                    <!--hotel details-->
                    <article class="hotel-details">
                        <h1>{{$hotel->name}}
                            <span class="stars" style="margin-top: 5px">
                        							  @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $hotel->star)
                                        <i class="fa fa-star star-full"></i>
                                    @else
                                        <i class="fa fa-star-o star-empty"></i>
                                    @endif
                                @endfor
                        							</span>
                        </h1>
                        <div class="address" style="width: 190px; margin-top: 10px">{{$hotel->location}}</div>
                        <span class="rating"> 8 /10</span>
                        <div class="description">
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($hotel->description)), 100) }}</p>


                        </div>
                        <div class="tags">
                            <ul>
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
                                            <li style="width: 90%">{{$facilitie->content}}</li>
                                        </ul>
                                    </div>
                                @endforeach

                            </ul>
                        </div>
                    </article>
                    <!--//hotel details-->

                    <!--testimonials-->
                    {{--                    <article class="testimonials">--}}
                    {{--                        <blockquote>Loved the staff and the location was just amazing... Perfect!”</blockquote>--}}
                    {{--                        <span class="name">- Jane Doe, Solo Traveller</span>--}}
                    {{--                    </article>--}}
                    {{--                    <!--//testimonials-->--}}

                    {{--                    <!--Need Help Booking?-->--}}
                    {{--                    <article class="widget">--}}
                    {{--                        <h4>Need Help Booking?</h4>--}}
                    {{--                        <p>Call our customer services team on the number below to speak to one of our advisors who will--}}
                    {{--                            help you with all of your holiday needs.</p>--}}
                    {{--                        <p class="number">1- 555 - 555 - 555</p>--}}
                    {{--                    </article>--}}
                    <!--//Need Help Booking?-->

                </aside>
                <!--//sidebar-->
            </div>

            <nav class="inner-nav">
                <ul>
                    <li class="availability"><a href="#availability" title="Availability">Tình trạng phòng</a>
                    </li>
                    <li class="description"><a href="#description" title="Description">Mô tả</a></li>
                    <li class="facilities"><a href="#facilities" title="Facilities">Tiện nghi</a></li>
                    <li class="location"><a href="#location" title="Location">Vị trí</a></li>
                    <li class="reviews"><a href="#reviews" title="Reviews">Đánh giá</a></li>
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
                                               placeholder="Chọn ngày bắt đầu"
                                               value="{{ old('start_date') ?? $data['start_date'] }}"/>
                                        @error('start_date')
                                        <div class="text-danger">{{ $message }}</div>
                                        @else
                                            <div class="text-danger" style="visibility: hidden;">&nbsp;</div>
                                            @enderror
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="datepicker2">Ngày kết thúc</label>
                                        <input type="text" id="datepicker2" name="end_date" class="form-control"
                                               placeholder="Chọn ngày kết thúc"
                                               value="{{ old('end_date') ?? $data['end_date'] }}"/>
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
                                               placeholder="Số  người lớn"
                                               value="{{ old('number_adult') ?? $data['number_adult_search'] }}"/>
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
                                               placeholder="Số trẻ em"
                                               value="{{ old('number_child') ?? $data['number_child_search'] }}"/>
                                        <div class="text-danger" style="visibility: hidden;">&nbsp;</div>
                                    </div>

                                    <input type="hidden" name="check" value="1">
                                    <input type="hidden" name="hotel_id" value="{{$hotel->id}}">

                                    <div class="form-group col-md-2 submit">
                                        <button type="submit" class="btn btn-primary w-100">Tiến hành tìm kiếm
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @if ($errors->any() && !($errors->has('start_date') || $errors->has('end_date')|| $errors->has('number_adult')))
                        <div class="alert alert-danger">
                            <ul>
                                <li>Số lượng đặt phòng không thể để trống</li>
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            <ul>
                                <li>{{session('error')}}</li>
                            </ul>
                        </div>
                    @endif

                    <h2>Loại phòng</h2>
                    <form action="{{route('orders.services')}}" method="GET">
                        @csrf
                        <ul class="room-types">
                            @php
                                $allRoomsEmpty = collect($filteredData)->every(fn($item) => $item['rooms_count'] == 0);
                            @endphp
                            @if($allRoomsEmpty  || empty($filteredData))
                                <div class="alert-box">
                                    <i class="fas fa-exclamation-circle alert-icon"></i>
                                    <div class="alert-content">
                                        <strong>Trang web chúng tôi không còn phòng tại chỗ nghỉ này từ
                                            ngày {{ request()->start_date }} đến
                                            T4, {{request()->end_date}}</strong>
                                        <p>Chọn ngày khác để xem phòng trống</p>
                                    </div>
                                </div>
                            @else
                                @foreach( $filteredData as $key => $data)
                                    <li>
                                        @php
                                            $category = \App\Models\CatalogueRoom::query()->find($data['id']);
                                            $images = \App\Models\Image::query()->where('object_id',$data['id'])->get()
                                        @endphp

                                        <figure class="left" id="gallery1">
                                            <a href="{{Storage::url($category->thumbnail)}}"
                                               data-sub-html="<p>Superior Double Room</p>">
                                                <img src="{{Storage::url($category->thumbnail)}}"
                                                     alt=""/>
                                                <span class="image-overlay" style="z-index: 0"></span>
                                            </a>
                                            @foreach($category->images as $image)
                                                <a href="{{Storage::url($image->path)}}"
                                                   data-sub-html="<p>Superior Double Room</p>">
                                                    <img src="{{Storage::url($image->path)}}"
                                                         alt=""/>
                                                </a>
                                            @endforeach
                                        </figure>
                                        <div class="meta">
                                            <h3>{{$data['name']}}</h3>
                                            <div style="display: flex; justify-content: space-between">
                                                <p class="first">Giá:</p>
                                                <strong class="second">{{number_format($data['price'])}}
                                                    VND</strong>
                                            </div>
                                            <div style="margin-bottom: 10px">
                                                Nhập số lượng phòng:
                                                <input type="number" id="qty_room_{{$key}}" name=""
                                                       style="margin-top: 10px" class="qty-input"
                                                       placeholder="Số lượng phòng" value="0"/>

                                                <input type="hidden" id="hidden_qty_{{$key}}"
                                                       name="{{$data['id']}}"
                                                       value=""/>


                                                <div id="error-message_{{$key}}"
                                                     style="color: red; display: none;">
                                                    Số lượng phòng không được vượt quá số phòng có sẵn!
                                                </div>
                                            </div>

                                            <a href="javascript:void(0)" title="more info" class="more-info">+
                                                Xem
                                                thêm</a>
                                        </div>
                                        <div class="room-information">
                                            <div class="row">
                                                <div class="">Người lớn: {{$data['number_adult']}}
                                                    @for ( $i = 1; $i <= $data['number_adult']; $i++)
                                                        <i class="material-icons">&#xE7FD;</i>
                                                    @endfor</div>
                                            </div>

                                            <div class="row " style="margin-top: 10px;">
                                                <span class="first">Trẻ em: {{$data['number_child']}}</span>
                                            </div>
                                            <div class="row " style="margin-top: 10px;">
                                                <span class="first">Phòng trống: {{$data['rooms_count']}}</span>
                                            </div>
                                        </div>
                                        <div class="more-information">
                                            <div style="margin-bottom: 10px"><strong>+ Tiện
                                                    nghi</strong></div>
                                            @php
                                                $facilities =  $data['attributeValues']
                                                      ->flatten()
                                                      ->unique()
                                            @endphp
                                            @foreach($facilities as $facilitie)
                                                <div class="text-wrap">

                                                    <div class="facility-list">
                                                        <div class="facility-item">{{$facilitie->content}}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div style="margin-bottom: 10px"><strong>+ Mô tả</strong></div>
                                            <p>{!! $data['description'] !!}</p>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                            {{--                                                                                        @empty--}}
                            {{--                                                                                            <div class="alert-box">--}}
                            {{--                                                                                                <i class="fas fa-exclamation-circle alert-icon"></i>--}}
                            {{--                                                                                                <div class="alert-content">--}}
                            {{--                                                                                                    <strong>Trang web chúng tôi không còn phòng tại chỗ nghỉ này từ--}}
                            {{--                                                                                                        ngày {{ request()->start_date }} đến--}}
                            {{--                                                                                                        T4, {{request()->end_date}}</strong>--}}
                            {{--                                                                                                    <p>Chọn ngày khác để xem phòng trống</p>--}}
                            {{--                                                                                                </div>--}}
                            {{--                                                                                            </div>--}}
                            {{--                                                                                        @endforelse--}}
                        </ul>

                        @if(!$allRoomsEmpty  && !empty($filteredData))
                            <div class="submit-btn"
                                 style="display: flex; justify-content: center; margin: auto; margin-top: 20px;">
                                <button type="submit" class="gradient-button"
                                        style="padding: 15px; font-size: 18px; width: 200px; height: 50px">Đặt ngay
                                </button>
                            </div>
                        @endif

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
                                <li>{{$facilitie->content}}</li>
                            </ul>
                        </div>
                    @endforeach

                </article>
            </section>
            <!--//facilities-->

            <!--location-->
            <section id="location" class="tab-content">
                <article>
                    <!-- Map -->
                    <div class="gmap" id="map_canvas" style="height: 400px; width: 100%;">
                        <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.6823347925044!2d{{ $hotel->longitude ?? 105.7465729 }}!3d{{ $hotel->latitude ?? 21.0447132 }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abc12345%3A0xabcdef123456!2zU29tZSBsb2NhdGlvbiBuYW1lIQ!5e0!3m2!1sen!2s!4v1691140198945!5m2!1sen!2s"
                                width="100%"
                                height="400"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                        </iframe>

                    </div>
                    <!-- //Map -->
                </article>
            </section>


            <!--//location-->

            <!--reviews-->
            <section id="reviews" class="tab-content">


                <article>
                    <h2>Tất cả đánh giá về khách sạn của chúng tôi</h2>
                    <ul class="reviews">
                        @foreach($rates as $rate)
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
                        @endforeach
                    </ul>
                </article>
            </section>
            <!--//reviews-->
            <!--//row-->
        </div>
    </main>

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
            $('#qty_room_{{$key}}').on('input', function () {
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