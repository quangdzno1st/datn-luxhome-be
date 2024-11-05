@extends('admin.layouts.master')

@section('style-libs')
    <!-- Plugins css -->
    <link href="{{ asset('theme/admin/assets/libs/dropzone/dropzone.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('styles')
    <!-- Plugins css -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('title')
    Sửa khách sạn
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Sửa khách sạn</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Khách sạn</a></li>
                        <li class="breadcrumb-item active">Sửa</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <form action="{{ route('admin.hotels.update', $data->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-12 col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">Tên khách sạn</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ $data->name }}" placeholder="Tên khách sạn">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Gallery</h4>
                                    <button type="button" class="btn btn-primary"
                                            onclick="addImageGallery()">Thêm ảnh</button>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4" id="gallery_list">
                                            <div class="col-md-4" id="gallery_default_item">
                                                <label for="gallery_default"
                                                       class="form-label">Image</label>
                                                <div class="d-flex">
                                                    <input type="file" class="form-control"
                                                           name="images[]" id="gallery_default">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="hotel-image-wrapper">
                                @foreach ($data->images as $image)
                                    <img style="width: 50px; height: 50px; object-fit: cover" src="{{ \Storage::url($image->path) }}"
                                         alt="{{ $image->alt }}">
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="location">Địa chỉ</label>
                            <input type="text" class="form-control" id="location" name="location"
                                   value="{{ $data->location }}" placeholder="Địa chỉ">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="city_id">Thành
                                phố</label>
                            <select class="form-control" id="city_id"
                                    name="city_id">
                                <option value="">Chọn thành phố</option>
                                @foreach ($regions as $region)
                                    <optgroup label="{{ $region->name }}">
                                        @foreach ($region->cities as $city)
                                            <option value="{{ $city->id }}"
                                                    {{ $city->id == $data->city_id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="star">Số sao</label>
                            <input type="text" class="form-control" id="star" name="star"
                                   value="{{ $data->star }}" placeholder="Số sao">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                   value="{{ $data->phone  }}" placeholder="Số điện thoại">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email"
                                   value="{{ $data->email }}" placeholder="Email">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="quantity_of_room">Số lượng phòng</label>
                            <input type="text" class="form-control" id="quantity_of_room" name="quantity_of_room"
                                   value="{{ $data->quantity_of_room}}" placeholder="Số lượng phòng">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="quantity_floor">Số tầng</label>
                            <input type="text" class="form-control" id="quantity_floor" name="quantity_floor"
                                   value="{{ $data->quantity_floor }}" placeholder="Số tầng">
                        </div>

                        <div class="form-check form-check-right mb-2">
                            <input class="form-check-input" type="checkbox" name="status" id="formCheckboxRight1"
                                   {{ $data->status ? 'checked' : '' }} value="1">
                            <label class="form-check-label" for="status">
                                Hoạt động
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mb-4">
            <button type="submit" class="btn btn-success w-sm">Sửa</button>
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
        function addImageGallery() {
            let id = 'gen' + '_' + Math.random().toString(36).substring(2, 15).toLowerCase();
            let html = `
        <div class="col-md-4" id="${id}_item">
            <label for="${id}" class="form-label">Ảnh</label>
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
