@extends("admin.layouts.master")

@section('title')
    Thêm mới banner
@endsection

@section('content')
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Thêm mới banner</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Banner</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body">
                        <div class="listjs-table" id="customerList">
                            <div class="row g-4 mb-3">
                                <div class="col-sm-auto">
                                    <div>
                                        <a href="{{route('admin.banners.index')}}" class="btn btn-primary bg-gradient waves-effect waves-primary"><i
                                                    class="ri-arrow-left-line"></i> Quay lại
                                        </a>
                                        {{-- <button class="btn btn-soft-danger" onClick="deleteMultiple()"><i
                                                    class="ri-delete-bin-2-line"></i></button> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <form action="{{route('admin.banners.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="image">Hình ảnh<span class="text-danger">*</span></label>
                                    <input type="file" name="image" class="form-control">
                                    @error('image')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 d-flex justify-content-start">
                                    <div class="form-check form-switch form-switch-success">
                                        <input class="form-check-input" type="checkbox" role="switch" name="status" id="SwitchCheck3" value="1" checked>
                                        <label class="form-check-label" for="SwitchCheck3">Hoạt động</label>
                                    </div>
                                    @error('status')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">Thêm mới</button>
                                </div>
                            </form>
                        </div>
                    </div><!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->


        @endsection

        @section('style-libs')
            <!-- Sweet Alert css-->
            <link href="{{ asset('theme/admin/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"
                  type="text/css"/>
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


