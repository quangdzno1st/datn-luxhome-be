@extends('admin.layouts.master')
@section('content')
    <div class="card">
        <div class="row">
            <div class="col-md-12">
{{--                @include('backend.layouts.notification')--}}
            </div>
        </div>
        <h5 class="card-header">{{ __('Order detail') }}</h5>
        @if (session('success'))
            <div class="card-header  alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success')}} với chi phí phát sinh là {{number_format(session('incidental_costs'))}}VND
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body">
            @if($order)
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>{{ __("Name") }}</th>
                        <th>{{ __("Email") }}</th>
                        <th>{{ __("Voucher") }}</th>
                        <th>{{ __("Status") }}</th>
                        <th>{{ __("Phí phòng") }}</th>
                        <th>{{ __("Tổng tiền") }}</th>
                        <th>{{ __("Ngày checkin") }}</th>
                        <th>{{ __("Ngày checkout") }}</th>
                        <th>{{ __("Tiền còn lại") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$order->name}}</td>
                        <td>{{$order->email}}</td>
                        <td>{{$order->quantity}}</td>
                        <td>
                            {{$order->status}}
                        </td>
                        <td>{{number_format($order->booking_fee)}}VND</td>
                        <td>{{number_format($order->total_amount)}}VND</td>
                        <td>
                            @if($order->check_in==null)
                                <div class="d-flex gap-2">
                                    <div class="edit" id="check_out">
                                        <a class="btn btn-sm btn-info edit-item-btn" data-bs-toggle="modal" href="#checkinOrder">
                                            Check-in
                                        </a>
                                    </div>
                                </div>
                            @else
                                {{date('d-M-y', strtotime($order->check_in))}}
                            @endif</td>
                        <td>
                            @if($order->check_out==null)
                            <div class="d-flex gap-2">
                                <div class="edit" id="check_out">
                                    <a class="btn btn-sm btn-success edit-item-btn" data-bs-toggle="modal" href="#checkoutOrder">
                                        Check-out
                                    </a>
                                </div>
                            </div>
                            @else
                                {{date('d-M-y', strtotime($order->check_out))}}
                            @endif
                        </td>
                        <td>
                            {{number_format($order->payable)}} VND
                        </td>
                    </tr>
                    </tbody>
                </table>

                <section class="confirmation_part section_padding">
                    <div class="order_boxes">
                        <div class="row justify-content-between">
                            <div class="col-lg-6 col-lx-4 mb-3"  style="background-color: rgba(0, 0, 0, .05);flex: 0 0 49%;  ">
                                <div class="order-info">
                                    <h4 class="text-center pb-4">{{ __('Thông tin order items') }}</h4>
                                    <table class="table">
                                        <tr>
                                            <td>Loại phòng</td>
                                            <td>Số lượng</td>
                                            <td>Giá</td>
                                        </tr>
                                        @foreach($orderItemInfo as $orderItem)
                                        <tr class="">
                                            <td>{{ $orderItem->catalogueName }}</td>
                                            <td>{{ $orderItem->orderItemQuantity }}</td>
                                            <td>{{ $orderItem->cataloguePrice }}</td>
                                        </tr>
                                        @endforeach
                                            <tr>
                                                <td>Tổng</td>
                                                <td></td>
                                                <td>{{$sumOrderItem}}</td>
                                            </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6 col-lx-4 mb-3"  style="background-color: rgba(0, 0, 0, .05);flex: 0 0 49%;">
                                <div class="shipping-info">
                                    <h4 class="text-center pb-4">{{ __("Thông tin khách hàng") }}</h4>
                                    <table class="table">
                                        <tr>
                                            <td>{{ __('Name') }}</td>
                                            <td> : {{$order->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Email') }}</td>
                                            <td> : {{$order->email}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Phone No.') }}</td>
                                            <td> : {{$order->phone}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6 col-lx-4"  style="background-color: rgba(0, 0, 0, .05);flex: 0 0 49%;">
                                <div class="shipping-info">
                                    <h4 class="text-center pb-4">{{ __("Thông tin services") }}</h4>
                                    <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            id="create-btn" data-bs-target="#showModal"
                                    >Thêm service</button>
                                    <table class="table">
                                        <tr>
                                            <td>Tên dịch vụ</td>
                                            <td>Số lượng</td>
                                            <td>Trạng thái</td>
                                            <td>Giá dịch vụ</td>
                                        </tr>
                                        @foreach($servicesInfo as $service)
                                        <tr>
                                            <td>{{ $service->serviceName }}</td>
                                            <td>{{$service->serviceQuantity}}</td>
                                            <td>{{$service->status}}</td>
                                            <td>{{$service->servicePrice}}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td>Tổng</td>
                                            <td></td>
                                            <td></td>
                                            <td>{{$sumService}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            @if($voucher!=null)
                                <div class="col-lg-6 col-lx-4"  style="background-color: rgba(0, 0, 0, .05);flex: 0 0 49%;">
                                    <div class="shipping-info">
                                        <h4 class="text-center pb-4">{{ __("Thông tin voucher") }}</h4>
                                        <table class="table">
                                            <tr>
                                                <td>Loại giảm giá</td>
                                                <td>Giảm</td>
                                                <td>Mô tả</td>
                                            </tr>
                                            @foreach($voucher as $voucherItem)
                                                <tr>
                                                    @if(!$voucherItem->discount_type)
                                                        <td>Tiền</td>
                                                        <td>{{ number_format($voucherItem->discount_value) }}VND</td>
                                                    @else
                                                        <td>Phần trăm</td>
                                                        <td>{{ $voucherItem->discount_value }}%</td>
                                                    @endif
                                                    <td>{{$voucherItem->description}}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
{{--                @include('admin.orders.order_items.order_items')--}}
            @endif
                <!-- Modal -->s
{{--                notificate--}}
                <div class="modal fade flip" id="checkoutOrder" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body p-5 text-center">
                                <i class="fa-solid fa-money-bill-wave"></i>
                                <div class="mt-4 text-center">
                                    <h4>Số tiền cần check out là {{number_format($payable)}}VND!</h4>
                                    <p class="text-muted fs-15 mb-4">Bạn có muốn checkout order này không?</p>
                                    <div class="hstack gap-2 justify-content-center remove">
                                        <button class="btn btn-link link-success fw-medium text-decoration-none" id="deleteRecord-close" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Đóng</button>
                                        <form method="POST" action="{{route('orders.checkout',$order->id)}}">
                                            @csrf
                                            <button
                                                    class="btn btn-sm btn-success edit-item-btn"
                                                    type="submit"
                                            >
                                                Checkout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade flip" id="checkinOrder" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body p-5 text-center">
                                <i class="fa-solid fa-money-bill-wave"></i>
                                <div class="mt-4 text-center">
                                    <h4>Bạn có muốn checkin order này không?</h4>
                                    <p class="text-muted fs-15 mb-4">Khi checkin sẽ tính thời gian từ thời điểm hiện tại!</p>
                                    <div class="hstack gap-2 justify-content-center remove">
                                        <button class="btn btn-link link-success fw-medium text-decoration-none" id="deleteRecord-close" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Đóng</button>
                                        <form method="POST" action="{{route('orders.checkin',$order->id)}}">
                                            @csrf
                                            <button
                                                    class="btn btn-sm btn-success edit-item-btn"
                                                    type="submit"
                                            >
                                                Check-in
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{--                form add service--}}
                <div class="modal fade" id="showModal" tabindex="-1"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-light p-3">
                                <h5 class="modal-title">Thêm dịch vụ</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close" id="close-modal"></button>
                            </div>
                            <form class="tablelist-form" autocomplete="off"
                                  action="{{route('orders.addBookingServices',$order->id)}}"
                                  method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <label>Chọn dịch vụ</label><br>
                                            @foreach($services as $service)
                                                <input
                                                        type="checkbox"
                                                        class="custom-control-input"
                                                        id="customCheck{{ $service->id }}"
                                                        name="services[]"
                                                        value="{{ $service->id }}"
                                                        {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="customCheck{{ $service->id }}">
                                                    {{ $service->name }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Số lượng</label>
                                        <input name="quantity" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Nhập số lượng dịch vụ">
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Phòng</label>
                                        <select class="form-select" aria-label="Default select example" name="roomId">
                                            @foreach($roomCode as $room)
                                                <option value="{{$room->roomId}}">{{$room->roomCode}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Trạng thái</label>
                                        <select class="form-select" aria-label="Default select example" name="status">
                                            <option value="1">Chưa thanh toán</option>
                                            <option value="2">Đã thanh toán</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Đóng
                                        </button>
                                        <button type="submit" class="btn btn-success"
                                                id="add-btn">Cập Nhật Dịch Vụ
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection
{{--@section('scripts')--}}

{{--    <script>--}}
{{--        function checkoutOrder(orderId) {--}}
{{--            // URL đến route `checkPayable`--}}
{{--            const url = `/admin/orders/check-payable/${orderId}`;--}}

{{--                        console.log(1)--}}
{{--            fetch(url)--}}
{{--                .then(response => response.json())--}}
{{--                .then(data => {--}}
{{--                    if (data.success) {--}}
{{--                        // Lấy phần tử checkout_button và check_out để cập nhật--}}
{{--                        const checkoutButton = document.getElementById(`checkout_button_${orderId}`);--}}
{{--                        // const checkOutTime = document.getElementById(`check_out_${orderId}`);--}}
{{--                        const payableAmount = document.getElementById(`payable_amount_${orderId}`);--}}
{{--                        const check_out=document.getElementById('check_out');--}}
{{--                        // Ẩn nút checkout--}}
{{--                        if (checkoutButton) checkoutButton.style.display = 'none';--}}

{{--                        // Cập nhật thời gian hiện tại vào cột checkout--}}
{{--                        const now = new Date();--}}
{{--                        check_out.innerText = now.toLocaleString();--}}

{{--                        // Cập nhật số tiền còn lại--}}
{{--                        payableAmount.innerText = `${data.payable} VND`;--}}
{{--                    }--}}
{{--                })--}}
{{--                .catch(error => console.error('Error:', error));--}}
{{--        }--}}
{{--        new DataTable("#example", {--}}
{{--            paging: false,--}}
{{--            info: false,--}}
{{--            searching: false,--}}
{{--            order: [--}}
{{--                [0, 'desc']--}}
{{--            ]--}}
{{--        });--}}
{{--    </script>--}}

{{--@endsection--}}
@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--datatable js-->

    <script src="{{asset('theme/admin/assets/js/pages/datatables.init.js')}}"></script>
    <!-- App js -->
    <script src="{{asset('theme/admin/assets/js/app.js')}}"></script>

@endsection