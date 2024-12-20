@extends('client.layouts.master')

@section('title')
    Chọn dịch vụ
@endsection

<style>
    /* Dịch vụ (item) */
    .dropdown-item {
        display: inline-block;
        /* Cho phép các item xếp ngang */
        width: 250px;
        /* Đặt chiều rộng cụ thể */
        text-align: center;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 15px;
        white-space: normal;
        /* Nội dung xuống dòng trong item */
    }

    /* Hình ảnh */
    .dropdown-item img {
        width: 100%;
        /* Chiều rộng full trong item */
        border-radius: 8px;
        margin-bottom: 10px;
        object-fit: cover;
    }

    /* Thông tin dịch vụ */
    .service-info h4 {
        font-size: 16px;
        color: #333;
        margin: 5px 0;
    }

    .service-info p {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #666;
    }

    .service-info {
        display: flex;
        justify-content: space-between;
    }

    /* Checkbox */
    .service-actions input[type="checkbox"] {
        margin-top: 10px;
        transform: scale(1.2);
        /* Tăng kích thước checkbox */
    }

    .card-item {
        --border-radius: 0.75rem;
        --primary-color: #7257fa;
        --secondary-color: #3c3852;
        min-width: 210px;
        max-width: 210px;
        font-family: "Arial";
        padding: 1rem;
        cursor: pointer;
        border-radius: var(--border-radius);
        background: #ffffff;
        /* Đặt nền trắng */
        box-shadow: 0px 8px 20px 0px rgba(0, 0, 0, 0.1);
        /* Tăng độ mờ và nổi bật box-shadow */
        position: relative;
    }

    .card-item > * + * {
        margin-top: 1.1em;
    }

    .card-item .card__content {
        color: var(--secondary-color);
        font-size: 0.86rem;
    }

    .card-item .card__title {
        padding: 0;
        font-size: 1.3rem;
        font-weight: bold;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .card-item .card__date {
        color: #6e6b80;
        font-size: 0.8rem;
    }

    .card__content img {
        width: 100%;
    }

    .card-item .card__arrow {
        position: absolute;
        background: var(--primary-color);
        padding: 0.4rem;
        border-top-left-radius: var(--border-radius);
        border-bottom-right-radius: var(--border-radius);
        bottom: 0;
        right: 0;
        transition: 0.2s;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .card-item svg {
        transition: 0.2s;
    }

    /* hover */
    .card-item:hover .card__title {
        color: var(--primary-color);
    }

    .card-item:hover .card__arrow {
        background: #111;
    }

    .card-item:hover .card__arrow svg {
        transform: translateX(3px);
    }

    .right-sidebar .trip-info div {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .trip-info {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .toggle-service .arrow-icon {
        display: inline-block;
        transition: transform 0.3s ease;
    }

    .toggle-service.active .arrow-icon {
        transform: rotate(180deg);
        /* Xoay mũi tên lên */
    }
</style>

@section('content')
    <!--main-->
    <main class="main">
        <div class="wrap">
            <!--breadcrumbs-->
            <nav class="breadcrumbs">
                <!--crumbs-->
                <ul>
                    <li><a href="{{route('home.index')}}" title="Home">Trang chủ</a></li>
                    <li><a href="{{route('hotel.show', ['hotel_id' => $hotel->id, 'check' => 1, 'start_date' => session('start_date')?? \Carbon\Carbon::now()->format('Y-m-d'), 'end_date' =>session('end_date') ?? \Carbon\Carbon::tomorrow()->format('Y-m-d')])}}" title="Hotels">Khách sạn {{ $hotel->name }}</a></li>
                    <li>Đặt dịch vụ</li>
                </ul>
                <!--//crumbs-->
            </nav>
            <!--//breadcrumbs-->
            <div class="row">
                <!--three-fourth content-->
                <div class="">
                    <form id="booking" method="get" action="{{ route('orders.confirm') }}" class=" booking">
                        @csrf
                        <div class="" style="display: grid;grid-template-columns: 2fr 1fr;width: 100%; gap: 10px;">
                            <div class=" hotel-details"
                                 style="padding: 15px 20px; overflow: hidden; height: fit-content">
                                <h2>
                                    Dịch vụ mua thêm
                                </h2>
                                <div id="accordion" style="display: flex; flex-direction: column; grid-gap: 5px">
                                    @foreach($roomsOrder as $room)
                                        <div class="card">
                                            <div class="card-header" style="background-color:#fff;">
                                                <div class="">
                                                    <h3 style="color: #000000; padding-left: 0;">{{ $room['code'] }}</h3>
                                                    <div style="display: flex; justify-content: space-between">
                                                        <p style="padding-bottom: 0;">Loại
                                                            phòng: {{ $room['catalogue_room_name'] }}</p>
                                                        <a style="padding-bottom: 0;" data-bs-toggle="collapse"
                                                           href="#{{ $room['code'] }}" class="toggle-service">
                                                            Thêm dịch vụ
                                                            <span class="arrow-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                                 fill="none" width="15" height="15">
                                                                <path fill="#000" d="M12 16L6 10h12l-6 6z"></path>
                                                            </svg>
                                                        </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="{{ $room['code'] }}" class="collapse" data-bs-parent="#accordion">
                                                <div class="card-body"
                                                     style="justify-content: space-between; align-items: center;display: flex;overflow-x: scroll;gap: 25px;">
                                                    @foreach($services as $service)
                                                        <div class="card-item">
                                                            <h3 class="card__title" style="color: #000000; "
                                                                title="{{ $service['name'] }}">
                                                                {{ $service['name'] }}
                                                            </h3>
                                                            <p class="card__content" style="padding-bottom: 0;">
                                                                <img src="https://storage.googleapis.com/a1aa/image/fTBDgwUBPzxJDiH8nDgitf4wy92lU2H3thdHLP91P0LfLrknA.jpg"
                                                                     alt="{{ $service['name'] }}">
                                                            </p>

                                                            <div class="card__date">
                                                                <h6 style="font-size: 12px">Đơn
                                                                    giá: {{ number_format($service['price']) . ' đ' }}/
                                                                    đơn đặt</h6>
                                                            </div>
                                                            <input type="checkbox"
                                                                   name="services[{{ $room['room_id'] }}][{{ $service['id'] }}]"
                                                                   value="1" hidden>

                                                            <div class="card__arrow">
                                                                <a class="arrow-button"
                                                                   data-service-id="{{ $service['id'] }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                         viewBox="0 0 24 24" height="15" width="15">
                                                                        <path fill="#fff"
                                                                              d="M13.4697 17.9697C13.1768 18.2626 13.1768 18.7374 13.4697 19.0303C13.7626 19.3232 14.2374 19.3232 14.5303 19.0303L20.3232 13.2374C21.0066 12.554 21.0066 11.446 20.3232 10.7626L14.5303 4.96967C14.2374 4.67678 13.7626 4.67678 13.4697 4.96967C13.1768 5.26256 13.1768 5.73744 13.4697 6.03033L18.6893 11.25H4C3.58579 11.25 3.25 11.5858 3.25 12C3.25 12.4142 3.58579 12.75 4 12.75H18.6893L13.4697 17.9697Z">
                                                                        </path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!--//three-fourth content-->

                            <!--right sidebar-->
                            <aside class="right-sidebar booking">
                                <!--Booking details-->
                                <article class="hotel-details booking-details">

                                    <h2>Thời gian quy định</h2>
                                    <p>
                                        <i class="far fa-calendar-alt"></i>
                                        Check-in: 14:00 - 24:00<br>
                                        <i class="far fa-calendar-alt"></i>
                                        Check-out: 5:00 - 11:30 <br>
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
                                                        sạn {{ $room['hotel_name'] }}.
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

                                    <h2 class="">
                                        Chuyến đi
                                    </h2>
                                    <div class="trip-info">
                                        <p><strong>{{ $room['hotel_name'] }}</strong></p>
                                        <p>
                                            <i class="far fa-calendar-alt"></i>
                                            {{ \Carbon\Carbon::parse($room['start_date'])->format('d/m/Y') }} đến
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
                                        @foreach($roomsOrder as $room)
                                            @php
                                                $total_amount += $room['price'];
                                            @endphp
                                            <div>
                                                <div style="display: flex; justify-content: space-between; align-items: center">
                                                    <h5>{{ $room['code'] }}</h5>
                                                    <h6 class="total-cost"> {{number_format($room['price'])}} đ /
                                                        đêm</h6>
                                                </div>
                                                <p><i class="fas fa-bed"></i> x1 Phòng suite</p>
                                                <p><i class="fas fa-user"></i> Người lớn: {{ $room['number_adult'] }},
                                                    Trẻ
                                                    em: {{ $room['number_child'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Dịch vụ -->
                                    <h2>Dịch vụ</h2>
                                    <div class="service-list">
                                        <!-- Dịch vụ được chọn sẽ được thêm vào đây -->
                                    </div>

                                    <div class="total-cost">
                                        <h4><strong>Tổng cộng: <span id="total-price"> {{ number_format($total_amount) }} </span> đ</strong></h4>
                                        <input type="number" name="total_amount" hidden value="{{ $total_amount }}"
                                               id="total_price_input">
                                    </div>
                                </article>

                                <button class="continue-button"
                                        style="width: 100%; background-color: #f7941d !important">
                                    Tiếp tục
                                </button>
                            </aside>
                            <!--//right sidebar-->
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!--//main content-->
    </main>
    <!--//main-->
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scrollContainers = document.querySelectorAll('.card-body');

        scrollContainers.forEach(container => {
            container.addEventListener('wheel', (event) => {
                event.preventDefault();
                container.scrollLeft += event.deltaY; // Lăn chuột dọc để cuộn ngang
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.arrow-button').forEach(button => {
            button.addEventListener('click', function () {

                const parentContainer = this.closest('div');

                // Tìm checkbox thông qua container gốc
                const checkbox = parentContainer.previousElementSibling?.querySelector('input[type="checkbox"]');

                checkbox.checked = !checkbox.checked;

                const serviceId = this.getAttribute('data-service-id');
                const roomCode = this.closest('.card').querySelector('h3').textContent.trim(); // Lấy mã phòng
                const serviceName = this.closest('.card-item').querySelector('.card__title').textContent.trim();
                const servicePrice = parseInt(
                    this.closest('.card-item').querySelector('.card__date h6').textContent
                        .match(/\d+/g).join('') // Lấy số trong đơn giá
                );

                const serviceList = document.querySelector('.service-list');
                const existingService = serviceList.querySelector(`[data-service-id="${serviceId}"]`);

                if (this.classList.contains('added')) {
                    if (existingService) {
                        const quantityElement = existingService.querySelector('.quantity-value');
                        let quantity = parseInt(quantityElement.textContent) - 1;

                        checkbox.checked = false;

                        this.classList.remove('added');
                        this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" height="15" width="15">
                                            <path fill="#fff"
                                                  d="M13.4697 17.9697C13.1768 18.2626 13.1768 18.7374 13.4697 19.0303C13.7626 19.3232 14.2374 19.3232 14.5303 19.0303L20.3232 13.2374C21.0066 12.554 21.0066 11.446 20.3232 10.7626L14.5303 4.96967C14.2374 4.67678 13.7626 4.67678 13.4697 4.96967C13.1768 5.26256 13.1768 5.73744 13.4697 6.03033L18.6893 11.25H4C3.58579 11.25 3.25 11.5858 3.25 12C3.25 12.4142 3.58579 12.75 4 12.75H18.6893L13.4697 17.9697Z"></path>
                                        </svg>`;

                        if (quantity === 0) {
                            existingService.remove();
                        } else {
                            quantityElement.textContent = quantity;
                            const totalElement = existingService.querySelector('.total-value');
                            totalElement.textContent = (servicePrice * quantity).toLocaleString() + ' đ';
                        }
                    }
                } else {

                    checkbox.checked = true;

                    if (existingService) {
                        const quantityElement = existingService.querySelector('.quantity-value');
                        const quantity = parseInt(quantityElement.textContent) + 1;
                        quantityElement.textContent = quantity;

                        const totalElement = existingService.querySelector('.total-value');
                        totalElement.textContent = (servicePrice * quantity).toLocaleString() + ' đ';
                    } else {
                        const newService = document.createElement('div');
                        newService.classList.add('service-item');
                        newService.setAttribute('data-service-id', serviceId);
                        newService.innerHTML = `
                    <div class="trip-info">
                         <div class="service-info ">
                            <span class="service-name"><strong>${serviceName}</strong></span>
                            <span class="service-price"><strong>${servicePrice.toLocaleString()} đ</strong></span>
                        </div>
                        <div class="service-quantity">
                            Số lượng: <span class="quantity-value">1</span>
                        </div>
                        <div class="service-total ">
                            Thành tiền: <span class="total-value">${servicePrice.toLocaleString()} đ</span>
                        </div>
                    </div>

                    `;
                        serviceList.appendChild(newService);
                    }

                    this.classList.add('added');
                    this.style.color = "#fff";
                    this.innerHTML = `Xóa`;
                }

                updateTotalPrice();
            });
        });

        function updateTotalPrice() {
            const serviceItems = document.querySelectorAll('.service-item');
            const checkbox = document.getElementById('total_price_input');
            let totalPrice = parseInt({{ $total_amount }});

            serviceItems.forEach(item => {
                const totalElement = item.querySelector('.total-value');
                const total = parseInt(totalElement.textContent.replace(/\D/g, ''));
                console.log(total)
                totalPrice += total;
            });

            console.log(totalPrice);


            document.getElementById('total-price').textContent = totalPrice.toLocaleString();
            checkbox.value = totalPrice;
        }
    });


    document.addEventListener('DOMContentLoaded', function () {
        // Lấy tất cả các thẻ toggle
        const toggleServices = document.querySelectorAll('.toggle-service');

        toggleServices.forEach(toggle => {
            const collapseTarget = toggle.getAttribute('href'); // Lấy ID của phần collapse
            const collapseElement = document.querySelector(collapseTarget);

            // Lắng nghe sự kiện show (mở)
            collapseElement.addEventListener('show.bs.collapse', function () {
                toggle.classList.add('active'); // Thêm class active để xoay mũi tên
                toggle.textContent = "Ẩn dịch vụ"; // Đổi nội dung thành "Ẩn dịch vụ"
            });

            // Lắng nghe sự kiện hide (đóng)
            collapseElement.addEventListener('hide.bs.collapse', function () {
                toggle.classList.remove('active'); // Xóa class active
                toggle.textContent = "Thêm dịch vụ"; // Đổi nội dung thành "Thêm dịch vụ"
            });

            // Xử lý trạng thái ban đầu
            if (collapseElement.classList.contains('show')) {
                toggle.classList.add('active'); // Nếu đang mở, thêm class active
                toggle.textContent = "Ẩn dịch vụ"; // Đặt nội dung ban đầu là "Ẩn dịch vụ"
            } else {
                toggle.textContent = "Thêm dịch vụ"; // Đặt nội dung ban đầu là "Thêm dịch vụ"
            }
        });
    });
</script>