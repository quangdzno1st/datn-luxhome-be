@extends('admin.layouts.master')

@section('title')
    Danh sách account
@endsection

@section('content')
    <!-- start page title -->

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Danh sách tài khoản</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Danh sách</a></li>
                        <li class="breadcrumb-item active">Tài khoản</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                @if (session('toast_message'))
                    <div class="card-header   alert alert-{{ session('toast_style') }} alert-dismissible fade show" role="alert">
                        {{ session('toast_message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card-header d-flex justify-content-between">
                    @can('create_users')
                    <div class="">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-success">+ Thêm mới</a>
                    </div>
                    @endcan
                    <form method="GET" id="searchForm" action="{{ route('admin.users.index') }}" class="d-flex justify-content-end mb-3">
                        <div class="input-group w-auto">
                            <!-- Tìm kiếm theo tên -->
                            <input type="text" class="form-control" name="search" id="searchInput" placeholder="Tìm kiếm người dùng..." value="{{ request()->input('search') }}">
                        </div>
                        <div class="input-group w-auto ms-2">
                            <!-- Tìm kiếm theo type (Customer/Admin) -->
                            <select name="type" class="form-select" id="typeSelect">
                                <option value="">Tất cả loại</option>
                                <option value="{{ \App\Models\User::ADMIN }}" {{ request()->input('type') == \App\Models\User::ADMIN ? 'selected' : '' }}>Chủ chuỗi khách sạn</option>
                                <option value="{{ \App\Models\User::HOTELIER }}" {{ request()->input('type') == \App\Models\User::HOTELIER ? 'selected' : '' }}>Quản lý khách sạn</option>
                                <option value="{{ \App\Models\User::STAFF }}" {{ request()->input('type') == \App\Models\User::STAFF ? 'selected' : '' }}>Nhân viên khách sạn</option>
                                <option value="{{ \App\Models\User::CUSTOMER }}" {{ request()->input('type') == \App\Models\User::CUSTOMER ? 'selected' : '' }}>Người dùng</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary ms-2">Tìm kiếm</button>
                    </form>
                </div>


                <div class="card-body">
                    <table id="example" class="table table-bordered dt-responsive nowrap align-middle"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Ảnh đại diện</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Loại tài khoản</th>

                                <th>Trạng thái</th>
                                @can('edit_users')
                                <th>Hành động</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $user)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>
                                        <img src="{{!empty($user->avatar) ? \Storage::url($user->avatar) : asset('theme/client/images/uploads/avatar.jpg')}}" alt="Avatar" style="width: 50px;  object-fit: cover;">
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        @if ($user->type === \App\Models\User::CUSTOMER)
                                        <span class="badge bg-info">Người dùng</span>
                                        @elseif($user->type === \App\Models\User::HOTELIER)
                                        <span class="badge bg-danger">Quản lý khách sạn</span>
                                        @elseif($user->type === \App\Models\User::STAFF)
                                        <span class="badge bg-success">Nhân viên</span>
                                        @else
                                            <span class="badge bg-warning">Admin</span>
                                    @endif
                                    </td>
                                    <td>
                                        @if ($user->is_active)
                                            <span class="badge bg-success-subtle text-success text-uppercase">Active</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger text-uppercase">Inactive</span>
                                        @endif
                                    </td>
                                    @can('edit_users')
                                    <td class="d-flex justify-content-center">

                                        <a href="{{route('admin.users.edit', $user)}}" class="btn btn-soft-warning me-2"><i
                                            class="ri-edit-2-line"></i></a>

{{--                                        <form action="{{route('admin.users.destroy', $user)}}" method="post">--}}
{{--                                            @csrf--}}
{{--                                            @method('DELETE')--}}
{{--                                            <button type="submit" class="btn btn-soft-danger" onclick="return confirm('Bạn có muốn xóa không?')"><i class="ri-delete-bin-line"></i></button>--}}
{{--                                        </form>--}}
                                    </td>
                                       @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->

@endsection

@section('style-libs')
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection

@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script>
        new DataTable("#example", {
            paging: false,
            info: false,
            searching: false,
            order: [
                [0, 'desc']
            ]
        });
    </script>
@endsection
