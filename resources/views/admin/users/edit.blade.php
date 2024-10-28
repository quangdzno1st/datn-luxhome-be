@extends('admin.layouts.master')

@section('title')
    Cập nhật tài khoản
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Cập nhật tài khoản</h4>

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



    <form action="{{ route('admin.users.update', $user) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row gy-4">
                                <div class="col-7">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name:</label>
                                        <input type="text" class="form-control" id="name" placeholder="Enter name"
                                            name="name" value="{{ $user->name }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="email" class="form-control" id="email" placeholder="Enter email"
                                            name="email" value="{{ $user->email }}">
                                    </div>
                                    {{-- <div class="mb-3">
                                        <label for="password" class="form-label">Password:</label>
                                        <input type="text" class="form-control" id="password" placeholder="Enter password"
                                            name="password" value="{{$user->password}}">
                                    </div> --}}
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone number:</label>
                                        <input type="text" class="form-control" id="phone" placeholder="Enter phone"
                                            name="phone" value="{{$user->phone}}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address:</label>
                                        <input type="text" class="form-control" id="address" placeholder="Enter address"
                                            name="address" value="{{$user->address}}">
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="mb-3">
                                        <label for="avatar" class="form-label">Avatar:</label>
                                        <input type="file" class="form-control" id="avatar" name="avatar">
                                        <img src="{{ \Storage::url($user->avatar) }}" alt="" width="100px">
                                    </div>
                                    <div class="mb-3 d-flex">
                                        <div class="form-check form-radio-danger mb-3 me-3">
                                            <input class="form-check-input" type="radio" name="type" id="admin"
                                                value="admin" {{ $user->type == 'admin' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="admin">
                                                Admin
                                            </label>
                                        </div>
                                        <div class="form-check form-radio-success mb-3">
                                            <input class="form-check-input" type="radio" name="type" id="member"
                                                value="member" {{ $user->type == 'member' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="member">
                                                Member
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="exampleCheck1" value="1"
                                            name="is_active" {{ $user->is_active == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="exampleCheck1">Is active</label>
                                    </div>
                                </div>
                                <div class="card-header align-items-center d-flex">
                                    <button type="submit" class="btn btn-success">Cập nhật</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
