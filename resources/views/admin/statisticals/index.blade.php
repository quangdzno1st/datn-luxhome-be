@extends('admin.layouts.master')

@section('title')
    Tổng Quan
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Tổng quan</h4>

            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-12">
            <div class="flex-grow-1 mb-3">
                <h4 class="fs-16 mb-1">Tổng quan thống kê</h4>
                <p class="text-muted mb-0">Theo dõi tổng quan thống kê số liệu website</p>
            </div>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('admin.handle.statistical') }}" method="post" class="row">
                        @csrf
                        @if (Auth::check() && Auth::user()->type == \App\Constant\Enum\RoleEnum::SupperAdmin->value)
                            <div class="mb-3 col-3">
                                <div class="">
                                    <label for="hotel">Chọn khách sạn:</label>
                                </div>
                                <div class="">
                                    <select name="hotel_id" id="hotel" class="form-select">
                                        <option value="">Tất cả</option>
                                        @foreach ($hotels as $hotel)
                                            <option value="{{ $hotel->id }}" @selected($hotel_id == $hotel->id)>
                                                {{ $hotel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="mb-3 col-3">
                            <div class="">
                                <label for="start_date">Thời gian bắt đầu:</label>
                            </div>
                            <div class="">
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ $startDate }}">
                            </div>
                            @error('start_date')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3 col-3">
                            <div class="">
                                <label for="end_date">Thời gian kết thúc:</label>
                            </div>
                            <div class="">
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="{{ $endDate }}">
                            </div>
                            @error('end_date')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3 col-3">
                            <div class="">
                                <label for="option">Chọn mốc theo:</label>
                            </div>
                            <div class="d-flex">
                                <div class="col-9">
                                    <select name="option_time" id="option" class="form-select">
                                        @foreach ($optionTime as $key => $value)
                                            <option value="{{ $key }}" @selected($selectTime != '' && $selectTime == $key)>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-success d-block">Xem</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Doanh thu</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div>
                            <h4 class="fs-20 fw-semibold ff-secondary mb-1"><span class="counter-value"
                                    data-target="{{ (int) $totalRevenue->total_revenue }}">0</span><sup>đ</sup> </h4>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span href="javascript:void(0)" class="text-decoration-underline" style="color: white"></span>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-success-subtle rounded fs-3">
                                    <i class="bx bx-dollar-circle text-success"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Đơn đặt</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div>
                            <h4 class="fs-20 fw-semibold ff-secondary mb-1"><span class="counter-value"
                                    data-target="{{ $totalOrder->total_order }}">0</span></h4>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">

                            <span href="javascript:void(0)" class="text-decoration-underline" style="color: white"></span>
                            {{-- @if (Auth::user()->type == 2)
                                <a href="#" class="text-decoration-underline text-white">.</a>
                            @else
                                <a href="{{ route('admin.orders.index') }}" class="text-decoration-underline">Xem chi tiết</a>
                            @endif --}}
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-danger rounded fs-3">
                                    <i class="ri-building-4-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Tổng số phòng</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div>
                            <h4 class="fs-20 fw-semibold ff-secondary mb-1"><span class="counter-value"
                                    data-target="{{ $thongkeTotalRoom }}">0</span> </h4>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span href="javascript:void(0)" class="text-decoration-underline" style="color: white"></span>
                            {{-- @if (Auth::user()->type == 2)
                                <a href="#" class="text-decoration-underline text-white">.</a>
                            @else
                                <a href="{{ route('admin.rooms.index') }}" class="text-decoration-underline">Xem chi tiết</a>
                            @endif --}}
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded fs-3">
                                    <i class="las la-door-closed text-info"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Phòng đang đặt</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div>
                            <h4 class="fs-20 fw-semibold ff-secondary mb-1"><span class="counter-value"
                                    data-target="{{ $thongkeTotalRoomBeingBooked[0]['booked_room_qty'] }}">0</span> </h4>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span href="javascript:void(0)" class="text-decoration-underline" style="color: white"></span>
                            {{-- <a href="#" class="text-decoration-underline text-white">.</a> --}}
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-warning-subtle rounded fs-3">
                                    <i class="las la-door-open text-warning"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Đánh giá</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div>
                            <h4 class="fs-20 fw-semibold ff-secondary mb-1"><span class="counter-value"
                                    data-target="{{ $totalRating->total_rating }}">0</span> </h4>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span href="javascript:void(0)" class="text-decoration-underline" style="color: white"></span>
                            {{-- @if (Auth::user()->type == 2)
                                <a href="#" class="text-decoration-underline text-white">.</a>
                            @else
                                <a href="{{ route('admin.rates.hotels') }}" class="text-decoration-underline">Xem chi tiết</a>
                            @endif --}}
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle rounded fs-3">
                                    <i class="ri-message-2-line text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Khách hàng</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div>
                            <h4 class="fs-20 fw-semibold ff-secondary mb-1"><span class="counter-value"
                                    data-target="{{ $totalUser }}">0</span></h4>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span href="javascript:void(0)" class="text-decoration-underline" style="color: white"></span>
                            {{-- @if (Auth::user()->type == 2)
                                <a href="#" class="text-decoration-underline text-white">.</a>
                            @else
                                <a href="{{ route('admin.users.index') }}" class="text-decoration-underline">Xem chi tiết</a>
                            @endif --}}
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-warning-subtle rounded fs-3">
                                    <i class="bx bx-user-circle text-warning"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div> <!-- end row-->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                {{-- <div class="card-header">
                    <form action="{{ route('admin.handle.statistical') }}" method="post" class="row">
                        @csrf
                        @if (Auth::check() && Auth::user()->type == \App\Constant\Enum\RoleEnum::SupperAdmin->value)
                            <div class="mb-3 col-3">
                                <div class="">
                                    <label for="hotel">Chọn khách sạn:</label>
                                </div>
                                <div class="">
                                    <select name="hotel_id" id="hotel" class="form-select">
                                        <option value="">Tất cả</option>
                                        @foreach ($hotels as $hotel)
                                            <option value="{{ $hotel->id }}" @selected($hotel_id == $hotel->id)>
                                                {{ $hotel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="mb-3 col-3">
                            <div class="">
                                <label for="start_date">Thời gian bắt đầu:</label>
                            </div>
                            <div class="">
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ $startDate }}">
                            </div>
                            @error('start_date')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3 col-3">
                            <div class="">
                                <label for="end_date">Thời gian kết thúc:</label>
                            </div>
                            <div class="">
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="{{ $endDate }}">
                            </div>
                            @error('end_date')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3 col-3">
                            <div class="">
                                <label for="option">Chọn mốc theo:</label>
                            </div>
                            <div class="d-flex">
                                <div class="col-9">
                                    <select name="option_time" id="option" class="form-select">
                                        @foreach ($optionTime as $key => $value)
                                            <option value="{{ $key }}" @selected($selectTime != '' && $selectTime == $key)>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-success d-block">Xem</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div> --}}
                <div class="card-body">
                    <div class="d-flex">
                        <div style="width: 100%; max-width: 60%">
                            <canvas id="revenueChart" style="width:100%"></canvas>
                        </div>
                        <div style="width: 100%; max-width: 30%; margin: 0 auto; color:rgb(69, 66, 66)">
                            <canvas id="orderChart" style="width:100%"></canvas>
                        </div>
                    </div>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

@endsection


@section('scripts')
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');

        let xValues = [
            @if (isset($thongke[0]->month))
                @foreach ($thongke as $value)
                    "{{ "$value->month/$value->year" }}",
                @endforeach
            @elseif (isset($thongke[0]->quarter))
                @foreach ($thongke as $value)
                    "{{ "Q$value->quarter/$value->year" }}",
                @endforeach
            @else
                @foreach ($thongke as $value)
                    "{{ $value->year }}",
                @endforeach
            @endif
        ];

        let yValuesRevenue = [
            @foreach ($thongke as $value)
                "{{ (int) $value->total_revenue }}",
            @endforeach
        ];

        let yValuesOrder = [
            @foreach ($thongke as $value)
                {{ $value->total_order }},
            @endforeach
        ];

        const data = {
            labels: xValues, // Nhãn trục x
            datasets: [{
                    label: 'Số đơn đặt', // Nhãn cho số booking
                    data: yValuesOrder, // Dữ liệu số booking
                    borderColor: 'blue',
                    backgroundColor: 'blue',
                    yAxisID: 'y1', // Liên kết với trục y đầu tiên
                    fill: false,
                    tension: 0.3 // Độ cong của đường
                },
                {
                    label: 'Doanh thu', // Nhãn cho doanh thu
                    data: yValuesRevenue, // Dữ liệu doanh thu
                    borderColor: 'green',
                    backgroundColor: 'green',
                    yAxisID: 'y2', // Liên kết với trục y thứ hai
                    fill: false,
                    tension: 0.3
                }
            ]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top', // Vị trí của chú thích
                    },
                },
                scales: {
                    y1: {
                        type: 'linear', // Trục y cho số booking
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Số đơn đặt',
                        },
                        ticks: {
                            stepSize: 2
                        }
                    },
                    y2: {
                        type: 'linear', // Trục y cho doanh thu
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Doanh thu',
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' đ'; // Hiển thị đơn vị VND
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Thống kê doanh thu và đơn đặt',
                            font: {
                                size: 12, // Kích thước chữ
                                weight: 'bold' // Độ đậm của chữ
                            },
                            padding: {
                                top: 10, // Khoảng cách phía trên
                                bottom: 10 // Khoảng cách phía dưới
                            },
                            color: '#333' // Màu chữ
                        },
                    }
                }
            }
        };

        new Chart(ctx, config);
    </script>
    <script>
        const orderChart = document.getElementById('orderChart').getContext('2d');

        let dataOrderStatus = [
            @foreach ($thongkeOrderByStatus as $value)
                {{ $value->quantity_order }},
            @endforeach
        ];

        let backgroundColorArr = [];

        let borderColorArr = [];

        let labelsPie = [];

        @foreach ($thongkeOrderByStatus as $value)
            @if ($value->status == 1)
                labelsPie.push('Đang chờ');
                backgroundColorArr.push('rgba(255, 205, 86, 0.6)');
                borderColorArr.push('rgba(255, 205, 86, 1)');
            @endif
            @if ($value->status == 2)
                labelsPie.push('Đã xác nhận');
                backgroundColorArr.push('rgba(54, 162, 235, 0.6)');
                borderColorArr.push('rgba(54, 162, 235, 1)');
            @endif
            @if ($value->status == 3)
                labelsPie.push('Hoàn thành');
                backgroundColorArr.push('rgba(75, 192, 192, 0.6)');
                borderColorArr.push('rgba(75, 192, 192, 1)');
            @endif
            @if ($value->status == 4)
                labelsPie.push('Đã hủy');
                backgroundColorArr.push('rgba(236, 27, 27, 0.6)');
                borderColorArr.push('rgba(236, 27, 27, 1)');
            @endif
            @if ($value->status == 5)
                labelsPie.push('Yêu cầu hủy');
                backgroundColorArr.push('rgba(69, 66, 66, 0.6)');
                borderColorArr.push('rgba(69, 66, 66, 1)');
            @endif
        @endforeach

        const dataChart = {
            labels: labelsPie, // Các trạng thái đơn hàng
            datasets: [{
                label: 'Thống kê đơn hàng',
                data: dataOrderStatus, // Số lượng đơn hàng theo từng trạng thái
                backgroundColor: backgroundColorArr,
                borderColor: borderColorArr,
                borderWidth: 1
            }]
        };

        const configChart = {
            type: 'pie', // Loại biểu đồ
            data: dataChart,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top', // Hiển thị chú thích ở trên
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const total = dataChart.datasets[0].data.reduce((a, b) => a + b, 0);
                                const value = dataChart.datasets[0].data[tooltipItem.dataIndex];
                                const percentage = ((value / total) * 100).toFixed(2);
                                return `${dataChart.labels[tooltipItem.dataIndex]}: ${value} đơn hàng (${percentage}%)`;
                            }
                        }
                    },
                    title: {
                        display: true, // Bật tiêu đề
                        text: 'Thống kê trạng thái đơn hàng', // Nội dung tiêu đề
                        font: {
                            size: 12, // Kích thước chữ
                            weight: 'bold' // Độ đậm của chữ
                        },
                        padding: {
                            top: 10, // Khoảng cách phía trên
                            bottom: 10 // Khoảng cách phía dưới
                        },
                        color: '#333' // Màu chữ
                    }

                }
            }
        };

        new Chart(orderChart, configChart);
    </script>
    {{-- <script>
        let xValues = [
            @if (isset($thongke[0]->month))
                @foreach ($thongke as $value)
                    "{{ "$value->month/$value->year" }}",
                @endforeach
            @elseif (isset($thongke[0]->quarter))
                @foreach ($thongke as $value)
                    "{{ "Q$value->quarter/$value->year" }}",
                @endforeach
            @else
                @foreach ($thongke as $value)
                    "{{ $value->year }}",
                @endforeach
            @endif
        ];
        let yValuesRevenue = [
            @foreach ($thongke as $value)
                "{{ $value->total_revenue }}",
            @endforeach
        ];
        let barColors = [
            @foreach ($thongke as $value)
                "#9AD0F5",
            @endforeach
        ];

        new Chart("revenueChart", {
            type: "line",
            data: {
                labels: xValues,
                datasets: [{
                    borderWidth: 1,
                    data: yValuesRevenue
                }]
            },
            options: {
         plugins: {
          legend: { // Ẩn phần legend (chú thích)
            display: false
          },
          title: {
            display: true,
            text: 'Thống Kê Doanh Thu'
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            min: 0
          }
        }
      }
        });

        let yValuesOrder = [
            @foreach ($thongke as $value)
                {{ $value->total_order }},
            @endforeach
        ];

        new Chart("orderChart", {
            type: "line",
            data: {
                labels: xValues,
                datasets: [{
                    borderWidth: 1,
                    data: yValuesOrder
                }]
            },
            options: {
         plugins: {
          legend: { // Ẩn phần legend (chú thích)
            display: false
          },
          title: {
            display: true,
            text: 'Thống Kê Đơn Đặt Có Doanh Thu'
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            min: 0
          }
        }
      }
        });
    </script> --}}
@endsection

@section('styles')
    <style>
        .avatar-sm {
            width: 2rem;
            height: 2rem;
        }
    </style>
@endsection

@section('script-libs')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- apexcharts -->
    <script src="{{ asset('theme/admin/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Vector map-->
    <script src="{{ asset('theme/admin/assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/jsvectormap/maps/world-merc.js') }}"></script>

    <!--Swiper slider js-->
    <script src="{{ asset('theme/admin/assets/libs/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Dashboard init -->
    <script src="{{ asset('theme/admin/assets/js/pages/dashboard-ecommerce.init.js') }}"></script>
@endsection
