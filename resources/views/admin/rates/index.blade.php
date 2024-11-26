@extends('admin.layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Thống kê đánh giá của từng khách sạn</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-auto">
                                <div class="col-sm-auto">
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input type="text" class="form-control search" placeholder="Search...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
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
