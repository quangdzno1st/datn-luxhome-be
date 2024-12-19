@extends('admin.layouts.master')
@section('title')
    Chi tiết đơn đặt
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Chi tiết đơn đặt</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Đơn đặt</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="header d-flex justify-content-between align-items-center">
            <h5 class="card-header mb-0">{{ __('Thông tin chi tiết đơn đặt') }}</h5>
            <a href="{{ route('admin.orders.index') }}">
                <button type="button" class="btn btn-primary bg-gradient waves-effect waves-primary me-2" data-bs-toggle="modal"
                        id="create-btn" >
                    <i class="ri-arrow-left-s-line"></i> Danh sách
                </button>
            </a>
        </div>

    @if (session('success-checkout'))
            <div class="card-header  alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success-checkout')}} với phí phạt là {{number_format(session('incidental_costs'))}}VND
                checkout muộn {{session('extraHours')}}h (mỗi giờ checkout muộn tính {{session('percent_incidental')}}% tiền phòng)
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body">
            @if($order)
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>{{ __("Mã đơn đặt") }}</th>
                        <th>{{ __("Trạng thái") }}</th>
                        <th>{{ __("Tổng tiền") }}</th>
                        <th>{{ __("Tiền thực nhận") }}</th>
                        <th>{{ __("Ngày bắt đầu") }}</th>
                        <th>{{ __("Ngày kết thúc") }}</th>
                        <th>{{ __("Ngày checkin") }}</th>
                        <th>{{ __("Ngày checkout") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$order->code}}</td>
                        <td>
                            {{$order->status}}
                        </td>
                        <td>{{number_format($order->total_amount)}}VND</td>
                        <td>{{number_format($order->net_amount)}}VND</td>
                        <td>{{\Carbon\Carbon::parse($order->start_date)->format('H:i:s d-m-Y')}}</td>
                        <td>{{\Carbon\Carbon::parse($order->end_date)->format('H:i:s d-m-Y')}}</td>
                        <td>
                            @if($order->status=='Đã hủy')
                                <span class="badge bg-danger">Đã hủy</span>
                            @elseif($order->status=='Đang chờ')
                                <span class="badge bg-warning">Chưa xác nhận</span>
                            @elseif($order->check_in==null)
                                <div class="d-flex gap-2">
                                    <div class="edit" id="check_out">
                                        <a class="btn btn-sm btn-info edit-item-btn" data-bs-toggle="modal" href="#checkinOrder">
                                            Check-in
                                        </a>
                                    </div>
                                </div>
                            @else
                                {{\Carbon\Carbon::parse($order->check_in)->format('H:i:s d-m-Y')}}
                            @endif</td>
                        <td>
                            @if($order->status=='Đã hủy')
                                <span class="badge bg-danger">Đã hủy</span>
                            @elseif($order->check_out==null&&$order->check_in==null)
                                <div class="d-flex gap-2">
                                    <span class="badge bg-warning">Phải checkin trước</span>
                                </div>
                            @elseif($order->check_out==null)
                                <div class="d-flex gap-2">
                                    <div class="edit" id="check_out">
                                        <a class="btn btn-sm btn-success edit-item-btn" data-bs-toggle="modal" href="#checkoutOrder">
                                            Check-out
                                        </a>
                                    </div>
                                </div>
                            @else
                                {{\Carbon\Carbon::parse($order->check_out)->format('H:i:s d-m-Y')}}
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>

                <section class="confirmation_part section_padding">
                    <div class="order_boxes">
                        <div class="row justify-content-between">
                            <div class="col-lg-6 col-lx-4 mb-3 pt-3"  style="background-color: rgba(0, 0, 0, .05);flex: 0 0 49%;  ">
                                <div class="order-info">
                                    <h4 class="text-center pb-4">{{ __('Thông tin từng phòng') }}</h4>
                                    <table class="table">
                                        <tr>
                                            <td>Mã phòng</td>
                                            <td>Loại phòng</td>
                                            <td>Số lượng</td>
                                            <td>Giá</td>
                                        </tr>
                                        @foreach($orderItemInfo as $orderItem)
                                        <tr class="">
                                            <td>{{ $orderItem->roomCodes }}</td>
                                            <td>{{ $orderItem->catalogueName }}</td>
                                            <td>{{ $orderItem->totalQuantity }}</td>
                                            <td>{{ number_format($orderItem->cataloguePrice) }}VND</td>
                                        </tr>
                                        @endforeach
                                            <tr>
                                                <td>Tổng</td>
                                                <td></td>
                                                <td></td>
                                                <td>{{number_format($sumOrderItem)}}VND</td>
                                            </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6 col-lx-4 mb-3 pt-3"  style="background-color: rgba(0, 0, 0, .05);flex: 0 0 49%;">
                                <div class="shipping-info">
                                    <h4 class="text-center pb-4">{{ __("Thông tin khách hàng") }}</h4>
                                    <table class="table">
                                        <tr>
                                            <td>{{ __('Tên') }}</td>
                                            <td> : {{$order->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Email') }}</td>
                                            <td> : {{$order->email}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Số điện thoại') }}</td>
                                            <td> : {{$order->phone}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-6 col-lx-4"  style="background-color: rgba(0, 0, 0, .05);flex: 0 0 49%;">
                                <div class="shipping-info">
                                    <h4 class="text-center pb-4 pt-3">{{ __("Thông tin dịch vụ") }}</h4>
                                    @if(!is_null($order->check_in) && $order->status!='Hoàn thành')
                                    <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            id="create-btn" data-bs-target="#service{{$order->id}}"
                                    >Thêm service</button>
                                    @endif
                                    <table class="table">
                                        <tr>
                                            <td>Tên dịch vụ</td>
                                            <td>Phòng</td>
                                            <td>Trạng thái</td>
                                            <td>Giá dịch vụ</td>
                                        </tr>
                                        @foreach($servicesInfo as $service)
                                        <tr>
{{--                                            <td><span class="badge bg-danger">{{ $service->status }}</span></td>--}}
                                            <td>{{ $service->serviceName }}</td>
                                            <td>{{ $service->roomCode }}</td>
                                            @if($service->status==1)
                                                <td><span class="badge bg-danger">Chưa thanh toán</span></td>
                                            @else
                                                <td><span class="badge bg-success">Đã thanh toán</span></td>
                                            @endif
                                            <td>{{number_format($service->servicePrice)}}VND</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td>Tổng tiền chưa thanh toán</td>
                                            <td></td>
                                            <td></td>
                                            <td>{{number_format($sumServiceNotPayment)}}VND</td>
                                        </tr>
                                        <tr>
                                            <td>Tổng</td>
                                            <td></td>
                                            <td></td>
                                            <td>{{number_format($sumService)}}VND</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            @if($voucher!=null)
                                <div class="col-lg-6 col-lx-4"  style="background-color: rgba(0, 0, 0, .05);flex: 0 0 49%;">
                                    <div class="shipping-info">
                                        <h4 class="text-center pb-4 pt-3">{{ __("Thông tin mã giảm giá") }}</h4>
                                        <table class="table">
                                            <tr>
                                                <td>Loại giảm giá</td>
                                                <td>Giảm</td>
                                                <td>Giảm tối đa</td>
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
                                                        <td>{{number_format($voucherItem->max_price)}} VND</td>
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
            @endif
                <!-- Modal -->
{{--                notificate--}}
                <div class="modal fade flip" id="checkoutOrder" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body p-5 text-center">
                                <i class="fa-solid fa-money-bill-wave"></i>
                                <div class="mt-4 text-center">
                                    <h4>Bạn có muốn checkout order này không?</h4>
                                    <p class="text-muted fs-15 mb-4">Thời gian checkout sẽ tính từ thời điểm này!</p>
                                    <div class="hstack gap-2 justify-content-center remove">
                                        <button class="btn btn-link link-success fw-medium text-decoration-none" id="deleteRecord-close" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Đóng</button>
                                        <form method="POST" action="{{route('admin.orders.checkout',$order->id)}}">
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
                <div class="modal fade flip" id="checkoutOrderHaventCheckin" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body p-5 text-center">
                                <i class="fa-solid fa-money-bill-wave"></i>
                                <div class="mt-4 text-center">
                                    <div class="modal-content">
                                        <div class="modal-body p-5 text-center">
                                            <i class="fa-solid fa-money-bill-wave"></i>
                                            <div class="mt-4 text-center">
                                                <h4>Bạn có phải checkin trước mới checkout được!</h4>
                                                <p class="text-muted fs-15 mb-4">Khi checkin sẽ tính thời gian từ thời điểm hiện tại!</p>
                                            </div>
                                        </div>
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
                                        <form method="POST" action="{{route('admin.orders.checkin',$order->id)}}">
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
                <div class="modal fade" id="service{{$order->id}}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-light p-3">
                                <h5 class="modal-title">Thêm dịch vụ</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form class="tablelist-form" autocomplete="off" action="{{ route('admin.orders.addBookingServices', $order->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <!-- Chọn phòng -->
                                    <div class="mb-3">
                                        <label for="roomId" class="form-label fw-bold">Phòng</label>
                                        <select class="form-select" id="roomId" name="roomId" data-order-id="{{ $order->id }}">
                                            <option value="" selected>Chọn phòng</option>
                                            @foreach($roomCode as $room)
                                                <option value="{{ $room->roomId }}">{{ $room->roomCode }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Chọn dịch vụ -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Chọn dịch vụ</label>
                                        <div id="services-container" class="border p-3 rounded bg-light">
                                            <!-- Dịch vụ sẽ được hiển thị ở đây -->
                                        </div>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-success">Cập Nhật Dịch Vụ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

        </div>
    </div>
@endsection
@section('scripts')

    <script>
        function checkoutOrder(orderId) {
            // URL đến route `checkPayable`
            const url = `/admin/orders/check-payable/${orderId}`;

                        console.log(1)
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Lấy phần tử checkout_button và check_out để cập nhật
                        const checkoutButton = document.getElementById(`checkout_button_${orderId}`);
                        // const checkOutTime = document.getElementById(`check_out_${orderId}`);
                        const payableAmount = document.getElementById(`payable_amount_${orderId}`);
                        const check_out=document.getElementById('check_out');
                        // Ẩn nút checkout
                        if (checkoutButton) checkoutButton.style.display = 'none';

                        // Cập nhật thời gian hiện tại vào cột checkout
                        const now = new Date();
                        check_out.innerText = now.toLocaleString();

                        // Cập nhật số tiền còn lại
                        payableAmount.innerText = `${data.payable} VND`;
                    }
                })
                .catch(error => console.error('Error:', error));
        }
        new DataTable("#example", {
            paging: false,
            info: false,
            searching: false,
            order: [
                [0, 'desc']
            ]
        });
    </script>

@endsection
@section('script-libs')
    <script>
        document.getElementById('roomId').addEventListener('change', function () {
            const roomId = this.value;
            const orderId = this.getAttribute('data-order-id');
            const servicesContainer = document.getElementById('services-container');

            servicesContainer.innerHTML = '<p>Loading...</p>';

            if (roomId) {
                fetch(`${orderId}/available-services?roomId=${roomId}`)
                    .then(response => response.json())
                    .then(data => {
                        servicesContainer.innerHTML = '';

                        if (data.services && data.services.length > 0) {
                            data.services.forEach(service => {
                                const checkbox = `
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="service${service.id}" name="services[]" value="${service.id}">
                                <label class="form-check-label" for="service${service.id}">
                                    ${service.name} - ${new Intl.NumberFormat('vi-VN', { maximumFractionDigits: 0 }).format(service.price)} VND
                                </label>
                            </div>


                        `;
                                servicesContainer.innerHTML += checkbox;
                            });
                        } else {
                            servicesContainer.innerHTML = '<p>Không có dịch vụ nào khả dụng.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        servicesContainer.innerHTML = '<p>Đã xảy ra lỗi khi tải dịch vụ.</p>';
                    });
            } else {
                servicesContainer.innerHTML = '<p>Vui lòng chọn phòng.</p>';
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--datatable js-->

    <script src="{{asset('theme/admin/assets/js/pages/datatables.init.js')}}"></script>
    <!-- App js -->
    <script src="{{asset('theme/admin/assets/js/app.js')}}"></script>

@endsection