@extends('admin.layouts.master')
@section('title')
    Danh sách đánh giá bị ẩn
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Đánh giá của khách sạn: {{ $hotel->name }} bị ẩn</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Đánh giá</a></li>
                        <li class="breadcrumb-item active">Thùng rác</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <!-- end page title -->
    
    <!-- Notification -->
    {{-- <div class="row">
        @if (session('msg'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('msg') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div> --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-auto">
                                <div class="col-sm-auto">
                                    <div>
                                        @if (Auth::user()->type == 2)
                                            <a href="{{ route('admin.rates.hotel', $hotel->id) }}"
                                                class="btn btn-light bg-gradient waves-effect waves-light">
                                                <i class="bx bx-arrow-back"></i> Quay lại</a>
                                        @else
                                            <a href="{{ route('admin.rates.hotel.hotelier') }}"
                                                class="btn btn-light bg-gradient waves-effect waves-light">
                                                <i class="bx bx-arrow-back"></i> Quay lại</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <form class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input type="text" class="form-control search" name="content"
                                            placeholder="Điền nội dung"
                                            value="{{ request()->has('content') ? request()->input('content') : '' }}">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                    <div class="ms-2">
                                        <input type="text" class="form-control search" name="name"
                                            placeholder="Điền tên khách"
                                            value="{{ request()->has('name') ? request()->input('name') : '' }}">
                                    </div>
                                    <div class="ms-2">
                                        <select name="rate" id="" class="form-select">
                                            <option value="">Lọc đánh giá</option>
                                            <option value="5" @selected(request()->input('rate') == 5)>Rất Hài Lòng</option>
                                            <option value="4" @selected(request()->input('rate') == 4)>Hài Lòng</option>
                                            <option value="3" @selected(request()->input('rate') == 3)>Bình Thường</option>
                                            <option value="2" @selected(request()->input('rate') == 2)>Kém</option>
                                            <option value="1" @selected(request()->input('rate') == 1)>Rất kém</option>
                                        </select>
                                    </div>
                                    {{-- <div class="ms-2">
                                        <select name="status" id="" class="form-select">
                                            <option value="">Chọn tất cả</option>
                                            <option value="1" @selected(request()->input('status') == 1)>Đã trả lời</option>
                                            <option value="2" @selected(request()->input('status') == 2)>Chưa trả lời</option>
                                        </select>
                                    </div> --}}
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
                                    <th scope="col">Khách hàng</th>
                                    <th scope="col">Đánh giá</th>
                                    <th scope="col">Nội dung</th>
                                    <th scope="col">Ngày đánh giá</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rates as $index => $rate)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $rate->user->name }}</td>
                                        <td class="text-warning">
                                            <span style="background-color: {{App\Models\Rate::RATE[$rate->rate][1]}}" class="badge ">{{App\Models\Rate::RATE[$rate->rate][0]}}</span>
                                        </td>
                                        </td>
                                        <td>{{ $rate->content }}</td>
                                        <td>{{ Carbon\Carbon::parse($rate->created_at)->format('H:i:s d-m-Y'); }}</td>
                                        <td class="d-flex gap-1">
                                            <form action="{{ route('admin.rates.restore', $rate->id) }}" method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-soft-success">Khôi
                                                    phục</button>
                                            </form>
                                            {{-- <div class="remove">
                                                <button class="btn btn-sm btn-soft-danger remove-item-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteRecordModal{{ $rate->id }}">Xóa</button>
                                            </div> --}}
                                        </td>
                                    </tr>
                                    <!-- Modal -->
                                    {{-- <div class="modal fade zoomIn" id="deleteRecordModal{{ $rate->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close" id="btn-close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mt-2 text-center">
                                                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json"
                                                            trigger="loop" colors="primary:#f7b84b,secondary:#f06548"
                                                            style="width:100px;height:100px"></lord-icon>
                                                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                                            <h4>Bạn chắc chắn ?</h4>
                                                            <p class="text-muted mx-4 mb-0">Bạn có chắc muốn xóa vĩnh viễn
                                                                đánh giá này ?</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                        <form action="{{ route('admin.rates.destroy', $rate->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" type="button" class="btn w-sm btn-light"
                                                                data-bs-dismiss="modal">Đóng
                                                            </button>
                                                            <button type="submit" class="btn w-sm btn-danger "
                                                                id="delete-record">Chắc chắn!
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $rates->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
@endsection

@section('styles')
    <style>
        td {
            white-space: nowrap;
            /* Không xuống dòng */
            overflow: hidden;
            /* Ẩn phần văn bản thừa */
            text-overflow: ellipsis;
            /* Hiển thị dấu ... khi vượt quá */
            max-width: 150px;
            /* Đặt chiều rộng tối đa */

        }
    </style>
@endsection