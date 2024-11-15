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
                        <div class="row g-4 mb-3">
                            <div class="col-sm-auto">
                                <div class="col-sm-auto">
                                    <div>
                                        <a href="">
{{--                                            <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"--}}
{{--                                                    id="create-btn" data-bs-target="#showModal"><i--}}
{{--                                                        class="ri-add-line align-bottom me-1"></i> Thêm voucher--}}
{{--                                            </button>--}}
                                        </a>
                                        <button class="btn btn-soft-danger" onClick="deleteMultiple()"><i
                                                    class="ri-delete-bin-2-line"></i></button>
                                    </div>
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

                        <div class="card-body">
                            <table id="example" class="table table-bordered dt-responsive nowrap align-middle"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                        </div>
                                    </th>
                                    <th>Người dùng</th>
                                    <th>voucher</th>
                                    <th>Phí đặt</th>
                                    <th>Email</th>
                                    <th>Tên</th>
                                    <th>Mã code</th>
                                    <th>QR CODE</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Tổng tiền</th>
                                    <th>Tiền còn lại</th>
                                    <th>Chi tiết</th>
                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @foreach($orders as $order)
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="chk_child" value="option1">
                                            </div>
                                        </th>

                                        <td>{{$order->User}}</td>
                                        <td>{{$order->voucher}}</td>
                                        <td>{{$order->booking_fee}}</td>
                                        <td>{{$order->email}}</td>
                                        <td>{{$order->name}}</td>
                                        <td>{{$order->code}}</td>
                                        <td>{{$order->qr_code}}</td>
                                        <td>{{$order->status}}</td>
                                        <td>{{date('d-M-y', strtotime($order->start_date))}}</td>
                                        <td>{{date('d-M-y', strtotime($order->end_date))}}</td>
                                        <td>
                                            {{number_format($order->total_amount)}} VND
                                        </td>
                                        <td id="payable_amount_{{ $order->id }}">
                                            {{ $payable<=1 ? '0' :number_format($payable)  }} VND
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{route('orders.show',$order)}}" class="dropdown-item">
                                                            <i class="ri-eye-fill align-bottom me-2 text-muted"></i> Chi tiết</a>
                                                    </li>
                                                    <li><a class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                                    <li>
                                                        <a class="dropdown-item remove-item-btn">
                                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link href="{{ asset('theme/admin/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"
          type="text/css"/>
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

@endsection