@extends('admin.layouts.master')

@section('title')
    Danh sách vai trò
@endsection

@section('content')

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Danh sách vai trò</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Phòng</a></li>
                            <li class="breadcrumb-item active">Danh sách</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Danh sách vai trò</h4>
                        @if (session('toast_message'))
                            <div class="card-header   alert alert-{{ session('toast_style') }} alert-dismissible fade show" role="alert">
                                {{ session('toast_message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="listjs-table" id="">
                            {{--                            <div class="row g-4 mb-3">--}}
                            {{--                                <div class="col-sm-auto">--}}
                            {{--                                    <div>--}}
                            {{--                                        <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"--}}
                            {{--                                                id="create-btn" data-bs-target="#showModal"><i--}}
                            {{--                                                    class="ri-add-line align-bottom me-1"></i> Thêm mới--}}
                            {{--                                        </button>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="col-sm">--}}
                            {{--                                    <form method="get" action="{{ route('rooms.index') }}">--}}
                            {{--                                        @csrf--}}
                            {{--                                        <div class="d-flex justify-content-sm-end">--}}
                            {{--                                            <div class="search-box ms-2">--}}
                            {{--                                                <input type="text" class="form-control " placeholder="Search..."--}}
                            {{--                                                       name="keyword">--}}
                            {{--                                                <i class="ri-search-line search-icon"></i>--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                    </form>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}

                            <div class="table-responsive table-card mt-3 mb-1">
                                <table class="table align-middle table-nowrap" id="customerTable">
                                    <thead class="table-light">
                                    <tr>
                                        {{--                                        <th scope="col" style="width: 50px;">--}}
                                        {{--                                            <div class="form-check">--}}
                                        {{--                                                <input class="form-check-input" type="checkbox" id="checkAll"--}}
                                        {{--                                                       value="option">--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </th>--}}
                                        <th class="sort" data-sort="status">STT</th>
                                        <th class="sort" data-sort="status">Tên</th>
                                        <th class="sort" data-sort="action">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                    @foreach ($groups as $key => $group)
                                        <tr>
                                            <td class="id">{{ $key +1 }}</td>
                                            <td class="id">{{ $group->name }}</td>
                                            <td>
                                                <a href="{{route('admin.permissions.edit', $group->id)}}"
                                                   class="btn btn-soft-warning me-2"><i
                                                            class="ri-edit-2-line"></i></a>
                                            </td>
                                        </tr>

                                    @endforeach


                                    </tbody>
                                </table>
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


                            {{--                            <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"--}}
                            {{--                                 aria-hidden="true">--}}
                            {{--                                <div class="modal-dialog modal-dialog-centered">--}}
                            {{--                                    <div class="modal-content">--}}
                            {{--                                        <div class="modal-header bg-light p-3">--}}
                            {{--                                            <h5 class="modal-title" id="exampleModalLabel">Thêm phòng</h5>--}}
                            {{--                                            <button type="button" class="btn-close" data-bs-dismiss="modal"--}}
                            {{--                                                    aria-label="Close" id="close-modal"></button>--}}
                            {{--                                        </div>--}}
                            {{--                                        <form class="tablelist-form" autocomplete="off"--}}
                            {{--                                              action="{{route('rooms.store')}}" method="POST">--}}
                            {{--                                            @csrf--}}
                            {{--                                            <div class="modal-body">--}}
                            {{--                                                <div class="mb-3">--}}
                            {{--                                                    <label for="catalogue_room_id" class="form-label">Loại phòng</label>--}}
                            {{--                                                    <select class="form-control" data-trigger name="catalogue_room_id"--}}
                            {{--                                                            id="type-field" required>--}}
                            {{--                                                        @foreach($catalogueRooms as $catalogueRoom)--}}
                            {{--                                                            <option value="{{$catalogueRoom->id}}">{{$catalogueRoom->name}}</option>--}}
                            {{--                                                        @endforeach--}}
                            {{--                                                    </select>--}}
                            {{--                                                </div>--}}

                            {{--                                                <div class="mb-3">--}}
                            {{--                                                    <label for="catalogue_room_id" class="form-label">Trạng thái</label>--}}
                            {{--                                                    <select class="form-control" data-trigger name="status"--}}
                            {{--                                                            id="type-field" required>--}}
                            {{--                                                        @foreach(\App\Constant\Enum\RoomStatusEnum::cases() as $groupStatus)--}}
                            {{--                                                            <option value="{{$groupStatus->value}}">{{$groupStatus->getName()}}</option>--}}
                            {{--                                                        @endforeach--}}
                            {{--                                                    </select>--}}
                            {{--                                                </div>--}}
                            {{--                                            </div>--}}
                            {{--                                            <div class="modal-footer">--}}
                            {{--                                                <div class="modal-footer">--}}
                            {{--                                                    <div class="hstack gap-2 justify-content-end">--}}
                            {{--                                                        <button type="button" class="btn btn-light"--}}
                            {{--                                                                data-bs-dismiss="modal">--}}
                            {{--                                                            Close--}}
                            {{--                                                        </button>--}}
                            {{--                                                        <button type="submit" class="btn btn-success" id="add-btn">Thêm--}}
                            {{--                                                            mới--}}
                            {{--                                                        </button>--}}
                            {{--                                                    </div>--}}
                            {{--                                                </div>--}}
                            {{--                                            </div>--}}
                            {{--                                        </form>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                        </div>

                        {{--                        <div class="d-flex justify-content-end">--}}
                        {{--                            <div class="pagination-wrap hstack gap-2">--}}
                        {{--                                {{ $groups->links() }}--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

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
