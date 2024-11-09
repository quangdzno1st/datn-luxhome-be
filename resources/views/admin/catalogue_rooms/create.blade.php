@extends('admin.layouts.master')

@section('style-libs')
    <!-- Plugins css -->
    <link href="{{asset('theme/admin/assets/libs/dropzone/dropzone.css')}} rel="stylesheet" type="text/css"/>
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

    <form action="{{route('admin.catalogue-rooms.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">Tên loại phòng</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   {{old('name')}} placeholder="Tên loại phòng">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="price">Giá phòng</label>
                            <input type="number" class="form-control" id="price" name="price"
                                   {{old('price')}} placeholder="Giá phòng">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="thumbnail">Thumbnail</label>
                            <input class="form-control" id="thumbnail" type="file" name="thumbnail"
                                   {{old('thumbnail')}}
                                   accept="image/png, image/gif, image/jpeg">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="view">Lượt xem</label>
                            <input type="number" class="form-control" id="view" min="0" name="view"
                                   {{old('view')}} placeholder="Lượt xem">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="like">Lượt thích</label>
                            <input type="number" class="form-control" id="like" min="0" name="like"
                                   {{old('like')}} placeholder="Lượt thích">
                        </div>

                        <div class="form-check form-check-right mb-2">
                            <input class="form-check-input" type="checkbox" name="status" id="formCheckboxRight1"
                                   checked {{old('status')}}>
                            <label class="form-check-label" for="status">
                                Hoạt động
                            </label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <div>
                                <textarea class="form-control" id="content" rows="2" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <div class="row col-6">
                    <div class="col-lg-12 col-sm-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="images">Hình ảnh</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                       name="images[]" id="images" multiple accept="image/*">

                                @error('images')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                                <input type="file" name="representative_image" id="representative_image_input"
                                       style="display: none;">
                                <div id="image-preview" class="mt-2"></div>
                            </div>
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
                            <label for="disabledInput" class="form-label">Khách sạn</label>
                            <input type="text" class="form-control"
                                   id="disabledInput" {{$hotel->name}}
                                   disabled>
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
                                       {{old('capacity')}} placeholder="Sức chứa">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="mb-3">
                                <label class="form-label" for="acreage">Diện tích</label>
                                <input type="text" class="form-control" id="acreage"
                                       {{old('acreage')}} placeholder="Diện tích">
                            </div>
                        </div>

                        <div class="">
                            <label class="form-label" for="acreage">Nội thất</label>
                            <select class="form-control" id="choices-multiple-remove-button"
                                    data-choices data-choices-removeItem name="noiThat[]" multiple>
                                @foreach($attributeValues as $attributeValue)
                                    <option {{$attributeValue['id']}}>{{$attributeValue['value_text']}}</option>
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
        $(document).ready(function () {
            let imagesArray = [];

            $('#images').on('change', function () {
                const files = this.files;
                $('#image-preview').empty();

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const imgURL = URL.createObjectURL(file);
                    imagesArray.push(imgURL);

                    const imgElement = $('<img>', {
                        src: imgURL,
                        class: 'img-fluid img-thumbnail',
                        style: 'width: 100px; height: 100px; object-fit: cover; margin: 5px;'
                    });

                    imgElement.on('click', function () {
                        $('#representative-image').attr('src', imgURL).show();
                        const fileInput = $('#representative_image_input')[0];
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        fileInput.files = dataTransfer.files;
                    });

                    $('#image-preview').append(imgElement);
                }
            });
        })
    </script>


@endsection
