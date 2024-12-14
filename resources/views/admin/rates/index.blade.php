@extends('admin.layouts.master')
@section('title')
    Danh sách đánh giá từng khách sạn
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Thống kê đánh giá của từng khách sạn</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                </ol>
            </div>

        </div>
    </div>
</div>

<!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-auto">
                                <div class="col-sm-auto">
                                </div>
                            </div>
                            <div class="col-sm">
                                <form class="d-flex justify-content-sm-end" method="GET">
                                    <div class="search-box ms-2">
                                        <input type="text" class="form-control search" placeholder="Tìm kiếm..." name="keyword" value="{{ request()->has('keyword') ? request()->input('keyword') : '' }}">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                    <div class="ms-1">
                                        <button class="btn btn-primary">Tìm kiếm</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Active Tables -->
                        <table class="table table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Khách sạn</th>
                                    <th scope="col">Ảnh</th>
                                    <th scope="col">Tổng số đánh giá</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ratesOfHotels as $index => $ratesOfHotel)
                                <tr>
                                    <td>{{$index + 1}}</td>
                                    <td>{{$ratesOfHotel->name}}</td>
                                    <td>
                                        <img src="{{Storage::url($ratesOfHotel->thumbnail)}}" alt="Ảnh thumbnail" width="100px">
                                    </td>
                                    <td>{{count($ratesOfHotel->rates)}}</td>
                                    <td>
                                        <a class="btn btn-outline-primary" href="{{route('admin.rates.hotel', $ratesOfHotel->id)}}">Chi tiết</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{$ratesOfHotels->links()}}
                        </div>
                    </div>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
@endsection
