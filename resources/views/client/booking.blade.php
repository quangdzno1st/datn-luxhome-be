@extends('client.layouts.master')

@section('title')
    Đặt phòng
@endsection

<style>
    .voucher-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: box-shadow 0.3s, transform 0.3s;
        background-color: #ffffff;
    }

    .trip-info {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .voucher-card:hover {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .voucher-card.unavailable {
        background-color: #f8f9fa;
        opacity: 0.6;
        cursor: not-allowed;
    }

    .voucher-card .info h5 {
        margin: 0;
        font-size: 1.1em;
        font-weight: bold;
        color: #333;
    }

    .voucher-card .info p {
        margin: 0;
        font-size: 0.9em;
        color: #777;
    }

    .selected-voucher {
        margin-top: 15px;
        padding: 10px;
        border: 1px solid #0d6efd;
        border-radius: 5px;
        background-color: #e9f5ff;
        font-size: 1em;
    }

    .modal .modal-body {
        max-height: 70vh;
        overflow-y: auto;
        padding: 20px;
    }

    .modal .modal-body input {
        margin-bottom: 20px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        width: 100%;
    }

    .btn-apply {
        background-color: #0d6efd;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .btn-apply:hover {
        background-color: #0056b3;
        cursor: pointer;
    }

    .btn-disabled {
        background-color: #6c757d;
        cursor: not-allowed;
    }


    .voucher-container {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #ffffff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: 0 auto;
    }

    .voucher-btn {
        padding: 10px 20px;
        background-color: #007bff;
        color: #ffffff;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .voucher-btn:hover {
        background-color: #0056b3;
    }

    .voucher-info {
        flex-grow: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #333;
    }

    .voucher-info i {
        font-size: 18px;
        color: #ff5f57;
    }

    .voucher-trash {
        width: 44px;
        height: 44px;
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #e0f7f4;
        padding: 15px;
        border-radius: 50%;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .voucher-trash:hover {
        background-color: #ff5f57;
        color: #ffffff;
    }

    .voucher-trash i {
        color: #007bff;
        transition: color 0.3s;
    }

</style>

@section('content')
    <main class="main">
        <div class="wrap">
            <nav class="breadcrumbs">
                <ul>
                    <li><a href="#" title="Home">Trang chủ</a></li>
                    <li><a href="{{route('hotel.show', ['hotel_id' => $hotel->id, 'check' => 1, 'start_date' => session('start_date')?? \Carbon\Carbon::now()->format('Y-m-d'), 'end_date' =>session('end_date') ?? \Carbon\Carbon::tomorrow()->format('Y-m-d')])}}" title="Hotels">Khách sạn {{ $hotel->name }}</a></li>

                    <li>Thông tin hóa đơn</li>
                </ul>
            </nav>
            <div class="row">
                <div class="two-third">
                    <form id="booking" method="post" action="{{ route('orders.store') }}"
                          class="static-content booking">
                        @csrf
                        <fieldset>

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ session('error') }}</li>
                                    </ul>
                                </div>
                            @endif

                            @php
                                $user = Auth::user() ?? null;
                            @endphp

                            <h2>Thông tin hóa đơn</h2>
                            <div class="row">
                                <div class="f-item one-half">
                                    <label for="first_name">Họ Và Tên<span style="color: red">*</span></label>
                                    <input type="text" id="first_name" name="user_name"
                                           value="{{ old('user_name', $user?->name) }}"/>

                                    @error('user_name')
                                    <div class="text-danger" style="color:red">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="f-item one-half">
                                    <label for="last_name">Địa Chỉ Email<span style="color: red">*</span></label>
                                    <input type="text" id="last_name" name="user_email"
                                           value="{{ old('user_email', $user?->email) }}"/>

                                    @error('user_email')
                                    <div class="text-danger" style="color:red">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="f-item one-half">
                                    <label for="email">Số Điện Thoại<span style="color: red">*</span></label>
                                    <input type="number" id="email" name="user_phone_number"
                                           value="{{ old('user_phone_number', $user?->phone) }}"/>

                                    @error('user_phone_number')
                                    <div class="text-danger" style="color:red">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="f-item one-half">
                                    <strong><span>Chọn phiếu giảm giá</span></strong>
                                    <a type="button" data-bs-toggle="modal"
                                       data-bs-target="#voucherModal"><i class="fa-solid fa-square-plus fa-2xl"
                                                                         style="color: #ad1b01; margin: 0 5px"></i></a>
                                    <div style="display: flex; align-items: center; padding-top: 10px">
                                        <div id="selectedVoucher" style="display: none;">
                                            <strong> <i class="fa-solid fa-ticket fa-xl"
                                                        style="color: #fd7272; padding-right: 5px"></i></strong>
                                            <strong><span id="selectedVoucherCode"></span></strong>
                                            <a type="button" id="removeVoucher" class="btn btn-remove btn-sm"><i
                                                        class="fa-solid fa-trash-can fa-xl" style="color: #b30000;"></i>
                                            </a>
                                        </div>


                                        <input type="text" hidden name="total_amount" id="totalAmountDiscount"
                                               class="total" value="{{ $total_amount }}"/>
                                        <input hidden type="text" id="voucherId" name="voucher_id"/>

                                    </div>

                                    <div class="modal fade" id="voucherModal" tabindex="-1"
                                         aria-labelledby="voucherModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="voucherModalLabel">Danh sách
                                                        voucher</h5>
                                                    <a type="button" class="btn-close" data-bs-dismiss="modal"
                                                       aria-label="Close"></a>
                                                </div>
                                                <div class="modal-body">

                                                    <div id="" style="max-height: 70vh;">

                                                        @if(!empty($vouchers))
                                                            @foreach($vouchers as $voucher)
                                                                @php
                                                                    $isValid = $voucher['isValid'] == 1;
                                                                    $discountValue = number_format($voucher['discount_value']) . " VND";
                                                                    if ($voucher['discount_type']) {
                                                                         $desc = "Giảm giá " . $voucher['discount_value'] . '%, tối đa ' .  number_format($voucher['max_price']) . " VND";
                                                                    } else {
                                                                        $desc = "Giảm giá " .$discountValue;
                                                                    }
                                                                @endphp
                                                                <div class="voucher-card {{ $isValid ? '' : 'unavailable' }}"
                                                                     data-voucher="{{ $desc }}"
                                                                     data-discount="{{ $voucher['discount_value'] }}"
                                                                     data-discount-type="{{ $voucher['discount_type'] ? 'percentage' : 'fixed' }}"
                                                                     data-max-price="{{ $voucher['max_price'] }}"
                                                                     data-voucher-id="{{ $voucher['voucher_id'] }}">

                                                                    <div class="info"
                                                                         style="display: flex; justify-content: center;">
                                                                        <img width="120px"
                                                                             style="display:inline-block; object-fit: cover; height: 120px"
                                                                             src="{{Storage::url($voucher['thumbnail'])}}">

                                                                        <div style=" padding: 10px">
                                                                            <h5> {{ $desc }} </h5>
                                                                            <p style="padding-bottom: 5px; color: #0a0c0d">
                                                                                Đơn tối
                                                                                thiểu: {{ number_format($voucher['conditional_total_amount']) . ' VND' }}</p>
                                                                            <p>
                                                                                HSD: {{ \Carbon\Carbon::parse($voucher['start_date'])->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($voucher['end_date'])->format('d/m/Y') }}</p>
                                                                        </div>
                                                                    </div>

                                                                    <button type="button"
                                                                            class="btn btn-apply {{ $isValid ? 'btn-apply-action' : 'disabled' }}">{{ $isValid ? 'Áp dụng' : 'Không khả dụng' }}</button>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <p>Bạn chưa có phiếu giảm giá nào</p>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="info"></span>
                            </div>

                            <div class="row">
                                <div class="f-item full-width">
                                    <label>Ghi chú: </label>
                                    <textarea rows="10" cols="10" name="note">{{ old('note') }}</textarea>
                                </div>
                                <span class="info"></span>
                            </div>

                            <div class="row">
                                <div class="f-item full-width">
                                    <input type="submit" class="gradient-button" value="Thanh toán hóa đơn"
                                           id="next-step"/>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>

                <aside class="one-third right-sidebar booking">
                    <article class="hotel-details booking-details">

                        <h2>Thời gian quy định</h2>
                        <p>
                            <i class="far fa-calendar-alt"></i>
                            Check-in: 14:00 - 24:00<br>
                            <i class="far fa-calendar-alt"></i>
                            Check-out: 5:00 - 11:30<br>
                            Tiền tính thêm khi trả phòng quá thời gian: {{ $hotel['percent_incidental'] }}
                        </p>

                        <h2>Chính sách hủy phòng</h2>
                        <a href="#" class="gradient-button mb-3" data-bs-toggle="modal"
                           data-bs-target="#myModal">
                            Xem chính sách hủy phòng
                        </a>
                        <div class="modal fade" id="myModal">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">Chính sách hủy phòng</h4>
                                        <a type="button" class="btn-close" data-bs-dismiss="modal"></a>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <p>
                                            Yêu cầu hủy đặt phòng sẽ được gửi đến chủ khách
                                            sạn {{ $hotel->name}}.
                                        </p>
                                        <p>Chính sách hủy phòng: </p>
                                        <p>Hủy trong vòng từ 2-3 ngày trước ngày nhận phòng: </p>
                                        <ul>
                                            <li>
                                                Phí hủy phòng là 50% tổng số tiền đã thanh toán.
                                            </li>
                                            <li>
                                                Số tiền hoàn trả (nếu có) sẽ được chuyển khoản trong vòng 7 ngày
                                                làm việc.
                                            </li>
                                        </ul>
                                        <p>
                                            Hủy trong vòng 1 ngày trước ngày nhận phòng hoặc không đến:
                                        </p>
                                        <ul>
                                            <li>Không hoàn trả bất kỳ khoản thanh toán nào.</li>
                                        </ul>
                                        <p>
                                            Trường hợp đặc biệt:
                                        </p>
                                        <ul>
                                            <li>
                                                Nếu bạn cần thay đổi hoặc hủy đặt phòng do lý do bất khả kháng (thiên tai, dịch bệnh, v.v.),
                                                vui lòng liên hệ bộ phận hỗ trợ của khách sạn để được xem xét và xử lý.
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>



                        <h2 class="">Chuyến đi</h2>
                        <div>
                            @php
                                $room = $roomBooking[0];
                            @endphp
                            <p><strong>{{ $room['hotel_name'] }}</strong></p>
                            <p>
                                <i class="far fa-calendar-alt"></i>
                                {{ \Carbon\Carbon::parse($room['start_date'])->format('d/m/Y') }}
                                đến
                                {{ \Carbon\Carbon::parse($room['end_date'])->format('d/m/Y') }}
                            </p>
                            <p>
                                <i class="far fa-calendar-alt"></i>
                                {{ (new DateTime($room['end_date']))->diff(new DateTime($room['start_date']))->days }}
                                đêm
                            </p>

                            @php
                                $total_amount = 0;
                            @endphp
                            @foreach($roomBooking as $room)
                                @php
                                    $total_amount += $room['price'];
                                @endphp
                                <div class="trip-info">
                                    <div style="display: flex; justify-content: space-between; align-items: center">
                                        <h5>{{ $room['code'] }}</h5>
                                        <h6 class="total-cost">{{number_format($room['price'])}} đ / đêm</h6>
                                    </div>
                                    <p><i class="fas fa-bed"></i> x1 Phòng suite</p>
                                    <p><i class="fas fa-user"></i> Người lớn: {{ $room['number_adult'] }}, Trẻ
                                        em: {{ $room['number_child'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        <h2>Dịch vụ</h2>
                        @if(!empty($servicesQty))
                            @foreach($servicesQty as $key => $qty)
                                @php
                                    $serviceInfo = $servicesInfo[$key];
                                    $total_amount += $serviceInfo['price']*$qty;
                                @endphp
                                <div class="trip-info">
                                    <div class="service-info">
                                        <span class="service-name"><strong>{{ $serviceInfo['name'] }}</strong></span>
                                        <span class="service-price"><strong>{{ number_format($serviceInfo['price']) }} đ</strong></span>
                                    </div>
                                    <div class="service-quantity">
                                        Số lượng: <span class="quantity-value">{{ $qty }}</span>
                                    </div>
                                    <div class="service-total">
                                        Thành tiền: <span class="total-value">{{ number_format($qty * $serviceInfo['price']) }} đ</span>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <div class="price">
                            <p class="total">Tổng tiền: {{ number_format($total_amount) . ' đ' }}</p>
                        </div>


                    </article>
                </aside>
            </div>
        </div>
    </main>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {

        let selectedVoucherCode = null;
        let totalAmount = {{ $total_amount }}; // Giá trị tổng tiền gốc từ Blade
        const totalElement = document.querySelector('.price .total');
        const voucherIdInput = document.querySelector('#voucherId'); // Lấy input ẩn cho voucherId
        const totalAmountDiscount = document.querySelector("#totalAmountDiscount");

        // Áp dụng voucher
        document.querySelectorAll('.btn-apply-action').forEach(button => {
            button.addEventListener('click', (event) => {
                const voucherCard = event.target.closest('.voucher-card');
                const voucherCode = voucherCard.getAttribute('data-voucher');
                const discountValue = parseFloat(voucherCard.getAttribute('data-discount')) || 0; // Lấy giá trị giảm
                const discountType = voucherCard.getAttribute('data-discount-type') || ''; // Phần trăm hoặc cố định
                const maxPrice = parseFloat(voucherCard.getAttribute('data-max-price')) || 0; // Giá trị giảm tối đa nếu có
                const voucherId = voucherCard.getAttribute('data-voucher-id'); // Lấy ID voucher từ thuộc tính của voucherCard


                // Reset lại voucher trước đó nếu đã chọn
                if (selectedVoucherCode !== null) {
                    resetVoucherButtons();
                }

                // Hiển thị thông tin voucher đã chọn
                selectedVoucherCode = voucherCode;
                document.querySelector('#selectedVoucher').style.display = 'block';
                document.querySelector('#selectedVoucherCode').textContent = voucherCode;

                // Cập nhật tổng tiền
                const newTotal = calculateDiscountedTotal(totalAmount, discountValue, discountType, maxPrice);
                updateTotalPrice(newTotal);
                console.log(totalAmountDiscount.value)


                // Thay đổi trạng thái của nút đã áp dụng
                event.target.textContent = 'Đang sử dụng';
                event.target.classList.add('btn-disabled');
                event.target.setAttribute('disabled', true);

                // Cập nhật input voucherId với ID voucher đã chọn
                voucherIdInput.value = voucherId;

                // Đóng modal
                const modal = document.querySelector('#voucherModal');
                const bootstrapModal = bootstrap.Modal.getInstance(modal);
                bootstrapModal.hide();
            });
        });

        // Xóa voucher đã chọn
        document.querySelector('#removeVoucher').addEventListener('click', () => {
            selectedVoucherCode = null;
            document.querySelector('#selectedVoucher').style.display = 'none';
            resetVoucherButtons();

            // Khôi phục tổng tiền gốc
            updateTotalPrice(totalAmount);

            // Xóa voucherId trong input
            voucherIdInput.value = '';
        });

        // Reset lại trạng thái nút của voucher
        function resetVoucherButtons() {
            document.querySelectorAll('.voucher-card .btn-apply-action').forEach(button => {
                button.textContent = 'Áp dụng';
                button.classList.remove('btn-disabled');
                button.classList.add('btn-apply');
                button.removeAttribute('disabled');
            });
        }

        // Cập nhật tổng tiền hiển thị
        function updateTotalPrice(newTotal) {
            totalElement.textContent = `Tổng tiền: ${new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
            }).format(newTotal)}`;

        }

        // Hàm tính tổng tiền sau giảm giá
        function calculateDiscountedTotal(total, discountValue, discountType, maxPrice) {
            let discountAmount = 0;

            if (discountType === 'percentage') {
                discountAmount = (total * discountValue) / 100;
                if (maxPrice > 0 && discountAmount > maxPrice) {
                    discountAmount = maxPrice;
                }
            } else {
                discountAmount = discountValue;
            }

            return Math.max(total - discountAmount, 0); // Không cho phép tổng tiền âm
        }
    });

</script>
