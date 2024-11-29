@extends('admin.layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">

                    <h4 class="card-title mb-0">Danh sách order</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="card-body">
                            <table id="example" class="table table-bordered dt-responsive nowrap align-middle"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll"
                                                   value="option">
                                        </div>
                                    </th>
                                    <th>Email</th>
                                    <th>Người đặt</th>
                                    <th>Mã code</th>
                                    <th>Ngày đặt</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Phí đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Tiền thực nhận</th>
                                    <th>Chi phí phát sinh</th>
                                    <th>Trạng thái</th>
                                    <th>Trạng thái thanh toán</th>
                                    <th>Chi tiết</th>
                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @foreach($orders as $order)
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="chk_child"
                                                       value="option1">
                                            </div>
                                        </th>
                                        <td>{{$order->email}}</td>
                                        <td>{{$order->name}}</td>
                                        <td>{{$order->code}}</td>
                                        <td>{{date('d-M-y', strtotime($order->start_date))}}</td>
                                        <td>{{date('d-M-y', strtotime($order->end_date))}}</td>
                                        <td>{{number_format($order->booking_fee)}}VND</td>
                                        <td>
                                            {{number_format($order->total_amount)}} VND
                                        </td>
                                        <td>{{number_format($order->net_amount)}}VND</td>
                                        <td>{{number_format($order->incidental_costs)}}VND</td>
                                        <td>
                                            <div class="btn-group">
                                                @if(\App\Constant\Enum\StatusOrderEnum::isYeuCauHuy($order['status']))
                                                    <a class="btn btn-sm btn-danger edit-item-btn"
                                                       data-bs-toggle="modal" href="#{{ $order['id'] }}">
                                                        Yêu cầu hủy
                                                    </a>
                                                @elseif(\App\Constant\Enum\StatusOrderEnum::isDaXacNhan($order['status'])
                                                    || \App\Constant\Enum\StatusOrderEnum::isHoanThanh($order['status']))
                                                    <button class="btn btn-sm btn-success">{{ \App\Constant\Enum\StatusOrderEnum::parse($order['status'])->getName() }}</button>
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
                                                    <button class="btn btn-sm btn-warning">{{ \App\Constant\Enum\StatusPaymentOrderEnum::parse($order['status_payment'])->getName() }}</button>
                                                @else
                                                    <button class="btn btn-sm btn-success">{{ \App\Constant\Enum\StatusPaymentOrderEnum::parse($order['status_payment'])->getName() }}</button>
                                                @endif
                                            </div>
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
                                                    <li>
                                                        <form>
                                                            <button type="button" class="dropdown-item remove-item-btn">
                                                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                                Xóa
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
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
                            {{ $orders->links() }}
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
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css"/>
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css"/>

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link href="{{ asset('theme/admin/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <script src="{{ asset('theme/admin/assets/libs/list.pagination.js/list.pagination.min.js') }}"></script>

@endsection
@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

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