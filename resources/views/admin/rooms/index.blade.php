@extends('admin.layouts.master')

@section('title')
    Danh sách mã phòng
@endsection

@section('content')




    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Danh sách phòng</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Phòng</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <!-- end page title -->


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row g-4 mb-3">
                        <div class="col-sm-auto">
                            <div class="d-flex">
                                @can('create_rooms')
                                    <button type="button" class="btn btn-success add-btn " style="margin-right:5px "
                                        data-bs-toggle="modal" id="create-btn" data-bs-target="#showModal"><i
                                            class="ri-add-line align-bottom me-1"></i> Thêm mới
                                    </button>
                                @endcan
                                @if (auth()->user()->type == \App\Models\User::ADMIN)
                                    <form action="{{ route('admin.rooms.index') }}" method="get">
                                        <button type="submit" class="btn btn-secondary">Đổi khách sạn</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm">
                            <form method="get" action="{{ route('admin.rooms.index') }}">
                                @csrf
                                <div class="d-flex justify-content-sm-end">
                                    <div class="col-3">
                                        <select name="catalogue_room_id" id="option" class="form-select">
                                            <option value="0">Chọn loại phòng</option>

                                            @foreach ($catalogueRooms as $catalogueRoom)
                                                <option
                                                    {{ request()->input('catalogue_room_id') == $catalogueRoom->id ? 'selected' : '' }}
                                                    value="{{ $catalogueRoom->id }}">
                                                    {{ $catalogueRoom->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="hotel_id" value="{{ $hotelId }}">
                                    <div class="search-box ms-2">
                                        <input type="text" class="form-control " placeholder="Tìm kiếm..." name="keyword"
                                            value="{{ request()->input('keyword') }}">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                    <div class="ms-1">
                                        <button class="btn btn-primary">Tìm kiếm</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="listjs-table" id="">

                        @php
                            $background = [
                                1 => 'bg-warning',
                                2 => 'bg-primary',
                                3 => 'bg-secondary',
                                4 => 'bg-success',
                                5 => 'bg-danger',
                            ];
                        @endphp

                        <div class="row">
                            @foreach ($rooms as $room)
                                <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <div
                                        class="card card-animate {{ in_array($room['id'], $roomBookedIds) ? $background[5] : $background[$room['status']] }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-bold fs-5 text-white text-truncate text-center mb-0"
                                                        style="text-shadow: 0 2px 4px black">{{ $room->code }}</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-2 text-white">
                                                <div>
                                                    <p class="mb-2">Loại phòng: {{ $room->catalogue_room_name }}</p>
                                                    <p class="mb-2">Trạng
                                                        thái:
                                                        {{ \App\Constant\Enum\RoomStatusEnum::parse($room['status'])->getName() }}
                                                    </p>
                                                    <p class="mb-2">Tình trạng
                                                        :
                                                        {{ in_array($room['id'], $roomBookedIds) ? 'Đang được sử dụng' : 'Còn trống' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <div class="edit">
                                                    @can('edit_rooms')
                                                        <button class="btn btn-sm btn-soft-warning edit-item-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#showModal{{ $room->id }}"><i
                                                                class="ri-edit-2-line"></i></button>
                                                    @endcan
                                                </div>

                                                {{-- <div class="remove">
                                                    @can('delete_rooms')
                                                        <button class="btn btn-sm btn-soft-danger remove-item-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteRecordModal{{ $room->id }}"><i
                                                                class="ri-delete-bin-2-line"></i></button>
                                                    @endcan
                                                    </div> --}}
                                                </div>
                                            </div><!-- end card body -->

                                        </div><!-- end card -->
                                        <div class="modal fade" id="showModal{{ $room->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-light p-3">
                                                        <h5 class="modal-title">Chi tiết</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close" id="close-modal"></button>
                                                    </div>
                                                    <form class="tablelist-form" autocomplete="off"
                                                        action="{{ route('admin.rooms.update', $room) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">

                                                            <div lass="mb-3">
                                                                <label for="disabledInput" class="form-label">Mã
                                                                    phòng</label>
                                                                <input type="text" class="form-control" id="disabledInput"
                                                                    value="{{ $room->code }}" disabled>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="catalogue_room_id" class="form-label">Loại
                                                                    phòng</label>
                                                                <select class="form-control" data-trigger
                                                                    name="catalogue_room_id" id="type-field" required>
                                                                    @foreach ($catalogueRooms as $catalogueRoom)
                                                                        <option value="{{ $catalogueRoom->id }}"
                                                                            {{ $catalogueRoom->id === $room->catalogue_room_id ? 'selected' : '' }}>
                                                                            {{ $catalogueRoom->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="catalogue_room_id" class="form-label">Trạng
                                                                    thái</label>
                                                                <select class="form-control" data-trigger name="status"
                                                                    id="type-field" required>
                                                                    @foreach (\App\Constant\Enum\RoomStatusEnum::cases() as $roomStatus)
                                                                        <option value="{{ $roomStatus->value }}"
                                                                            {{ $roomStatus->value === $room->status ? 'selected' : '' }}>
                                                                            {{ $roomStatus->getName() }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="hstack gap-2 justify-content-end">
                                                                <button type="button" class="btn btn-light"
                                                                    data-bs-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-warning"
                                                                    id="add-btn">Cập nhật phòng
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal -->
                                        <div class="modal fade zoomIn" id="deleteRecordModal{{ $room->id }}"
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
                                                                <p class="text-muted mx-4 mb-0">Bạn có chắc muốn xóa mã
                                                                    này ?</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                            <form action="{{ route('admin.rooms.destroy', $room) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('DELETE')
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
                                        <!--end modal -->
                                    </div><!-- end col -->
                                @endforeach
                            </div>


                        <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-light p-3">

                                        <h5 class="modal-title" id="exampleModalLabel">Thêm phòng</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close" id="close-modal"></button>

                                    </div>
                                        <form class="tablelist-form" autocomplete="off"
                                            action="{{ route('admin.rooms.store') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <input type="hidden" name="hotel_id" value="{{ request('hotel_id') ?? auth()->user()->org_id }}">
                                                    <label for="catalogue_room_id" class="form-label">Loại phòng</label>
                                                    <select class="form-control" data-trigger name="catalogue_room_id"
                                                        id="type-field" required>
                                                        @foreach ($catalogueRooms as $catalogueRoom)
                                                            <option value="{{ $catalogueRoom->id }}">
                                                                {{ $catalogueRoom->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="catalogue_room_id" class="form-label">Trạng thái</label>
                                                    <select class="form-control" data-trigger name="status" id="type-field"
                                                        required>
                                                        @foreach (\App\Constant\Enum\RoomStatusEnum::cases() as $roomStatus)
                                                            <option value="{{ $roomStatus->value }}">
                                                                {{ $roomStatus->getName() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="modal-footer">
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                            Close
                                                        </button>
                                                        <button type="submit" class="btn btn-success" id="add-btn">Thêm
                                                            mới
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <div class="pagination-wrap hstack gap-2">
                                {{ $rooms->appends(request()->query())->links() }}
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
        @if (!$hotelId && auth()->user()->type == \App\Models\User::ADMIN)
            <div id="overlay" class="overlay"></div>
            <div id="hotelModal" class="modal">
                <div class="modal-content">
                    <h2>Vui lòng chọn khách sạn</h2>
                    <form action="{{ route('admin.rooms.index') }}" method="get">
                        <div class="form-group">
                            <label for="hotel_id">Chọn khách sạn</label>
                            <select name="hotel_id" id="hotel_id" class="form-control" required>
                                <option value="">Chọn khách sạn</option>
                                @foreach ($hotels as $hotel)
                                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-change btn-primary">Chọn</button>
                    </form>
                </div>
            </div>

            <style>
                /* Overlay mờ nền */
                .overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.5);
                    /* Lớp phủ mờ */
                    z-index: 9998;
                    /* Đặt thấp hơn modal */
                    pointer-events: auto;
                    /* Cho phép tương tác với overlay */
                }


                /* Modal CSS */
                .modal {
                    display: flex;
                    /* Sử dụng flexbox để căn giữa */
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    justify-content: center;
                    align-items: center;
                    z-index: 9999;
                    /* Đặt layer của modal cao hơn overlay */
                }

                .modal-content {
                    background: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    text-align: center;
                    width: 400px;
                    max-width: 100%;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
                }

                .modal-content h2 {
                    font-size: 24px;
                    color: #4CAF50;
                    margin-bottom: 20px;
                }

                .modal-content .form-group {
                    margin-top: 10px;
                    margin-bottom: 20px;
                }

                .form-control {
                    width: 100%;
                    padding: 10px;
                    font-size: 16px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                }

                .btn-change {
                    padding: 10px 20px;
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    font-size: 16px;
                    cursor: pointer;
                }

                .btn-change:hover {
                    background-color: #45a049;
                }
            </style>
        @endif

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
