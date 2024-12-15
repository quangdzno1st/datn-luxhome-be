@extends('admin.layouts.master')

@section('title')
    Danh sách khách sạn
@endsection

@section('content')

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Danh sách khách sạn</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Khách sạn</a></li>
                            <li class="breadcrumb-item active">Danh sách</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Danh sách khách sạn</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="listjs-table" id="customerList">
                            <div class="row g-4 mb-3">
                                <div class="col-sm-auto">
                                    <div>
                                        <a href="{{ route('admin.hotels.index') }}" class="btn btn-primary bg-gradient waves-effect waves-primary">
                                            <i class="ri-arrow-left-s-line"></i> Danh sách</a>
                                        {{-- <button class="btn btn-soft-danger" onClick="deleteMultiple()"><i
                                                    class="ri-delete-bin-2-line"></i></button> --}}
                                    </div>
                                </div>
                                <div class="col-sm">
                                    {{-- <div class="d-flex justify-content-sm-end">
                                        <div class="search-box ms-2">
                                            <input type="text" class="form-control" placeholder="Tìm kiếm...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                        <div class="ms-1">
                                            <button class="btn btn-primary">Tìm kiếm</button>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>

                            <div class="card-body table-responsive table-card mt-3 mb-1">
                                <table class="table align-middle table-nowrap" id="customerTable">
                                    <thead class="table-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên</th>
                                        <th>Ảnh</th>
                                        <th>Địa chỉ</th>
                                        <th>Số phòng</th>
                                        <th>Số sao</th>
                                        <th>Thành phố</th>
                                        <th>Số điện thoại</th>
                                        <th>Email</th>
                                        <th>Số tầng</th>
                                        <th>Trạng thái</th>
                                        <th>###</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                    @foreach ($data as $key => $hotel)
                                        <tr>
                                            <td class="id" style="display:none;"><a href="javascript:void(0);"
                                                                                    class="fw-medium link-primary"></a>
                                            </td>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $hotel->name }}</td>
                                            <td>
                                                <div class="hotel-image-wrapper">
                                                    <img src="{{ \Storage::url($hotel->thumbnail) }}"
                                                         alt="{{ $hotel->name }}">
                                                </div>
                                            </td>

                                            <td>{{ $hotel->location }}</td>
                                            <td>{{ $hotel->quantity_of_room }}</td>
                                            <td>{{ $hotel->star }}</td>
                                            <td>{{ $hotel->city->name ?? 'Thành phố đã bị xoá' }}</td>
                                            <td>{{ $hotel->phone }}</td>
                                            <td>{{ $hotel->email }}</td>
                                            <td>{{ $hotel->quantity_floor }}</td>
                                            <td>{!! $hotel->status
                                                    ? '<span class="badge rounded-pill border border-success text-success">Hoạt động</span>'
                                                    : '<span class="badge rounded-pill border border-danger text-danger">Không hoạt động</span>' !!}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <div class="restore">
                                                        <a href="{{ route('admin.hotels.restore', $hotel->id) }}"
                                                           class="btn btn-sm btn-soft-info restore-item-btn">
                                                            <i class="ri-arrow-go-back-line"></i>
                                                        </a>
                                                    </div>

                                                    {{-- <div class="remove">
                                                        <button class="btn btn-sm btn-soft-danger remove-item-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteRecordModal{{ $hotel->id }}">
                                                            <i class="ri-delete-bin-2-fill"></i>
                                                        </button>
                                                    </div> --}}

                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal -->
                                        <div class="modal fade zoomIn" id="deleteRecordModal{{ $hotel->id }}"=))
                                             tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"
                                                                id="btn-close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mt-2 text-center">
                                                            <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json"
                                                                       trigger="loop"
                                                                       colors="primary:#f7b84b,secondary:#f06548"
                                                                       style="width:100px;height:100px"></lord-icon>
                                                            <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                                                <h4>Bạn chắc chắn ?</h4>
                                                                <p class="text-muted mx-4 mb-0">Bạn có chắc muốn xóa mã
                                                                    này ?</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                            <form action="{{ route('admin.hotels.forceDelete', $hotel->id) }}"
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
                            </div>

                            <div class="d-flex justify-content-end">
                                <div class="pagination-wrap hstack gap-2">

                                </div>
                            </div>

                        </div>
                        {{ $data->links() }}
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

                .hotel-image-wrapper img {
                    width: 50px;
                    height: 50px;
                    object-fit: cover;
                    border-radius: 8px;
                    border: 1px solid #ddd;
                    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
                }

                .hotel-image-wrapper img:hover {
                    transform: scale(1.05);
                    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.15);
                }
            </style>
        @endsection

        @section('style-libs')
            <!-- Sweet Alert css-->
            <link href="{{ asset('theme/admin/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"
                  type="text/css" />
        @endsection

        @section('scripts')
            <script>
                function deleteMultiple() {
                    let formDelMulti = document.querySelector("#deleteMulti");
                    let checkboxes = formDelMulti.querySelectorAll(".service");

                    // Kiểm tra xem có ít nhất một checkbox được chọn hay không
                    let hasChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

                    if (confirm("Bạn có muốn xóa các dịch vụ khách sạn này không?")) {
                        if (!hasChecked) {
                            // Nếu chưa chọn checkbox nào, hiển thị thông báo
                            alert("Bạn cần chọn ít nhất 1 dịch vụ");
                            return;
                        }
                        formDelMulti.submit();
                    }
                }
            </script>

            <script>
                function addImageGallery() {
                    let id = 'gen' + '_' + Math.random().toString(36).substring(2, 15).toLowerCase();
                    let html = `
        <div class="col-md-4" id="${id}_item">
            <label for="${id}" class="form-label">Image</label>
            <div class="d-flex">
                <input type="file" class="form-control" name="images[]" id="${id}">
                <button type="button" class="btn btn-danger" onclick="removeImageGallery('${id}_item')">
                    <span class="bx bx-trash"></span>
                </button>
            </div>
        </div>
    `;

                    $('#gallery_list').append(html);
                }

                function removeImageGallery(id) {
                    $('#' + id).remove()
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
