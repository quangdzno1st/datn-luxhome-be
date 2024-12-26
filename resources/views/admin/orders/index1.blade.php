@extends('admin.layouts.master')
@section('title')
    Đơn đặt
@endsection
@section('styles')
    <style>
    .btn-smaller {
    font-size: 0.1em; /* Adjust font size as needed */
    padding: 0.25rem 0.5rem; /* Adjust padding as needed */
    }
    .btn-container {
        position: relative;
        display: inline-block;
    }

    /* Description mặc định ẩn */
    .btn-container .description {
        position: absolute;
        left: 105%; /* Đặt cạnh nút (bên phải) */
        top: 50%;
        transform: translateY(-50%);
        background-color: #000;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.6rem;
        white-space: nowrap;
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.2s ease-in-out, visibility 0.2s ease-in-out;
    }

    /* Mũi tên chỉ vào nút */
    .btn-container .description::after {
        content: '';
        position: absolute;
        top: 50%;
        left: -5px;
        transform: translateY(-50%);
        border-width: 5px;
        border-style: solid;
        border-color: transparent #000 transparent transparent;
    }

    /* Hiện description khi hover */
    .btn-container:hover .description {
        visibility: visible;
        opacity: 1;
    }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Danh sách đơn đặt</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Đơn đặt</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="card-body">
                            @if (session('result'))
                                <div class="card-header   alert alert-{{session('color')}} alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                                <form action="" method="GET" class="d-flex align-items-center justify-content-end gap-2">
                                    @if(\Illuminate\Support\Facades\Auth::user()->type==\App\Models\User::ADMIN)
                                        <div class="mb-0">
                                            <div class="">
                                                <label for="start_date">Khách sạn:</label>
                                            </div>
                                            <select name="hotel" class="form-select">
                                                <option value="">Chọn khách sạn</option>
                                                @foreach($hotels as $hotel)
                                                    <option value="{{$hotel->id}}" {{ request('hotel') == $hotel->id ? 'selected' : '' }}>{{$hotel->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class=" mb-0">
                                        <div class="">
                                            <label for="start_date">Mã phòng:</label>
                                        </div>
                                        <input type="text" name="code" placeholder="Mã đặt phòng" class="form-control" value="{{ request('code') }}">
                                    </div>
                                    <div class=" mb-0">
                                        <div class="">
                                            <label for="start_date">Trạng thái:</label>
                                        </div>
                                        <select name="status" class="form-select">
                                            <option value="">Chọn trạng thái</option>
                                            <option value="1" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                            <option value="2" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                            <option value="3" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                                            <option value="4" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                            <option value="5" {{ request('status') == 'require_cancelled' ? 'selected' : '' }}>Yêu cầu hủy</option>
                                        </select>
                                    </div>
                                    <div class=" mb-0">
                                        <div class="">
                                            <label for="start_date">Tổng tiền tối thiểu:</label>
                                        </div>
                                        <input type="number" name="total_amount" placeholder="1000000" class="form-control" value="{{ request('total_amount') }}">
                                    </div>
                                    <div class=" mb-0">
                                        <div class="">
                                            <label for="start_date">Thời gian bắt đầu:</label>
                                        </div>
                                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                                    </div>
                                    <div class=" mb-0">
                                        <div class="">
                                            <label for="start_date">Thời gian kết thúc:</label>
                                        </div>
                                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                                    </div>
                                    <div class=" mb-0">
                                        <div class="">
                                            <label for="start_date"><span class="text-white">.</span></label>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                                    </div>
                                </form>
                                <table id="example" class="table table-bordered dt-responsive nowrap align-middle"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>Mã code</th>
                                    <th>Người đặt</th>
                                    <th>Trạng thái</th>
                                    <th>Trạng thái thanh toán</th>
                                    <th>Ngày đặt</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Tổng tiền thanh toán</th>
                                    <th>Chi tiết</th>
                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="">
{{--                                            //        0: chưa đến ngày--}}
{{--                                            //        1: checkin,checkout muộn--}}
{{--                                            //        2: check out muộn đằng sau có khách--}}
{{--                                            //        3: đang dùng phòng--}}
                                                <div class="btn-container">
                                                    {{$order->code}}
                                                    @if($order->statusNoti==2)
                                                            <div type="button" class="btn btn-danger btn-sm" style="padding: 2px 2px; font-size: 0.4rem;">
                                                                <span class="d-flex align-items-center">
                                                                    <span class="spinner-grow flex-shrink-0" role="status" style="width: 12px; height: 12px;">
                                                                        <span class="visually-hidden">Loading...</span>
                                                                    </span>
                                                                </span>
                                                            </div>
                                                            <!-- Description -->
                                                            <div class="description">Quá giờ checkout, sau có khách</div>
                                                    @elseif($order->statusNoti==1)
                                                        <div type="button" class="btn btn-warning btn-sm" style="padding: 2px 2px; font-size: 0.4rem;">
                                                                <span class="d-flex align-items-center">
                                                                    <span class="spinner-grow flex-shrink-0" role="status" style="width: 12px; height: 12px;">
                                                                        <span class="visually-hidden">Loading...</span>
                                                                    </span>
                                                                </span>
                                                        </div>
                                                        <!-- Description -->
                                                        <div class="description">Checkin muộn</div>
                                                    @elseif($order->statusNoti==4)
                                                        <div type="button" class="btn btn-secondary btn-sm" style="padding: 2px 2px; font-size: 0.4rem;">
                                                                <span class="d-flex align-items-center">
                                                                    <span class="spinner-grow flex-shrink-0" role="status" style="width: 12px; height: 12px;">
                                                                        <span class="visually-hidden">Loading...</span>
                                                                    </span>
                                                                </span>
                                                        </div>
                                                        <!-- Description -->
                                                        <div class="description">Checkout muộn</div>
                                                    @endif
                                                </div>
                                        </td>
                                        <td>{{$order->name}}</td>
                                        <td>
                                            <div class="btn-group">
                                                @if(\App\Constant\Enum\StatusOrderEnum::isYeuCauHuy($order['status']))
                                                    <a class="btn btn-sm btn-danger edit-item-btn"
                                                       data-bs-toggle="modal" href="#{{ $order['id'] }}">
                                                        Yêu cầu hủy
                                                    </a>
                                                @elseif(\App\Constant\Enum\StatusOrderEnum::isHoanThanh($order['status']))
                                                    <button class="btn btn-sm btn-success">{{ \App\Constant\Enum\StatusOrderEnum::parse($order['status'])->getName() }}</button>
                                                @elseif(\App\Constant\Enum\StatusOrderEnum::isDaXacNhan($order['status']))
                                                    <button class="btn btn-sm btn-primary">{{ \App\Constant\Enum\StatusOrderEnum::parse($order['status'])->getName() }}</button>
                                                @else
                                                    <button class="btn btn-sm btn-warning">{{\App\Constant\Enum\StatusOrderEnum::parse($order['status'])->getName() }}</button>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @if(\App\Constant\Enum\StatusPaymentOrderEnum::isChuaHoanTien($order['status_payment'])
                                                       && \App\Constant\Enum\StatusOrderEnum::isDaHuy($order['status']))
                                                        <a class="btn btn-sm btn-danger edit-item-btn"
                                                           data-bs-toggle="modal" href="#ht{{ $order['id'] }}">
                                                            {{ \App\Constant\Enum\StatusPaymentOrderEnum::parse($order['status_payment'])->getName() }}
                                                        </a>

                                                @elseif(\App\Constant\Enum\StatusPaymentOrderEnum::isChuaThanhToan($order['status_payment']))
                                                    <button class="btn btn-sm btn-warning badge">{{ \App\Constant\Enum\StatusPaymentOrderEnum::parse($order['status_payment'])->getName() }}</button>
                                                @else
                                                    <button class="btn btn-sm btn-success badge">{{ \App\Constant\Enum\StatusPaymentOrderEnum::parse($order['status_payment'])->getName() }}</button>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{\Carbon\Carbon::parse($order->start_date)->format('d-m-Y')}}</td>
                                        <td>{{\Carbon\Carbon::parse($order->end_date)->format('d-m-Y')}}</td>
                                        <td>
                                            {{number_format($order->total_amount)}} VND
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{route('admin.orders.show',$order)}}"
                                                           class="dropdown-item">
                                                            <i class="ri-eye-fill align-bottom me-2 text-muted"></i> Chi
                                                            tiết</a>
                                                    </li>
                                                    @if($order['status'] == \App\Constant\Enum\StatusOrderEnum::DA_XAC_NHAN->value)
                                                    <li>
                                                        <a href="#{{ $order['id'] }}" class="dropdown-item" data-bs-toggle="modal" >
                                                            <i class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                            Hủy đơn
                                                        </a>
                                                    </li>
                                                    @if(!$order['haventCheckin'])
                                                    <li>
                                                        <a href="#hoanthanh{{ $order['id'] }}" class="dropdown-item" data-bs-toggle="modal" >
                                                            <i class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                            Hoàn thành đơn
                                                        </a>
                                                    </li>
                                                        @endif
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade flip" id="hoanthanh{{ $order['id'] }}" tabindex="-1"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body p-5 text-center">
                                                    <i class="fa-solid fa-money-bill-wave"></i>
                                                    <div class="mt-4 text-center">
                                                        <h4>Hoàn thành đơn!</h4>
                                                        <p class="text-muted fs-15 mb-4">Bạn có muốn hoàn thành
                                                            order {{ $order['code'] }} này
                                                            không?</p>
                                                        <div class="hstack gap-2 justify-content-center remove">
                                                                <button class="btn btn-link link-success fw-medium text-decoration-none"
                                                                        type="submit"
                                                                        id="deleteRecord-close"
                                                                        data-bs-dismiss="modal">
                                                                    <i class="ri-close-line me-1 align-middle"></i>Đóng
                                                                </button>
                                                            <form method="POST"
                                                                  action="{{route('admin.orders.updateFinishOrder',$order['id'])}}">
                                                                @csrf
                                                                <button
                                                                        class="btn btn-sm btn-success edit-item-btn"
                                                                        type="submit"
                                                                >
                                                                    Xác nhận
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade flip" id="{{ $order['id'] }}" tabindex="-1"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body p-5 text-center">
                                                    <i class="fa-solid fa-money-bill-wave"></i>
                                                    <div class="mt-4 text-center">
                                                        <h4>Hủy order!</h4>
                                                        <p class="text-muted fs-15 mb-4">Bạn có muốn hủy
                                                            order {{ $order['code'] }} này
                                                            không?</p>
                                                        <div class="hstack gap-2 justify-content-center remove">
                                                            @if($order['status']==\App\Constant\Enum\StatusOrderEnum::YEU_CAU_HUY->value)
                                                            <form method="POST"
                                                                  action="{{route('admin.orders.not_accepted_cancel',$order['id'])}}">
                                                                @csrf
                                                                <button class="btn btn-link link-success fw-medium text-decoration-none"
                                                                        type="submit"
                                                                        id="deleteRecord-close"
                                                                        data-bs-dismiss="modal">
                                                                    <i class="ri-close-line me-1 align-middle"></i>Hủy
                                                                </button>
                                                            </form>
                                                            @else
                                                                <button class="btn btn-link link-success fw-medium text-decoration-none"
                                                                        type="submit"
                                                                        id="deleteRecord-close"
                                                                        data-bs-dismiss="modal">
                                                                    <i class="ri-close-line me-1 align-middle"></i>Đóng
                                                                </button>
                                                            @endif
                                                            <form method="POST"
                                                                  action="{{route('admin.orders.accepted_cancel',$order['id'])}}">
                                                                @csrf
                                                                <button
                                                                        class="btn btn-sm btn-danger edit-item-btn"
                                                                        type="submit"
                                                                >
                                                                    Xác nhận
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade flip" id="ht{{ $order['id'] }}" tabindex="-1"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body p-5 text-center">
                                                    <i class="fa-solid fa-money-bill-wave"></i>
                                                    <div class="mt-4 text-center">
                                                        <h4>Hoàn tiền đặt phòng!</h4>
                                                        <p class="text-muted fs-15 mb-4">Đơn đặt
                                                            phòng {{ $order['code'] }} đã được hoàn tiền?</p>
                                                        <div class="hstack gap-2 justify-content-center remove">
                                                            <button class="btn btn-link link-success fw-medium text-decoration-none"
                                                                    type="submit"
                                                                    id="deleteRecord-close"
                                                                    data-bs-dismiss="modal">
                                                                <i class="ri-close-line me-1 align-middle"></i>Đóng
                                                            </button>

                                                            <form method="post" action="{{ route('admin.orders.refunded-money', $order['id']) }}">
                                                                @csrf
                                                                <button
                                                                        class="btn btn-sm btn-danger edit-item-btn"
                                                                        type="submit"
                                                                >
                                                                    Xác nhận
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <div class="pagination-wrap hstack gap-2">
                            {{ $orders->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
@endsection
@section('style-libs')
    <link href="{{ asset('theme/admin/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <script src="{{ asset('theme/admin/assets/libs/list.pagination.js/list.pagination.min.js') }}"></script>

@endsection
@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="{{asset('theme/admin/assets/js/pages/datatables.init.js')}}"></script>
    <!-- App js -->
    <script src="{{asset('theme/admin/assets/js/app.js')}}"></script>
    <script>
        $('#example').DataTable({
            paging: false, // Tắt phân trang
            info: false,   // Tắt thông tin
            searching: false, // Tắt tìm kiếm
        });
    </script>
@endsection