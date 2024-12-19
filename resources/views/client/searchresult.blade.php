@extends('client.layouts.master')

@section('title')
    Danh sách khách sạn
@endsection

@section('content')
    <!--main-->
    <style>
        .no-data-message {
            font-size: 18px;
            font-weight: bold;
            color: #ff3333; /* Màu đỏ */
            padding: 10px 20px;
            background-color: #f8d7da; /* Màu nền nhẹ nhàng */
            border: 1px solid #f5c6cb; /* Viền nhẹ */
            border-radius: 5px; /* Bo tròn góc */
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Đổ bóng nhẹ */
            margin-top: 20px; /* Khoảng cách trên */
            width: 100%;
            max-width: 400px; /* Đảm bảo không quá rộng */
            margin-left: auto;
            margin-right: auto;
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
    </style>
    <main class="main">
        <div class="wrap">
            <!--breadcrumbs-->
                       <nav class="breadcrumbs">
                           <!--crumbs-->
                           <ul>
                               <li><a href="{{route('home.index')}}" title="Home">Trang chủ</a></li>
                               <li>Kết quả tìm kiếm</li>
                           </ul>
                           <!--//crumbs-->

                           <!--top right navigation-->
                           {{-- <ul class="top-right-nav">
                               <li><a href="#" title="Back to results">Back to results</a></li>
                               <li><a href="#" title="Change search">Change search</a></li>
                           </ul> --}}
                           <!--//top right navigation-->
                       </nav>
            <!--//breadcrumbs-->

            <div class="row">
                <!--sidebar-->
                <aside class="one-fourth left-sidebar">
                    <article class="widget refine-search-results">
                        <h4>Thu hẹp kết quả tìm kiếm</h4>
                        <dl>
                            <!--Giá (mỗi đêm)-->

                            <form action="" method="GET">
                                <!-- Khoảng giá -->
                                <dt>Khoảng Giá</dt>
                                <div>
                                    <input type="hidden" name="city_id" value="{{ request()->get('city_id', '') }}">
                                    <input type="hidden" name="start_date"
                                           value="{{ request()->get('start_date', '') }}">
                                    <input type="hidden" name="end_date" value="{{ request()->get('end_date', '') }}">
                                    <input type="hidden" name="number_adult"
                                           value="{{ request()->get('number_adult', '') }}">
                                    <input type="hidden" name="number_child"
                                           value="{{ request()->get('number_child', '') }}">
                                    <div class="checkbox">
                                        <input type="checkbox" id="ch1" name="price[]" value="1000000-2000000"
                                                {{ in_array('1000000-2000000', request()->get('price', [])) ? 'checked' : '' }} />
                                        <label for="ch1">1,000,000 - 2,000,000 VND</label>
                                    </div>
                                    <div class="checkbox">
                                        <input type="checkbox" id="ch3" name="price[]" value="2000000-4000000"
                                                {{ in_array('2000000-4000000', request()->get('price', [])) ? 'checked' : '' }} />
                                        <label for="ch3">2,000,000 - 4,000,000 VND</label>
                                    </div>
                                    <div class="checkbox">
                                        <input type="checkbox" id="ch4" name="price[]" value="4000000-8000000"
                                                {{ in_array('4000000-8000000', request()->get('price', [])) ? 'checked' : '' }} />
                                        <label for="ch4">4,000,000 - 8,000,000 VND</label>
                                    </div>
                                    <div class="checkbox">
                                        <input type="checkbox" id="ch10" name="price[]" value="8000000"
                                                {{ in_array('8000000', request()->get('price', [])) ? 'checked' : '' }} />
                                        <label for="ch10">8,000,000 VND+</label>
                                    </div>
                                </div>

                                <!-- Xếp hạng sao -->
                                <dt class="mt-4">Xếp hạng sao</dt>
                                <div class="mb-1 mt-2">
                                    <div class="checkbox">
                                        <input type="checkbox" id="ch1_star" name="star[]" value="5"
                                                {{ in_array('5', request()->get('star', [])) ? 'checked' : '' }} />
                                        <label for="ch1_star" class="stars" style="float:unset">
                                            <i class="material-icons">&#xE838;</i>
                                            <i class="material-icons">&#xE838;</i>
                                            <i class="material-icons">&#xE838;</i>
                                            <i class="material-icons">&#xE838;</i>
                                            <i class="material-icons">&#xE838;</i>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <input type="checkbox" id="ch2_star" name="star[]" value="4"
                                                {{ in_array('4', request()->get('star', [])) ? 'checked' : '' }} />
                                        <label for="ch2_star" class="stars" style="float:unset">
                                            <i class="material-icons">&#xE838;</i>
                                            <i class="material-icons">&#xE838;</i>
                                            <i class="material-icons">&#xE838;</i>
                                            <i class="material-icons">&#xE838;</i>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <input type="checkbox" id="ch3_star" name="star[]" value="3"
                                                {{ in_array('3', request()->get('star', [])) ? 'checked' : '' }} />
                                        <label for="ch3_star" class="stars" style="float:unset">
                                            <i class="material-icons">&#xE838;</i>
                                            <i class="material-icons">&#xE838;</i>
                                            <i class="material-icons">&#xE838;</i>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <input type="checkbox" id="ch4_star" name="star[]" value="2"
                                                {{ in_array('2', request()->get('star', [])) ? 'checked' : '' }} />
                                        <label for="ch4_star" class="stars" style="float:unset">
                                            <i class="material-icons">&#xE838;</i>
                                            <i class="material-icons">&#xE838;</i>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <input type="checkbox" id="ch5_star" name="star[]" value="1"
                                                {{ in_array('1', request()->get('star', [])) ? 'checked' : '' }} />
                                        <label for="ch5_star" class="stars" style="float:unset">
                                            <i class="material-icons">&#xE838;</i>
                                        </label>
                                    </div>
                                </div>
                                <hr style="border: none; height: 1px; background-color: black;">


                                <!-- Nút Submit -->
                                <button type="submit">Tìm kiếm</button>
                            </form>


                            <!--//Xếp hạng sao-->

                            <!--Đánh giá của người dùng-->
                            {{--                            <dt>Đánh giá của người dùng</dt>--}}
                            {{--                            <dd>--}}
                            {{--                                <div id="slider"></div>--}}
                            {{--                                <span class="min">0</span><span class="max">10</span>--}}
                            {{--                            </dd>--}}
                            <!--//Đánh giá của người dùng-->
                        </dl>
                    </article>
                </aside>

                <!--//sidebar-->

                <!--three-fourth content-->
                <div class="three-fourth">
                    {{--                    <div class="sort-by">--}}
                    {{--                        <h3>Sort by</h3>--}}
                    {{--                        <ul class="sort">--}}
                    {{--                            <li>Popularity <a href="#" title="ascending" class="ascending">ascending</a><a href="#"--}}
                    {{--                                                                                                           title="descending"--}}
                    {{--                                                                                                           class="descending">descending</a>--}}
                    {{--                            </li>--}}
                    {{--                            <li>Price <a href="#" title="ascending" class="ascending">ascending</a><a href="#"--}}
                    {{--                                                                                                      title="descending"--}}
                    {{--                                                                                                      class="descending">descending</a>--}}
                    {{--                            </li>--}}
                    {{--                            <li>Stars <a href="#" title="ascending" class="ascending">ascending</a><a href="#"--}}
                    {{--                                                                                                      title="descending"--}}
                    {{--                                                                                                      class="descending">descending</a>--}}
                    {{--                            </li>--}}
                    {{--                            <li>Rating <a href="#" title="ascending" class="ascending">ascending</a><a href="#"--}}
                    {{--                                                                                                       title="descending"--}}
                    {{--                                                                                                       class="descending">descending</a>--}}
                    {{--                            </li>--}}
                    {{--                        </ul>--}}

                    {{--                        <ul class="view-type">--}}
                    {{--                            <li class="grid-view"><a href="#" title="grid view">grid view</a></li>--}}
                    {{--                            <li class="list-view"><a href="#" title="list view">list view</a></li>--}}
                    {{--                            <li class="location-view"><a href="#" title="location view">location view</a></li>--}}
                    {{--                        </ul>--}}
                    {{--                    </div>--}}

                    <div class="row deals  results">
                        <!--deal-->

                        @if(!$hotels->isEmpty())
                            @php
                                $searchData = session('search_data');
                                $allRoomsEmpty = collect($searchData)->every(fn($item) => $item['rooms_count'] == 0);
                            @endphp
                            @if($allRoomsEmpty)
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
                                @foreach($hotels as $hotel)
                                    <article class="one-third">

                                        <figure><a href="{{route('hotel.show',$hotel->id)}}" title=""><img
                                                        src="{{ Storage::url($hotel?->thumbnail) }}"
                                                        alt=""/></a></figure>
                                        <div class="details">
                                            <h3>{{$hotel->name}}
                                                <span class="stars">
                        @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $hotel->star)
                                                            <i class="fa fa-star star-full"></i>
                                                        @else
                                                            <i class="fa fa-star-o star-empty"></i>
                                                        @endif
                                                    @endfor
                    </span>
                                            </h3>

                                            <span class="address">{{$hotel?->city?->name}}</span>

                                            @php
                                                $prices = $hotel?->catalogues()->pluck('price');
                                                $minPrice = $prices->min(); // Giá nhỏ nhất
                                                $maxPrice = $prices->max(); // Giá lớn nhất
                                            @endphp
                                            <span style="display: flex; justify-content: space-between" class="price">
                    Giá:  <strong>{{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }} VND</strong>
                </span>
                                            <div class="description">
                                                <p>{{  \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($hotel->description), 150)) }}</p>
                                            </div>
                                            <a href="{{route('hotel.show',$hotel->id)}}" title="Book now"
                                               class="gradient-button">Đặt ngay</a>
                                        </div>
                                    </article>
                                @endforeach

                            @endif

                        @else
                            <div class="alert-box">
                                <i class="fas fa-exclamation-circle alert-icon"></i>
                                <div class="alert-content">
                                    <strong>Trang web chúng tôi không còn phòng tại chỗ nghỉ này từ
                                        ngày {{ request()->start_date }} đến
                                        T4, {{request()->end_date}}</strong>
                                    <p>Chọn ngày khác để xem phòng trống</p>
                                </div>
                            </div>
                        @endif
                        <!--//deal-->

                        <!--bottom navigation-->
                        <div class="bottom-nav">
                            <!--back up button-->
                            {{--                            <a href="#" class="scroll-to-top" title="Back up">Back up</a>--}}
                            <!--//back up button-->

                            <!--pager-->
                            {{--                            <div class="pager">--}}
                            {{--                                <span><a href="#">First page</a></span>--}}
                            {{--                                <span><a href="#">&lt;</a></span>--}}
                            {{--                                <span class="current">1</span>--}}
                            {{--                                <span><a href="#">2</a></span>--}}
                            {{--                                <span><a href="#">3</a></span>--}}
                            {{--                                <span><a href="#">4</a></span>--}}
                            {{--                                <span><a href="#">5</a></span>--}}
                            {{--                                <span><a href="#">6</a></span>--}}
                            {{--                                <span><a href="#">7</a></span>--}}
                            {{--                                <span><a href="#">8</a></span>--}}
                            {{--                                <span><a href="#">&gt;</a></span>--}}
                            {{--                                <span><a href="#">Last page</a></span>--}}
                            {{--                            </div>--}}
                            <!--//pager-->
                        </div>
                        <!--//bottom navigation-->
                    </div>
                </div>
                <!--//three-fourth content-->
            </div>
            <!--//main content-->
        </div>
    </main>
    <!--//main-->
@endsection

@section('script-libs')
    <script type="text/javascript" src="{{asset('theme/client/js/jquery.raty.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/client/js/search.js')}}"></script>
@endsection