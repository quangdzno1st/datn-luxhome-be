@extends('admin.layouts.master')

@section('title')
    Cập nhật quyền
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Cập nhật quyền</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tài khoản</a></li>
                        <li class="breadcrumb-item active">Cập nhật</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->



    <form action="{{ route('admin.permissions.update', $group->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="mb-3">
                                <label for="modules" class="form-label">Quản lý Module:</label>
                                <div class="row mt-3">
                                    @foreach($modules as $module)
                                        <div class="col-4">
                                            <!-- Quyền "Xem" -->
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="view{{$loop->index}}"
                                                       name="permissions[]" value="view_{{$module->name}}"
                                                       @if(is_array(json_decode($group->permissions, true)) && in_array("view_{$module->name}", json_decode($group->permissions, true)))
                                                           checked
                                                        @endif
                                                >
                                                <label class="form-check-label" for="view{{$loop->index}}">Xem {{$module->title}}</label>
                                            </div>

                                            <!-- Quyền "Thêm" -->
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="create{{$loop->index}}"
                                                       name="permissions[]" value="create_{{$module->name}}"
                                                       @if(is_array(json_decode($group->permissions, true)) && in_array("create_{$module->name}", json_decode($group->permissions, true)))
                                                           checked
                                                        @endif
                                                >
                                                <label class="form-check-label" for="create{{$loop->index}}">Thêm {{$module->title}}</label>
                                            </div>

                                            <!-- Quyền "Sửa" -->
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="edit{{$loop->index}}"
                                                       name="permissions[]" value="edit_{{$module->name}}"
                                                       @if(is_array(json_decode($group->permissions, true)) && in_array("edit_{$module->name}", json_decode($group->permissions, true)))
                                                           checked
                                                        @endif
                                                >
                                                <label class="form-check-label" for="edit{{$loop->index}}">Sửa {{$module->title}}</label>
                                            </div>

                                            <!-- Quyền "Xóa" -->
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="delete{{$loop->index}}"
                                                       name="permissions[]" value="delete_{{$module->name}}"
                                                       @if(is_array(json_decode($group->permissions, true)) && in_array("delete_{$module->name}", json_decode($group->permissions, true)))
                                                           checked
                                                        @endif
                                                >
                                                <label class="form-check-label" for="delete{{$loop->index}}">Xóa {{$module->title}}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Nút Cập nhật -->
                            <div class="mt-4">
                                <button type="submit" class="btn btn-success">Cập nhật</button>
                                <a href="{{ route('admin.permissions') }}" class="btn btn-primary">Quay lại</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Hàm kiểm tra khi thay đổi radio button
        $('input[name="type"]').on('change', function () {
            if ($('#member').is(':checked')) {
                $('#hotelSelect').prop('disabled', true); // Vô hiệu hóa trường chọn khách sạn
            } else {
                $('#hotelSelect').prop('disabled', false); // Bật lại trường chọn khách sạn
            }
        });

        $('#togglePassword').click(function () {
            // Get the input field and toggle the type
            const passwordField = $('#password');
            const passwordFieldType = passwordField.attr('type');

            if (passwordFieldType === 'password') {
                passwordField.attr('type', 'text');
                // Change the eye icon to "open" when password is visible
                $(this).removeClass('bi-eye-slash').addClass('bi-eye');
            } else {
                passwordField.attr('type', 'password');
                // Change the eye icon to "closed" when password is hidden
                $(this).removeClass('bi-eye').addClass('bi-eye-slash');
            }
        });

        // Gọi hàm ngay khi tải trang để đặt trạng thái đúng
        if ($('#member').is(':checked')) {
            $('#hotelSelect').prop('disabled', true);
        }
    });
</script>
@endsection

