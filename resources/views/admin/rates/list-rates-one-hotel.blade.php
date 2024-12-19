@extends('admin.layouts.master')
@section('title')
    Danh sách đánh giá
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Đánh giá của khách sạn: {{ $hotel->name }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Đánh giá</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <!-- end page title -->

    <!-- Notification -->
    {{-- <div class="row">
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
    </div> --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

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
                                        <input type="text" class="form-control search" name="content"
                                            placeholder="Điền nội dung"
                                            value="{{ request()->has('content') ? request()->input('content') : '' }}">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                    <div class="ms-2">
                                        <input type="text" class="form-control search" name="name"
                                            placeholder="Điền tên khách"
                                            value="{{ request()->has('name') ? request()->input('name') : '' }}">
                                    </div>
                                    <div class="ms-2">
                                        <select name="rate" id="" class="form-select">
                                            <option value="">Lọc đánh giá</option>
                                            <option value="5" @selected(request()->input('rate') == 5)>Rất Hài Lòng</option>
                                            <option value="4" @selected(request()->input('rate') == 4)>Hài Lòng</option>
                                            <option value="3" @selected(request()->input('rate') == 3)>Bình Thường</option>
                                            <option value="2" @selected(request()->input('rate') == 2)>Kém</option>
                                            <option value="1" @selected(request()->input('rate') == 1)>Rất kém</option>
                                        </select>
                                    </div>
                                    <div class="ms-2">
                                        <select name="status" id="" class="form-select">
                                            <option value="">Lọc trả lời</option>
                                            <option value="1" @selected(request()->input('status') == 1)>Đã trả lời</option>
                                            <option value="2" @selected(request()->input('status') == 2)>Chưa trả lời</option>
                                        </select>
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
                                    <th scope="col">Đánh giá</th>
                                    <th scope="col">Nội dung</th>
                                    <th scope="col">Ngày đánh giá</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rates as $index => $rate)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $rate->user->name }}</td>
                                        <td class="text-warning">
                                            <span style="background-color: {{App\Models\Rate::RATE[$rate->rate][1]}}" class="badge ">{{App\Models\Rate::RATE[$rate->rate][0]}}</span>
                                        </td>
                                        <td>{{ $rate->content }}</td>
                                        <td>{{ Carbon\Carbon::parse($rate->created_at)->format('H:i:s d-m-Y') }}</td>
                                        <td>
                                            @if (!empty($rate->comment))
                                                <span class="badge bg-success">Đã trả lời</span>
                                            @else
                                                <span class="badge bg-warning">Chưa trả lời</span>
                                            @endif
                                        </td>
                                        <td class="d-flex gap-1">
                                            <div class="">
                                                <button class="btn btn-sm btn-soft-primary" data-bs-toggle="modal"
                                                    data-bs-target="#showModal{{ $rate->id }}">Trả lời</button>
                                            </div>
                                            <div class="remove">
                                                <button class="btn btn-sm btn-soft-danger remove-item-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteRecordModal{{ $rate->id }}">Ẩn</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal comment-->
                                    @if (!empty($rate->comment))
                                        <div class="modal fade" id="showModal{{ $rate->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-light p-3">
                                                        <h5 class="modal-title">Bình luận</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close" id="close-modal"></button>
                                                    </div>
                                                    <form class="tablelist-form" autocomplete="off"
                                                        action="{{ route('admin.comments.update', $rate->comment->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Đánh giá của khách
                                                                    hàng
                                                                    {{ $rate->user->name }}:</label>
                                                                <div>{{ $rate->content }}</div>
                                                                <div class="text-warning">
                                                                    <span style="background-color: {{App\Models\Rate::RATE[$rate->rate][1]}}" class="badge ">{{App\Models\Rate::RATE[$rate->rate][0]}}</span>
                                                                </div>
                                                            </div>

                                                            <input type="hidden" name="user_id"
                                                                value="{{ Auth::user()->id }}">
                                                            <input type="hidden" name="rate_id"
                                                                value="{{ $rate->id }}">

                                                            <div class="mb-3">
                                                                <label for="content" class="form-label">Trả lời<span
                                                                        class="text-danger">*</span></label>
                                                                <textarea name="content" class="form-control" id="content" cols="30" rows="5"
                                                                    placeholder="Nhập nội dung" required>{{ $rate->comment->content }}</textarea>
                                                                @error('content')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <div class="">
                                                                <a href="{{ route('admin.comments.delete', $rate->comment->id) }}"
                                                                    class="btn btn-danger"
                                                                    onclick="return confirm('Bạn có muốn xóa bình luận này không?')">
                                                                    Xóa bình luận
                                                                </a>
                                                            </div>
                                                            <div class="hstack gap-2 justify-content-end">
                                                                <button type="button" class="btn btn-light"
                                                                    data-bs-dismiss="modal">Đóng
                                                                </button>
                                                                <button type="submit" class="btn btn-warning"
                                                                    id="add-btn">Cập nhật bình luận
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="modal fade" id="showModal{{ $rate->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-light p-3">
                                                        <h5 class="modal-title">Bình luận</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close" id="close-modal"></button>
                                                    </div>
                                                    <form class="tablelist-form" autocomplete="off"
                                                        action="{{ route('admin.comments.store') }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Đánh giá của 
                                                                    {{ $rate->user->name }}:</label>
                                                                <div>{{ $rate->content }}</div>
                                                                <div class="text-warning">
                                                                    @for ($i = 0; $i < $rate->rate; $i++)
                                                                        <i class="ri-star-fill"></i>
                                                                    @endfor
                                                                </div>
                                                            </div>

                                                            <input type="hidden" name="user_id"
                                                                value="{{ Auth::user()->id }}">
                                                            <input type="hidden" name="rate_id"
                                                                value="{{ $rate->id }}">

                                                            <div class="mb-3">
                                                                <label for="content" class="form-label">Trả lời<span
                                                                        class="text-danger">*</span></label>
                                                                <textarea name="content" class="form-control" id="content" cols="30" rows="5"
                                                                    placeholder="Nhập nội dung" required></textarea>
                                                                @error('content')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="hstack gap-2 justify-content-end">
                                                                <button type="button" class="btn btn-light"
                                                                    data-bs-dismiss="modal">Đóng
                                                                </button>
                                                                <button type="submit" class="btn btn-success"
                                                                    id="add-btn">Trả lời
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Modal xóa-->
                                    <div class="modal fade zoomIn" id="deleteRecordModal{{ $rate->id }}"
                                        tabindex="-1" aria-hidden="true">
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
                                                            <p class="text-muted mx-4 mb-0">Bạn có chắc muốn ẩn đánh giá
                                                                này
                                                                ?</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                        <form action="{{ route('admin.rates.hidden', $rate->id) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="button" type="button"
                                                                class="btn w-sm btn-light" data-bs-dismiss="modal">Đóng
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
                            {{ $rates->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
@endsection

@section('styles')
    <style>
        td {
            white-space: nowrap;
            /* Không xuống dòng */
            overflow: hidden;
            /* Ẩn phần văn bản thừa */
            text-overflow: ellipsis;
            /* Hiển thị dấu ... khi vượt quá */
            max-width: 150px;
            /* Đặt chiều rộng tối đa */

        }
    </style>
@endsection

@section('style-libs')
    <!-- Sweet Alert css-->
    <link href="{{ asset('theme/admin/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"
        type="text/css" />
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
