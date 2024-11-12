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

                        <div class="table-responsive table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap" id="customerTable">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                        </div>
                                    </th>
                                    <th class="" data-sort="customer_name">User</th>
                                    <th class="" data-sort="email">voucher</th>
                                    <th class="" data-sort="phone">booking_fee</th>
                                    <th class="" data-sort="date">email</th>
                                    <th class="" data-sort="status">name</th>
                                    <th class="" data-sort="action">code</th>
                                    <th class="" data-sort="action">qr_code</th>
                                    <th class="" data-sort="action">status</th>
                                    <th class="" data-sort="action">start_date</th>
                                    <th class="" data-sort="action">end_date</th>
                                    <th class="" data-sort="action">check_in</th>
                                    <th class="" data-sort="action">check_out</th>
                                    <th class="" data-sort="action">total_amount</th>
{{--                                    <th class="" data-sort="action">created_at</th>--}}
{{--                                    <th class="" data-sort="action">updated_at</th>--}}
                                    <th class="" data-sort="action">Payable</th>
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

                                        <td class="customer_name">{{$order->User}}</td>
                                        <td class="phone">{{$order->voucher}}</td>
                                        <td class="date">{{$order->booking_fee}}</td>
                                        <td class="date">{{$order->email}}</td>
                                        <td class="date">{{$order->name}}</td>
                                        <td class="date">{{$order->code}}</td>
                                        <td class="date">{{$order->qr_code}}</td>
                                        <td class="date">{{$order->status}}</td>
                                        <td class="date">{{date('d-M-y', strtotime($order->start_date))}}</td>
                                        <td class="date">{{date('d-M-y', strtotime($order->end_date))}}</td>
                                        <td></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <div class="edit">
                                                    <button
                                                            class="btn btn-sm btn-success edit-item-btn"
                                                            onclick="checkoutOrder('{{ $order->id }}')"
                                                            id="checkout_button_{{ $order->id }}"
                                                    >
                                                        Checkout
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{number_format($order->total_amount)}}VND
                                        </td>
                                        <td id="payable_amount_{{ $order->id }}">
                                            {{ $payable ? number_format($payable) : 'N/A' }} VND
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="noresult" style="display: none">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2">Sorry! No Result Found</h5>
                                    <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find any orders for you search.</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <div class="pagination-wrap hstack gap-2">
                                <a class="page-item pagination-prev disabled" href="javascript:void(0);">
                                    Previous
                                </a>
                                <ul class="pagination listjs-pagination mb-0"></ul>
                                <a class="page-item pagination-next" href="javascript:void(0);">
                                    Next
                                </a>
                            </div>
                        </div>
                    </div>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
@endsection
@section('scripts')
    <script>
        function checkoutOrder(orderId) {
            // URL đến route `checkPayable`
            const url = `/admin/orders/check-payable/${orderId}`;

            // Gửi yêu cầu AJAX để tính toán và cập nhật số tiền phải trả
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Lấy phần tử checkout_button và check_out để cập nhật
                        const checkoutButton = document.getElementById(`checkout_button_${orderId}`);
                        // const checkOutTime = document.getElementById(`check_out_${orderId}`);
                        const payableAmount = document.getElementById(`payable_amount_${orderId}`);

                        // Ẩn nút checkout
                        if (checkoutButton) checkoutButton.style.display = 'none';

                        // Cập nhật thời gian hiện tại vào cột checkout
                        // const now = new Date();
                        // checkOutTime.innerText = now.toLocaleString();

                        // Cập nhật số tiền còn lại
                        payableAmount.innerText = `${data.payable.toLocaleString()} VND`;
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

@endsection