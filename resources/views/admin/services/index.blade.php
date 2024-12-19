@extends('admin.layouts.master')

@section('title')
    Danh sách dịch vụ
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Danh sách dịch vụ</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dịch vụ</a></li>
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
                        <strong>{{session('msg')}}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{session('error')}}</strong>
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
                                <div>
                                    @can('create_services')
                                        <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"
                                            id="create-btn" data-bs-target="#showModal"><i
                                                class="ri-add-line align-bottom me-1"></i> Thêm Mới
                                        </button>
                                    @endcan
                                    {{-- <button class="btn btn-soft-danger" onClick="deleteMultiple()"><i
                                                    class="ri-delete-bin-2-line"></i></button> --}}
                                </div>
                            </div>
                            <div class="col-sm">
                                <form class="d-flex justify-content-sm-end" method="GET"
                                    action="{{ route('admin.services.index') }}">
                                    @if (Auth::user()->type == App\Models\User::ADMIN)
                                        <div class="ms-1">
                                            <select name="hotel" id="" class="form-select">
                                                <option value="">Chọn khách sạn</option>
                                                @foreach ($hotels as $hotel)
                                                    <option value="{{ $hotel->id }}" @selected(request()->input('hotel') == $hotel->id)>
                                                        {{ $hotel->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="ms-1">
                                        <select name="type" id="" class="form-select">
                                            <option value="">Loại dịch vụ</option>
                                            <option value="1" @selected(request()->input('type') == 1)>Dịch vụ mất phí</option>
                                            <option value="2" @selected(request()->input('type') == 2)>Dịch vụ đi kèm</option>
                                        </select>
                                    </div>
                                    <div class="ms-1">
                                        <select name="status" id="" class="form-select">
                                            <option value="">Trạng thái</option>
                                            <option value="1" @selected(request()->input('status') == 1)>Hoạt động</option>
                                            <option value="2" @selected(request()->input('status') == 2)>Không hoạt động</option>
                                        </select>
                                    </div>
                                    <div class="ms-1">
                                        <input type="number" name="min_price" class="form-control"
                                            placeholder="Giá thấp nhất" value="{{ request('min_price') }}">
                                    </div>
                                    <div class="ms-1">
                                        <input type="number" name="max_price" class="form-control"
                                            placeholder="Giá cao nhất" value="{{ request('max_price') }}">
                                    </div>
                                    <div class="ms-1">
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Điền tên dịch vụ"
                                            value="{{ request()->has('name') ? request()->input('name') : '' }}">
                                    </div>
                                    <div class="ms-1">
                                        <button class="btn btn-primary">Tìm</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body table-responsive table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap" id="customerTable">
                                <thead class="table-light">
                                    <tr>
                                        {{-- <th scope="col" style="width: 50px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAll"
                                                       value="option">
                                            </div>
                                        </th> --}}
                                        <th class="text-center">STT</th>
                                        <th>Tên dịch vụ</th>
                                        <th>Giá</th>
                                        <th>Loại dịch vụ</th>
                                        <th>Trạng thái</th>
                                        @if (Gate::check('edit_services') || Gate::check('delete_services'))
                                            <th>Hành động</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                    @foreach ($services as $index => $service)
                                        <tr>
                                            {{-- <th scope="row">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="chk_child"
                                                           value="option1">
                                                </div>
                                            </th> --}}
                                            <td class="id" style="display:none;"><a href="javascript:void(0);"
                                                    class="fw-medium link-primary"></a>
                                            </td>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="">{{ $service->name }}</td>
                                            <td class="">{{ number_format($service->price, 0, ',', '.') }} VND</td>
                                            <td class="">
                                                @php
                                                    $bgColor = 'bg-light text-dark';
                                                    if ($service->type == 1) {
                                                        $bgColor = 'bg-info';
                                                    }
                                                @endphp
                                                <span
                                                    class="badge {{ $bgColor }}">{{ App\Models\Service::TYPE_SERVICE["$service->type"] }}</span>
                                            </td>

                                            <td>
                                                @php
                                                    $bg = 'bg-danger';
                                                    if ($service->status == 1) {
                                                        $bg = 'bg-success';
                                                    }
                                                @endphp
                                                <span
                                                    class="badge {{ $bg }}">{{ $service->status == 1 ? 'Hoạt động' : 'Không hoạt động' }}
                                                </span>
                                            </td>

                                            @if (Gate::check('edit_services') || Gate::check('delete_services'))
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        @can('edit_services')
                                                            <div class="edit">
                                                                <button class="btn btn-sm btn-soft-warning edit-item-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#showModal{{ $service->id }}"><i
                                                                        class="ri-edit-2-line"></i></button>
                                                            </div>
                                                        @endcan
                                                        {{-- @can('delete_services')
                                                            <div class="remove">
                                                                <button class="btn btn-sm btn-soft-danger remove-item-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteRecordModal{{ $service->id }}"><i
                                                                        class="ri-delete-bin-2-line"></i></button>
                                                            </div>
                                                        @endcan --}}

                                                    </div>
                                                </td>
                                            @endif

                                        </tr>

                                        <div class="modal fade" id="showModal{{ $service->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-light p-3">
                                                        <h5 class="modal-title">Chi Tiết Dịch Vụ</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close" id="close-modal"></button>
                                                    </div>
                                                    <form class="tablelist-form" autocomplete="off"
                                                        action="{{ route('admin.services.update', $service) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Tên dịch vụ<span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" id="name" class="form-control"
                                                                    placeholder="Nhập tên dịch vụ" required name="name"
                                                                    value="{{ $service->name }}" />
                                                                @error('name')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="price" class="form-label">Giá<span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" id="price" class="form-control"
                                                                    name="price" placeholder="Nhập giá" required
                                                                    value="{{ $service->price }}" />
                                                                @error('price')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="description" class="form-label">Mô
                                                                    tả<span class="text-danger">*</span></label>
                                                                <textarea name="description" class="form-control" id="description" cols="30" rows="5"
                                                                    placeholder="Nhập mô tả" required>{{ $service->description }}</textarea>
                                                                @error('description')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                @enderror

                                                            </div>

                                                            <div class="mb-2 d-flex justify-content-end">
                                                                <div class="form-check form-switch form-switch-success">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        role="switch" name="status" id="SwitchCheck3"
                                                                        value="1" @checked($service->status == 1)>
                                                                    <label class="form-check-label"
                                                                        for="SwitchCheck3">Hoạt động</label>
                                                                </div>
                                                            </div>


                                                            @if (Auth::user()->type == 2)
                                                                <div class="mb-3">
                                                                    <label for="hotel" class="form-label">Khách
                                                                        sạn<span class="text-danger">*</span></label>
                                                                    <select name="hotel_id" id="hotel"
                                                                        class="form-select" required>
                                                                        <option value="">--Chọn khách sạn--</option>
                                                                        @foreach ($hotels as $hotel)
                                                                            <option value="{{ $hotel->id }}" @selected($service->hotel->id == $hotel->id)>
                                                                                {{ $hotel->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('hotel_id')
                                                                        <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <!-- end card body -->
                                                            @else
                                                                <input type="hidden" value="{{ Auth::user()->org_id }}"
                                                                    name="hotel_id">
                                                            @endif

                                                            <div class="mb-3">
                                                                <label for="type" class="form-label">Loại dịch
                                                                    vụ</label>
                                                                <select name="type" id="type"
                                                                    class="form-select">
                                                                    @foreach ($typesService as $key => $name)
                                                                        <option value="{{ $key }}"
                                                                            @selected("$service->type" == $key)>
                                                                            {{ $name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('type')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="hstack gap-2 justify-content-end">
                                                                <button type="button" class="btn btn-light"
                                                                    data-bs-dismiss="modal">Đóng
                                                                </button>
                                                                <button type="submit" class="btn btn-warning"
                                                                    id="add-btn">Cập Nhật
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal -->
                                        <div class="modal fade zoomIn" id="deleteRecordModal{{ $service->id }}"
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
                                                                <p class="text-muted mx-4 mb-0">Bạn có chắc muốn xóa dịch
                                                                    vụ
                                                                    này ?</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                            <form action="{{ route('admin.services.destroy', $service) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" type="button"
                                                                    class="btn w-sm btn-light"
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
                                        <!--end modal -->
                                    @endforeach


                                </tbody>
                            </table>
                            <div class="noresult" style="display: none">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#121331,secondary:#08a88a"
                                        style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2">Sorry! No Result Found</h5>
                                    <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find
                                        any
                                        orders for you search.</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <div class="pagination-wrap hstack gap-2">

                            </div>
                        </div>

                        <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-light p-3">
                                        <h5 class="modal-title" id="exampleModalLabel">Thêm service</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close" id="close-modal"></button>
                                    </div>
                                    <form class="tablelist-form" autocomplete="off"
                                        action="{{ route('admin.services.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Tên dịch vụ<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" id="name" class="form-control"
                                                    placeholder="Nhập tên dịch vụ" required name="name" />
                                                @error('name')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>


                                            <div class="mb-3">
                                                <label for="price" class="form-label">Giá<span
                                                        class="text-danger">*</span></label>
                                                <input type="number" id="price" class="form-control" name="price"
                                                    placeholder="Nhập giá" required />
                                                @error('price')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="description" class="form-label">Mô tả<span
                                                        class="text-danger">*</span></label>
                                                <textarea name="description" class="form-control" id="description" cols="30" rows="5"
                                                    placeholder="Nhập mô tả" required></textarea>
                                                @error('description')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror

                                            </div>

                                            <div class="mb-2 d-flex justify-content-end">
                                                <div class="form-check form-switch form-switch-success">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="status" id="SwitchCheck3" value="1" checked>
                                                    <label class="form-check-label" for="SwitchCheck3">Hoạt động</label>
                                                </div>
                                            </div>

                                            @if (Auth::user()->type == 2)
                                                <div class="mb-3">
                                                    <label for="hotel" class="form-label">Khách sạn<span class="text-danger">*</span></label>
                                                    <select name="hotel_id" id="hotel" class="form-select" required>
                                                        <option value="">--Chọn khách sạn--</option>
                                                        @foreach ($hotels as $hotel)
                                                            <option value="{{ $hotel->id }}">{{ $hotel->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('hotel_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <!-- end card body -->
                                            @else
                                                <input type="hidden" value="{{ Auth::user()->org_id }}"
                                                    name="hotel_id">
                                            @endif

                                            <div class="mb-3">
                                                <label for="type" class="form-label">Loại dịch vụ</label>
                                                <select name="type" id="type" class="form-select">
                                                    @foreach ($typesService as $key => $name)
                                                        <option value="{{ $key }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('type')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <div class="hstack gap-2 justify-content-end">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                    Đóng
                                                </button>
                                                <button type="submit" class="btn btn-success" id="add-btn">Thêm Dịch
                                                    Vụ
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                    {{ $services->appends(request()->query())->links() }}
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
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
