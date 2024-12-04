@extends("admin.layouts.master")

@section("title")
    Dịch vụ của khách sạn {{$hotel->name}}
@endsection

@section('content')

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dịch vụ của khách sạn {{$hotel->name}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.hotels.index')}}">Khách sạn</a></li>
                            <li class="breadcrumb-item active">Danh sách dịch vụ</li>
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
            @error('services')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{$message}}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @enderror
        </div> --}}


        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body">
                        <div class="listjs-table" id="customerList">
                            <div class="row g-4 mb-3">
                                <div class="col-sm-auto">
                                    <div>
                                        <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"
                                                id="create-btn" data-bs-target="#showModal"><i
                                                    class="ri-add-line align-bottom me-1"></i> Thêm dịch vụ
                                        </button>
                                        <button class="btn btn-soft-danger" onClick="deleteMultiple()"><i
                                                    class="ri-delete-bin-2-line"></i></button>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <form class="d-flex justify-content-sm-end" method="GET" action="{{url()->full()}}">
                                        <div class="ms-2">
                                            <select name="type" id="" class="form-select">
                                                <option value="">Chọn tất cả</option>
                                                <option value="1" @selected(request()->input('type') == 1)>Dịch vụ đi kèm</option>
                                                <option value="2" @selected(request()->input('type') == 2)>Dịch vụ mất phí</option>
                                            </select>
                                        </div>
                                        <div class="ms-2">
                                            <input type="number" name="min_price" class="form-control" placeholder="Giá thấp nhất"
                                                value="{{ request('min_price') }}">
                                        </div>
                                        <div class="ms-2">
                                            <input type="number" name="max_price" class="form-control" placeholder="Giá cao nhất"
                                                value="{{ request('max_price') }}">
                                        </div>
                                        <div class="search-box ms-2">
                                            <input type="text" name="keyword" class="form-control" placeholder="Điền tên, giá, mô tả..." value="{{ request()->has('keyword') ? request()->input('keyword') : '' }}">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                        <div class="ms-1">
                                            <button class="btn btn-primary">Tìm kiếm</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card-body table-responsive table-card mt-3 mb-1">
                                <form action="{{route('admin.hotel.service.destroyMulti')}}" method="post" id="deleteMulti">
                                    @csrf
                                    @method('DELETE')
                                <table class="table align-middle table-nowrap" id="customerTable">
                                    <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 50px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAll"
                                                       value="option">
                                            </div>
                                        </th>
                                        <th>STT</th>
                                        <th>Tên dịch vụ</th>
                                        <th>Giá</th>
                                        <th>Mô tả</th>
                                        <th>Loại dịch vụ</th>
                                        <th>Hành động</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @foreach ($hotelServices as $index => $hotelService)
                                            <tr>
                                                <th scope="row">
                                                    <div class="form-check">

                                                            <input class="form-check-input service" type="checkbox"
                                                                   name="services[][service_id]"
                                                                   value="{{$hotelService->id}}">

                                                    </div>
                                                </th>
                                                <td class="id" style="display:none;"><a href="javascript:void(0);"
                                                                                        class="fw-medium link-primary"></a>
                                                </td>
                                                <td class="">{{ $index + 1 }}</td>
                                                <td class="">{{ $hotelService->service->name }}</td>
                                                <td class="">{{ number_format($hotelService->service->price, 0, ',', '.') }} VND</td>
                                                <td class="">{{ $hotelService->service->description }}</td>
                                                <td class="">
                                                    @php
                                                        $bgColor = 'bg-light text-dark';
                                                        if ($hotelService->service->type == 1) {
                                                            $bgColor = 'bg-info';
                                                        }
                                                    @endphp
                                                    <span class="badge {{$bgColor}}">{{ App\Models\Service::TYPE_SERVICE[$hotelService->service->type]}}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <div class="remove">
                                                            <button type="button" class="btn btn-sm btn-soft-danger remove-item-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteRecordModal{{ $hotelService->id }}">
                                                                <i
                                                                        class="ri-delete-bin-2-line"></i></button>
                                                        </div>

                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal -->
                                            <div class="modal fade zoomIn" id="deleteRecordModal{{ $hotelService->id }}"
                                                 tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close" id="btn-close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mt-2 text-center">
                                                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json"
                                                                           trigger="loop"
                                                                           colors="primary:#f7b84b,secondary:#f06548"
                                                                           style="width:100px;height:100px"></lord-icon>
                                                                <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                                                    <h4>Bạn chắc chắn ?</h4>
                                                                    <p class="text-muted mx-4 mb-0">Bạn có chắc muốn xóa
                                                                        dịch vụ khách sạn
                                                                        này ?</p>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                                <div>
                                                                    <button type="button" type="button"
                                                                            class="btn w-sm btn-light"
                                                                            data-bs-dismiss="modal">Đóng
                                                                    </button>
                                                                    <a href="{{ route('admin.hotel.service.destroy', $hotelService) }}" class="btn w-sm btn-danger "
                                                                            id="delete-record">Chắc chắn!
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end modal -->
                                        @endforeach

                                    </tbody>
                                </table>
                                </form>
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
                                              action="{{url()->current()}}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="services"
                                                           class="form-label text-muted">Chọn dịch vụ<span class="text-danger">*</span></label>
                                                    <select class="form-control" id="services"
                                                            data-choices data-choices-removeItem
                                                            name="services[][service_id]" multiple>
                                                        @foreach($services as $service)
                                                            <option value="{{$service->id}}" @foreach($hotelServiceConstants as $hotelServiceConstant)
                                                                {{$hotelServiceConstant->service_id == $service->id ? 'disabled' : ''}}
                                                                    @endforeach>{{$service->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="invalid-feedback"></div>

                                            </div>
                                            <div class="modal-footer">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        Đóng
                                                    </button>
                                                    <button type="submit" class="btn btn-success" id="add-btn">Thêm dịch vụ
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                        {{ $hotelServices->links() }}
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
                    white-space: nowrap; /* Không xuống dòng */
                    overflow: hidden; /* Ẩn phần văn bản thừa */
                    text-overflow: ellipsis; /* Hiển thị dấu ... khi vượt quá */
                    max-width: 150px; /* Đặt chiều rộng tối đa */

                }
            </style>
        @endsection

        @section('style-libs')
            <!-- Sweet Alert css-->
            <link href="{{ asset('theme/admin/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"
                  type="text/css"/>
        @endsection

        @section('scripts')
            <script>
                function deleteMultiple() {
                    let formDelMulti = document.querySelector("#deleteMulti");
                    let checkboxes = formDelMulti.querySelectorAll(".service");

                    // Kiểm tra xem có ít nhất một checkbox được chọn hay không
                    let hasChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

                    if(confirm("Bạn có muốn xóa các dịch vụ khách sạn này không?"))
                    {
                        if (!hasChecked) {
                            // Nếu chưa chọn checkbox nào, hiển thị thông báo
                            alert("Bạn cần chọn ít nhất 1 dịch vụ");
                            return;
                        }
                        formDelMulti.submit();
                    }
                }
            </script>
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
