@extends('admin.layouts.master')

@section('style-libs')
    <!-- Plugins css -->
    <link href="{{asset('theme/admin/assets/libs/dropzone/dropzone.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('styles')
    <!-- Plugins css -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('title')
    Thêm mới loại phòng
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Thêm mới loại phòng</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Loại phòng</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{route('catalogue-rooms.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">Tên loại phòng</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{old('name')}}" placeholder="Tên loại phòng">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="price">Giá phòng</label>
                            <input type="number" class="form-control" id="price" name="price"
                                   value="{{old('price')}}" placeholder="Giá phòng">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="thumbnail">Thumbnail</label>
                            <input class="form-control" id="thumbnail" type="file" name="thumbnail"
                                   value="{{old('thumbnail')}}"
                                   accept="image/png, image/gif, image/jpeg">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="view">Lượt xem</label>
                            <input type="number" class="form-control" id="view" min="0" name="view"
                                   value="{{old('view')}}" placeholder="Lượt xem">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="like">Lượt thích</label>
                            <input type="number" class="form-control" id="like" min="0" name="like"
                                   value="{{old('like')}}" placeholder="Lượt thích">
                        </div>

                        <div class="form-check form-check-right mb-2">
                            <input class="form-check-input" type="checkbox" name="status" id="formCheckboxRight1"
                                   checked
                                   value="{{old('status')}}">
                            <label class="form-check-label" for="status">
                                Hoạt động
                            </label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <div>
                                <textarea class="form-control" id="content" rows="2" name="content"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Attached files</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <p class="text-muted">Add Attached files here.</p>

                            <div class="dropzone" id="dropzone" >
                                <div class="fallback">
                                    <input name="file[]" type="file" multiple="multiple">
                                </div>
                                <div class="dz-message needsclick">
                                    <div class="mb-3">
                                        <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
                                    </div>

                                    <h5>Drop files here or click to upload.</h5>
                                </div>
                            </div>

                            <ul class="list-unstyled mb-0" id="dropzone-preview">
                                <li class="mt-2" id="dropzone-preview-list">
                                    <!-- This is used as the file preview template -->
                                    <div class="border rounded">
                                        <div class="d-flex p-2">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm bg-light rounded">
                                                    <img src="#" alt="Project-Image" data-dz-thumbnail
                                                         class="img-fluid rounded d-block"/>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="pt-1">
                                                    <h5 class="fs-14 mb-1" data-dz-name>&nbsp;</h5>
                                                    <p class="fs-13 text-muted mb-0" data-dz-size></p>
                                                    <strong class="error text-danger" data-dz-errormessage></strong>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 ms-3">
                                                <button data-dz-remove class="btn btn-sm btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <!-- end dropzon-preview -->
                        </div>
                    </div>
                </div>
                <!-- end card -->
                <div class="text-end mb-4">
                    <button type="submit" class="btn btn-success w-sm">Create</button>
                </div>
            </div>
            <!-- end col -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Khách sạn</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <select class="js-example-basic-single" name="hotel">
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel['id'] }}">{{ $hotel['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- end card body -->
                    </div>
                </div>
                <!-- end card -->

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Chi tiết loại phòng</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="mb-3">
                                <label class="form-label" for="capacity">Sức chứa</label>
                                <input type="number" class="form-control" id="capacity"
                                       value="{{old('capacity')}}" placeholder="Sức chứa">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="mb-3">
                                <label class="form-label" for="acreage">Diện tích</label>
                                <input type="text" class="form-control" id="acreage"
                                       value="{{old('acreage')}}" placeholder="Diện tích">
                            </div>
                        </div>

                        <div class="">
                            <label class="form-label" for="acreage">Nội thất</label>
                            <select class="form-control" id="choices-multiple-remove-button"
                                    data-choices data-choices-removeItem name="noiThat[]" multiple>
                                @foreach($attributeValues as $attributeValue)
                                    <option value="{{$attributeValue['id']}}">{{$attributeValue['value_text']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
        </div>
    </form>
@endsection

@section('script-libs')
    <script src="https:////cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>

    <!-- ckeditor -->
    <script src="{{ asset('theme/admin/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>

    <!-- dropzone js -->
    <script src="{{ asset('theme/admin/assets/libs/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/js/pages/project-create.init.js') }}"></script>
    <script src="{{asset('theme/admin/assets/js/pages/select2.init.js')}}"></script>

@endsection

@section('scripts')
    <script>CKEDITOR.replace('content')</script>
    <script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
    <!--jquery cdn-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--select2 cdn-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>

        import { Dropzone } from "../../../theme/admin/assets/libs/dropzone/dropzone.mjs";

        document.addEventListener("DOMContentLoaded", function() {
            const myDropzone = new Dropzone("#myDropzone", {
                url: "{{ route('upload-image') }}", // Route tải lên
                maxFilesize: 5, // Giới hạn kích thước file, ví dụ 5MB
                acceptedFiles: 'image/*',
                addRemoveLinks: true,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}" // Thêm CSRF token cho các yêu cầu
                },
                init: function () {
                    this.on("success", function (file, response) {
                        // Khi tải lên thành công, thêm ảnh vào danh sách xem trước
                        let imageUrl = response.url;
                        let listItem = `<li class="mt-2">
                                    <img src="${imageUrl}" alt="Uploaded Image" class="img-fluid" width="100">
                                </li>`;
                        document.getElementById('uploaded-images').insertAdjacentHTML('beforeend', listItem);
                    });
                }
            });
        });


@endsection
