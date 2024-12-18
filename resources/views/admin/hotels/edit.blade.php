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
                        <li class="breadcrumb-item"><a href="{{route('admin.hotels.index')}}">Khách sạn</a></li>
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
            <div class="col-lg-8 col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col">
                                <label class="form-label" for="name">Tên khách sạn<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ $data->name }}" placeholder="Tên khách sạn">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col">
                                <label class="form-label" for="percent_incidental">Phần trăm tiền phạt<span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('percent_incidental') is-invalid @enderror" id="percent_incidental" name="percent_incidental"
                                       value="{{ $data->percent_incidental }}" placeholder="Phần trăm tiền phạt">
                                @error('percent_incidental')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="thumbnail">Ảnh đại diện</label>
                            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail"
                                   value="{{ old('thumbnail') }}">
                            @error('thumbnail')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="mb-2">
                                <img width="100px" src="{{ \Storage::url($data->thumbnail) }}"
                                     alt="{{ $data->name }}">
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h5 class="card-title mb-0 flex-grow-1">Hình ảnh</h5>
                                    <button type="button" class="btn btn-primary"
                                            onclick="addImageGallery()">Thêm ảnh</button>
                                </div>
                                <div class="justify-content-end d-flex mt-2 me-3">
                                    <a href="#" class="float-end text-decoration-underline" data-bs-toggle="modal"
                                    data-bs-target=".bs-example-modal-xl">Danh sách ảnh</a>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4" id="gallery_list">
                                            <div class="col-md-4" id="gallery_default_item">
                                                <div class="d-flex">
                                                    <input type="file" class="form-control @error('images') is-invalid @enderror"
                                                           name="images[]" id="gallery_default" multiple>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @error('images.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                <div class="mb-3 col">
                                    <label class="form-label" for="quantity_of_room">Số lượng phòng<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="quantity_of_room" name="quantity_of_room"
                                           value="{{ $data->quantity_of_room}}" placeholder="Số lượng phòng">
                                </div>
        
                                <div class="mb-3 col">
                                    <label class="form-label" for="quantity_floor">Số tầng<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="quantity_floor" name="quantity_floor"
                                           value="{{ $data->quantity_floor }}" placeholder="Số tầng">
                                </div>
                            </div>
                            <div class="form-check form-switch form-switch-success">
                                <input class="form-check-input" type="checkbox" name="status" id="SwitchCheck6"
                                       {{ $data->status ? 'checked' : '' }} value="1">
                                <label class="form-check-label" for="SwitchCheck6">
                                    Hoạt động
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col">
                                <label class="form-label" for="location">Địa chỉ<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="location" name="location"
                                       value="{{ $data->location }}" placeholder="Địa chỉ">
                            </div>
    
                            <div class="mb-3 col">
                                <label class="form-label" for="city_id">Thành
                                    phố<span class="text-danger">*</span></label>
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
                <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="description">Mô tả</label>
                                <textarea class="form-control" name="description" id="description" rows="2">{!! $data->description !!}</textarea>
                                @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="view">Lượt xem</label>
                            <input type="text" class="form-control" id="view"
                                   value="{{ $data->view }}" placeholder="Lượt xem" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="star">Số sao<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="star" name="star"
                                   value="{{ $data->star }}" placeholder="Số sao">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">Số điện thoại<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                   value="{{ $data->phone  }}" placeholder="Số điện thoại">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">Email<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="email" name="email"
                                   value="{{ $data->email }}" placeholder="Email">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="province">Tỉnh<span class="text-danger">*</span></label>
                            <input type="text" class="form-control  @error('province') is-invalid @enderror" id="province" name="province"
                                   value="{{ $data->province }}" placeholder="Tỉnh">
                            @error('province')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="district">Huyện<span class="text-danger">*</span></label>
                            <input type="text" class="form-control  @error('district') is-invalid @enderror" id="district" name="district"
                                   value="{{ $data->district }}" placeholder="Huyện">
                            @error('district')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="commune">Xã<span class="text-danger">*</span></label>
                            <input type="text" class="form-control  @error('commune') is-invalid @enderror" id="commune" name="commune"
                                   value="{{ $data->commune }}" placeholder="Xã">
                            @error('commune')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="latitude">Vĩ độ<span class="text-danger">*</span></label>
                            <input type="text" class="form-control  @error('latitude') is-invalid @enderror" id="latitude" name="latitude"
                                   value="{{ $data->latitude }}" placeholder="Vĩ độ">
                            @error('latitude')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="longitude">Kinh độ<span class="text-danger">*</span></label>
                            <input type="text" class="form-control  @error('longitude') is-invalid @enderror" id="longitude" name="longitude"
                                   value="{{ $data->longitude }}" placeholder="Kinh độ">
                            @error('longitude')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-lg-6 col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="province">Tỉnh</label>
                            <input type="text" class="form-control  @error('province') is-invalid @enderror" id="province" name="province"
                                   value="{{ $data->province }}" placeholder="Tỉnh">
                            @error('province')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="district">Huyện</label>
                            <input type="text" class="form-control  @error('district') is-invalid @enderror" id="district" name="district"
                                   value="{{ $data->district }}" placeholder="Huyện">
                            @error('district')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="commune">Xã</label>
                            <input type="text" class="form-control  @error('commune') is-invalid @enderror" id="commune" name="commune"
                                   value="{{ $data->commune }}" placeholder="Xã">
                            @error('commune')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="col-lg-6 col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="latitude">Vĩ độ</label>
                            <input type="text" class="form-control  @error('latitude') is-invalid @enderror" id="latitude" name="latitude"
                                   value="{{ $data->latitude }}" placeholder="Vĩ độ">
                            @error('latitude')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="longitude">Kinh độ</label>
                            <input type="text" class="form-control  @error('longitude') is-invalid @enderror" id="longitude" name="longitude"
                                   value="{{ $data->longitude }}" placeholder="Kinh độ">
                            @error('longitude')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="col-lg-6 col-md-6 mb-3">
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
            </div> --}}

            {{-- <div class="col-lg-12 col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="description">Mô tả</label>
                            <textarea class="form-control" name="description" id="description" rows="2">{!! $data->description !!}</textarea>
                            @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

        <div class="text-end mb-4">
            <button type="submit" class="btn btn-warning w-sm">Cập nhật</button>
        </div>
    </form>

    <!--  Extra Large modal example -->
    <div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form class="modal-content" action="{{route('admin.catalogue-rooms.delete.image.multi')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">Danh Sách Ảnh</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                @foreach ($data->images as $key => $image)                                
                                    <div class="col-2">
                                        <div class="form-check form-check-secondary mb-3">
                                            <input class="form-check-input" type="checkbox" id="formCheck{{$key}}" name="images_id[]" value="{{$image->id}}">
                                            <label class="form-check-label" for="formCheck{{$key}}">
                                                <img class="img-fluid img-thumbnail mt-2"
                                                style="width: 100%; height: 100px; object-fit: cover"
                                                src="{{ \Storage::url($image->path) }}" alt="ảnh">
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-link link-primary fw-medium" data-bs-dismiss="modal"><i
                            class="ri-close-line me-1 align-middle"></i> Đóng</a>
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có muốn xóa những ảnh đã chọn không!')">Xóa Ảnh</button>
                </div>
            </form><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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
        CKEDITOR.replace('description')
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
