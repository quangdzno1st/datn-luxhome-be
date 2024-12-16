@extends('admin.layouts.master')

@section('title')
    Danh sách loại phòng
@endsection

@section('content')
    <!-- start page title -->

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Danh sách loại phòng</h4>

                {{-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Khách sạn: {{$hotel->name}}</a></li>
                    </ol>
                </div> --}}

            </div>
        </div>
    </div>

    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    @can('create_categories')
                    <a href="{{route('admin.catalogue-rooms.create')}}" class="btn btn-success" style="height: 37px; ">+ Thêm mới</a>
                    @endcan
                        <form class="d-flex justify-content-sm-end" action="" method="get">
                            @if (Auth::user()->type == App\Models\User::ADMIN)
                                        <div class="ms-2">
                                            <select name="hotel" id="" class="form-select">
                                                <option value="">Chọn khách sạn</option>
                                                @foreach ($hotels as $hotel)
                                                    <option value="{{ $hotel->id }}" @selected(request()->input('hotel') == $hotel->id)>
                                                        {{ $hotel->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                            <div class="ms-2">
                                <input type="text" class="form-control " placeholder="Điền tên loại" name="name" value="{{ request()->has('name') ? request()->input('name') : '' }}">
                            </div>
                            <div class="ms-2">
                                <input type="text" class="form-control " placeholder="Điền giá" name="price" value="{{ request()->has('price') ? request()->input('price') : '' }}">
                            </div>
                            <div class="ms-2">
                                <select name="status" id="" class="form-select">
                                    <option value="">Chọn trạng thái</option>
                                    <option value="1" @selected(request()->input('status') == 1)>Hoạt động</option>
                                    <option value="2" @selected(request()->input('status') == 2)>Không hoạt động</option>
                                </select>
                            </div>
                            <div class="ms-1">
                                <button class="btn btn-primary">Tìm kiếm</button>
                            </div>
                        </form>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($catalogueRooms as $catalogueRoom)
                        <div class="col-sm-6 col-xl-4">
                            <!-- Simple card -->
                            <div class="card">
                                <img class="card-img-top img-fluid" style="height: 12rem; object-fit: cover" src="{{\Storage::url($catalogueRoom->thumbnail)}}" alt="Ảnh thumbnail loại phòng">
                                <div class="card-body">
                                    <div class="">
                                        <h4 class="card-title mb-2 " title="{{ $catalogueRoom->name }}">{{$catalogueRoom->name}}</h4>
                                    </div>
                                    <p class="card-text fw-bold">Trạng thái: {!! $catalogueRoom->status == 1 ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-danger">Không hoạt động</span>' !!}</p>
                                    <p class="card-text">Diện tích: {{$catalogueRoom->acreage}} <span>m<sup>2</sup></span></p>
                                    <p class="card-text">Sức chứa người lớn: {{$catalogueRoom->number_adult}}</p>
                                    <p class="card-text">Sức chứa trẻ em: {{$catalogueRoom->number_child}}</p>
                                    <p class="card-text">Giá: {{number_format($catalogueRoom->price, 0, ',', '.')}} VNĐ</p>
                                    <p class="card-text">Phòng đang đặt: {{ $roomBookedQtyMapBy[$catalogueRoom['id']]['booked_room_qty'] . ' / ' . $roomBookedQtyMapBy[$catalogueRoom['id']]['total_rooms'] . ' phòng' }}
                                     @if($roomBookedQtyMapBy[$catalogueRoom['id']]['booked_room_qty'] == $roomBookedQtyMapBy[$catalogueRoom['id']]['total_rooms'])
                                            <span class="badge bg-warning">Hết phòng</span>
                                     @endif</p>
                                     @can('edit_categories')
                                    <div class="text-end">
                                        <a href="{{route('admin.catalogue-rooms.edit', $catalogueRoom->id)}}" class="btn btn-soft-secondary">Chi tiết</a>
                                    </div>
                                    @endcan
                                </div>
                            </div><!-- end card -->
                        </div><!-- end col -->
                        @endforeach
                        <div class="row">
                            {{$catalogueRooms->appends(request()->query())->links()}}   
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->

@endsection

@section('styles')
<style>
    .text-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis; /* Thêm dấu ba chấm */
        line-height: 20px;
    }
</style>

@endsection

