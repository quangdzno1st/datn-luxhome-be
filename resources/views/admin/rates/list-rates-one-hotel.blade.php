@extends('admin.layouts.master')
@section('content')
    <!-- Notification -->
    <div class="row">
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
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Đánh giá của khách sạn: {{ $hotel->name }}</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-auto">
                                <div class="col-sm-auto">
                                    <div>
                                        @if (Auth::user()->type == 2)
                                            <a href="{{ route('admin.rates.hotel.trash', $hotel->id) }}"
                                                class="btn btn-light bg-gradient waves-effect waves-light">
                                                <i class="ri-delete-bin-fill"></i> Đánh giá đã ẩn</a>
                                        @else
                                            <a href="{{ route('admin.rates.hotel.trash.hotelier') }}"
                                                class="btn btn-light bg-gradient waves-effect waves-light">
                                                <i class="ri-delete-bin-fill"></i> Đánh giá đã ẩn</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <form class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input type="text" class="form-control search" name="keyword"
                                            placeholder="Điền điểm, nội dung, ..."
                                            value="{{ request()->has('keyword') ? request()->input('keyword') : '' }}">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
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
                                    <th scope="col">Điểm</th>
                                    <th scope="col">Nội dung</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rates as $index => $rate)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $rate->user->name }}</td>
                                        <td class="text-warning">
                                            @for ($i = 0; $i < $rate->rate; $i++)
                                                <i class="ri-star-fill"></i>
                                            @endfor
                                        </td>
                                        <td>{{ $rate->content }}</td>
                                        <td>

                                            <div class="remove">
                                                <button class="btn btn-sm btn-soft-danger remove-item-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteRecordModal{{ $rate->id }}">Ẩn</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Modal -->
                                    <div class="modal fade zoomIn" id="deleteRecordModal{{ $rate->id }}" tabindex="-1"
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
                                                            <p class="text-muted mx-4 mb-0">Bạn có chắc muốn ẩn đánh giá này
                                                                ?</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                        <form action="{{ route('admin.rates.hidden', $rate->id) }}"
                                                            method="post">
                                                            @csrf
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
                                    </div>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $rates->links() }}
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
    <!-- Sweet Alert css-->
    <link href="{{ asset('theme/admin/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('script-libs')
    <!-- prismjs plugin -->
    <script src="{{ asset('theme/admin/assets/libs/prismjs/prism.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/list.js/list.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/list.pagination.js/list.pagination.min.js') }}"></script>

    <!-- list.js min js -->
    <script src="{{ asset('theme/admin/assets/js/pages/listjs.init.js') }}"></script>

    <!-- Sweet Alerts js -->
    <script src="{{ asset('theme/admin/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection
