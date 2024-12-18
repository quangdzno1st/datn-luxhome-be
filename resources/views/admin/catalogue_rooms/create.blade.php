@extends('admin.layouts.master')

@section('style-libs')
    <!-- Plugins css -->
    <link href="{{ asset('theme/admin/assets/libs/dropzone/dropzone.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('styles')
    <!-- Plugins css -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                        <li class="breadcrumb-item"><a href="{{route('admin.catalogue-rooms.index')}}">Loại phòng</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Notification -->
    {{-- <div class="row">
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{session('error')}}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    </div> --}}

    <form action="{{ route('admin.catalogue-rooms.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">Tên loại phòng<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                                placeholder="Tên loại phòng">
                            @error('name')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="thumbnail">Ảnh đại diện<span class="text-danger">*</span></label>
                            <input class="form-control" id="thumbnail" type="file" name="thumbnail"
                                accept="image/png, image/gif, image/jpeg">
                                @error('thumbnail')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="mb-3 row">
                           <div class="col">
                            <label class="form-label" for="price">Giá phòng<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}"
                                placeholder="Giá phòng">
                                @error('price')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                           </div>
                           {{-- <div class="col"> --}}
                            {{-- <label class="form-label" for="price_hour">Giá phạt checkout quá giờ<span class="text-danger">*</span></label> --}}
                            <input type="hidden" class="form-control" id="price_hour" name="price_hour" value="0"
                                placeholder="Giá phạt">
                                {{-- @error('price_hour')
                                <p class="text-danger">{{$message}}</p>
                            @enderror --}}
                           {{-- </div> --}}
                        </div>

                        <div class="mb-2 d-flex justify-content-end">
                            <div class="form-check form-switch form-switch-success">
                                <input class="form-check-input" type="checkbox" role="switch" name="status" id="SwitchCheck3" value="1" checked>
                                <label class="form-check-label" for="SwitchCheck3">Hoạt động</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả<span class="text-danger">*</span></label>
                            <div>
                                <textarea class="form-control" id="content" rows="2" name="description">{{old('description')}}</textarea>
                                @error('description')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <!-- end card -->
                <div class="text-end mb-3">
                    <button type="submit" class="btn btn-success w-sm">Thêm Mới</button>
                </div>
            </div>
            <!-- end col -->
            <div class="col-lg-4">
                @if (Auth::user()->type == 2)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Khách sạn</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="hotel" class="form-label">Khách sạn<span class="text-danger">*</span></label>
                                <select name="hotel_id" id="hotel" class="form-select">
                                    <option value="">--Chọn khách sạn--</option>
                                    @foreach ($hotels as $hotel)
                                        <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                    @endforeach
                                </select>
                                @error('hotel_id')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                            </div>
                            <!-- end card body -->
                        </div>
                    </div>
                    <!-- end card -->
                @else
                    <input type="hidden" value="{{Auth::user()->org_id}}" name="hotel_id">
                @endif
                {{-- <input type="hidden" value="{{Auth::user()->org_id}}" name="hotel_id"> --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Hình ảnh<span class="text-danger">*</span></h5>
                    </div>
                    <div class="card-body">
                        <div class="col-lg-12 col-sm-12">
                            <div class="mb-1 row">
                                <div class="">
                                    <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                        name="images[]" id="images" multiple accept="image/*">

                                    @error('images')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    @error('images.*')
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
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Chi tiết loại phòng</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="mb-3">
                                <label class="form-label" for="">Sức chứa<span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col">
                                        <input type="number" class="form-control" id="number_adult" value="{{old('number_adult')}}"
                                        placeholder="Người lớn" name="number_adult">
                                        @error('number_adult')
                                        <p class="text-danger">{{$message}}</p>
                                    @enderror
                                    </div>
                                    <div class="col">
                                        <input type="number" class="form-control" id="number_child" value="{{old('number_child')}}"
                                        placeholder="Trẻ em" name="number_child">
                                        @error('number_child')
                                        <p class="text-danger">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="mb-3">
                                <label class="form-label" for="acreage">Diện tích<span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="acreage" name="acreage" value="{{ old('acreage') }}"
                                    placeholder="Diện tích">
                                    @error('acreage')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="">
                            <label class="form-label" for="choices-multiple-remove-button">Tiện ích</label>
                            <select class="form-control" id="choices-multiple-remove-button" data-choices
                                data-choices-removeItem name="attributes[]" multiple>
                                @foreach ($attributes as $attribute)
                                    <option value="{{$attribute->id}}">{{$attribute->content}}</option>
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
    <script src="{{ asset('theme/admin/assets/js/pages/select2.init.js') }}"></script>
@endsection

@section('scripts')
    <script>
        CKEDITOR.replace('content')
    </script>
    <script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
    <!--jquery cdn-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--select2 cdn-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            let imagesArray = [];

            $('#images').on('change', function() {
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

                    imgElement.on('click', function() {
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
