@extends('admin.layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Danh sách voucher</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-auto">
                                <div class="col-sm-auto">
                                    <div>
                                        <a href="{{route('vouchers.create')}}">
                                            <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"
                                                    id="create-btn" data-bs-target="#showModal"><i
                                                        class="ri-add-line align-bottom me-1"></i> Danh sách voucher xóa
                                            </button>
                                        </a>
                                        <button class="btn btn-soft-danger" onClick="deleteMultiple()"><i
                                                    class="ri-delete-bin-2-line"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input type="text" class="form-control search" placeholder="Search...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap" id="customerTable">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                        </div>
                                    </th>
                                    <th class="" data-sort="customer_name">Mô tả</th>
                                    <th class="" data-sort="email">Trạng thái</th>
                                    <th class="" data-sort="phone">Số lượng</th>
                                    <th class="" data-sort="date">Loại giảm giá</th>
                                    <th class="" data-sort="status">Giá trị giảm giá</th>
                                    <th class="" data-sort="action">Ngày bắt đầu</th>
                                    <th class="" data-sort="action">Ngày kết thúc</th>
                                    <th class="" data-sort="action">Giá thấp nhất</th>
                                    <th class="" data-sort="action">Giá cao nhất</th>
                                    <th class="" data-sort="action">Rank</th>
                                    <th class="" data-sort="action">Điều kiện rank</th>
                                    <th class="" data-sort="action">Conditional_total_amount</th>
                                    <th class="" data-sort="action">Hành động</th>

                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @foreach($trashedVouchers as $voucher)
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="chk_child" value="option1">
                                            </div>
                                        </th>

                                        <td class="customer_name">{{$voucher->description}}</td>
                                        @if($voucher->status==1)
                                            <td class="status"><span class="badge bg-success-subtle text-success text-uppercase">Active</span></td>
                                        @else
                                            <td class="status"><span class="badge bg-danger-subtle text-danger text-uppercase">Inactive</span></td>
                                        @endif
                                        <td class="phone">{{$voucher->quantity}}</td>
                                        @if($voucher->discount_type)
                                            <td class="date">Phần trăm</td>
                                            <td class="date">{{$voucher->discount_value}}%</td>
                                        @else
                                            <td class="date">Tiền</td>
                                            <td class="date">{{number_format($voucher->discount_value)}}đ</td>
                                        @endif
                                        <td class="date">{{date('d-M-y', strtotime($voucher->start_date))}}</td>
                                        <td class="date">{{date('d-M-y', strtotime($voucher->end_date))}}</td>
                                        <td class="date">{{$voucher->min_price}}</td>
                                        <td class="date">{{$voucher->max_price}}</td>
                                        <td class="date">{{$voucher->rank_id}}</td>
                                        <td class="date">{{$voucher->conditional_rank}}</td>
                                        <td></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <div class="edit">
                                                    <form action="{{route('vouchers.restore',$voucher->id)}}" method="post">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger remove-item-btn" data-bs-toggle="modal" data-bs-target="#deleteRecordModal">Restore</button>
                                                    </form>
                                                </div>
                                                <div class="remove">
                                                    <form action="{{route('vouchers.force_delete',$voucher->id)}}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger remove-item-btn" data-bs-toggle="modal" data-bs-target="#deleteRecordModal">Force delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="noresult" style="display: none">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2">Sorry! No Result Found</h5>
                                    <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find any orders for you search.</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <div class="pagination-wrap hstack gap-2">
                                <a class="page-item pagination-prev disabled" href="javascript:void(0);">
                                    Previous
                                </a>
                                <ul class="pagination listjs-pagination mb-0"></ul>
                                <a class="page-item pagination-next" href="javascript:void(0);">
                                    Next
                                </a>
                            </div>
                        </div>
                        {{--                        <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"--}}
                        {{--                             aria-hidden="true">--}}
                        {{--                            <div class="modal-dialog modal-dialog-centered">--}}
                        {{--                                <div class="modal-content">--}}
                        {{--                                    <div class="modal-header bg-light p-3">--}}
                        {{--                                        <h5 class="modal-title" id="exampleModalLabel">Thêm voucher</h5>--}}
                        {{--                                        <button type="button" class="btn-close" data-bs-dismiss="modal"--}}
                        {{--                                                aria-label="Close" id="close-modal"></button>--}}
                        {{--                                    </div>--}}

                        {{--                                    <form class="tablelist-form" autocomplete="off"--}}
                        {{--                                          action="{{route('vouchers.store')}}" method="POST">--}}
                        {{--                                        @csrf--}}
                        {{--                                        <div class="modal-body">--}}
                        {{--                                            <div class="mb-3">--}}
                        {{--                                            <label for="discount_type" class="form-label">Loại giảm giá:</label>--}}
                        {{--                                            <select id="discount_type" name="discount_type" class="form-control">--}}
                        {{--                                                <option value="0">Tiền mặt</option>--}}
                        {{--                                                <option value="1">%</option>--}}
                        {{--                                            </select>--}}
                        {{--                                            </div>--}}
                        {{--                                            <div class="mb-3">--}}
                        {{--                                                <label for="discount_value" class="form-label">Giá trị giảm giá:</label>--}}
                        {{--                                                <input type="number" id="discount_value" name="discount_value" step="0.01" placeholder="Nhập giá trị giảm giá" class="form-control"/>--}}
                        {{--                                            </div>--}}
                        {{--                                            <div class="mb-3">--}}
                        {{--                                                <label for="quantity" class="form-label">Số lượng</label>--}}
                        {{--                                                <input type="number" id="quantity" class="form-control"--}}
                        {{--                                                       name="quantity" placeholder="Nhập số lượng"--}}
                        {{--                                                       value="{{ old('quantity') }}--}}
                        {{--                                                       "/>--}}
                        {{--                                                <div class="invalid-feedback"></div>--}}
                        {{--                                            </div>--}}

                        {{--                                            <div class="mb-3">--}}
                        {{--                                                <label for="status">Trạng thái</label>--}}
                        {{--                                                <select class="form-control" name="status" id="">--}}
                        {{--                                                    <option value="1" {{old('status')=='1'?'selected':''}}>Active</option>--}}
                        {{--                                                    <option value="0" {{old('status')=='0'?'selected':''}}>Inactive</option>--}}
                        {{--                                                </select>--}}
                        {{--                                            </div>--}}

                        {{--                                            <div class="mb-3">--}}
                        {{--                                                <label for="description"--}}
                        {{--                                                       class="form-label">Mô tả</label>--}}
                        {{--                                                <textarea name="description" class="form-control"--}}
                        {{--                                                          id="description" cols="30" rows="5"--}}
                        {{--                                                          placeholder="Nhập mô tả"--}}
                        {{--                                                          value="{{ old('description') }}"--}}
                        {{--                                                ></textarea>--}}
                        {{--                                                <div class="invalid-feedback"></div>--}}
                        {{--                                            </div>--}}
                        {{--                                            <div class="mb-3">--}}
                        {{--                                                <label for="start_date"--}}
                        {{--                                                       class="form-label">Ngày bắt đầu</label>--}}
                        {{--                                                <input type="date" class="form-control" name="start_date" value="{{old('start_date')}}">--}}
                        {{--                                                <div class="invalid-feedback"></div>--}}
                        {{--                                            </div>--}}

                        {{--                                            <div class="mb-3">--}}
                        {{--                                                <label for="end_date"--}}
                        {{--                                                       class="form-label">Ngày kết thúc</label>--}}
                        {{--                                                <input type="date" class="form-control" name="start_end" value="{{old('end_date')}}">--}}
                        {{--                                                <div class="invalid-feedback"></div>--}}
                        {{--                                            </div>--}}
                        {{--                                            <div class="mb-3">--}}
                        {{--                                                <label for="min_price"--}}
                        {{--                                                       class="form-label">Giảm thấp nhất</label>--}}
                        {{--                                                <input type="number" class="form-control"--}}
                        {{--                                                       value="{{old('min_price')}}"--}}
                        {{--                                                        name="min_price"--}}
                        {{--                                                >--}}
                        {{--                                                <div class="invalid-feedback"></div>--}}
                        {{--                                            </div>--}}
                        {{--                                            <div class="mb-3">--}}
                        {{--                                                <label for="max_price"--}}
                        {{--                                                       class="form-label">Giảm cao nhất</label>--}}
                        {{--                                                <input type="number" class="form-control"--}}
                        {{--                                                       value="{{old('max_price')}}"--}}
                        {{--                                                       name="max_price"--}}
                        {{--                                                >--}}
                        {{--                                                <div class="invalid-feedback"></div>--}}
                        {{--                                            </div>--}}
                        {{--                                            <div class="mb-3">--}}
                        {{--                                                <label for="rank"--}}
                        {{--                                                       class="form-label">Rank</label>--}}
                        {{--                                                <select class="form-control" name="rank" id="">--}}
                        {{--                                                    <option value="1">Hội viên Vip</option>--}}
                        {{--                                                    <option value="0">Phèn</option>--}}
                        {{--                                                </select>--}}
                        {{--                                                <div class="invalid-feedback"></div>--}}
                        {{--                                            </div>--}}
                        {{--                                        </div>--}}
                        {{--                                        <div class="modal-footer">--}}
                        {{--                                            <div class="hstack gap-2 justify-content-end">--}}
                        {{--                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">--}}
                        {{--                                                    Close--}}
                        {{--                                                </button>--}}
                        {{--                                                <button type="submit" class="btn btn-success" id="add-btn">Add--}}
                        {{--                                                    voucher--}}
                        {{--                                                </button>--}}
                        {{--                                            </div>--}}
                        {{--                                        </div>--}}
                        {{--                                    </form>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </div>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
@endsection