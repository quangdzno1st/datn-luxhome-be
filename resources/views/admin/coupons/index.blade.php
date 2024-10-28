@extends('admin.layouts.master')

@section('title')
    Danh sách mã giảm giá
@endsection

@section('content')
    <div class="row">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Danh sách mã giảm giá</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Mã giảm giá</a></li>
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
                        <h4 class="card-title mb-0">Danh sách mã giảm giá</h4>
                    </div><!-- end card header -->
    
                    <div class="card-body">
                        <div class="listjs-table" id="customerList">
                            <div class="row g-4 mb-3">
                                <div class="col-sm-auto">
                                    <div>
                                        <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1"></i> Add</button>
                                        <button class="btn btn-soft-danger" onClick="deleteMultiple()"><i
                                                class="ri-delete-bin-2-line"></i></button>
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
                                                    <input class="form-check-input" type="checkbox" id="checkAll"
                                                        value="option">
                                                </div>
                                            </th>
                                            <th class="sort" data-sort="id">Coupon ID</th>
                                            <th class="sort" data-sort="customer_name">Code</th>
                                            <th class="sort" data-sort="date">Type</th>
                                            <th class="sort" data-sort="product_name">Discount</th>
                                            <th class="sort" data-sort="amount">Description</th>
                                            <th class="sort" data-sort="payment">Uses/Limit</th>
                                            <th class="sort" data-sort="status">Expiry</th>
                                            <th class="sort" data-sort="city">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @foreach ($coupons as $coupon)
                                            <tr>
                                                <th scope="row">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="chk_child"
                                                            value="option1">
                                                    </div>
                                                </th>
                                                <td class="id" style="display:none;"><a href="javascript:void(0);"
                                                        class="fw-medium link-primary"></a></td>
                                                <td class="id">{{ $coupon->id }}</td>
                                                <td class="customer_name">{{ $coupon->code }}</td>
                                                <td class="">{{ $coupon->type == 'percent' ? 'Giảm giá theo phần trăm (%)' : 'Giảm giá theo đơn giá'}}</td>
                                                <td class="">{{ $coupon->discount }}</td>
                                                <td class="">{{ $coupon->description }}</td>
                                                <td class="">{{ "$coupon->uses/$coupon->limit" }}</td>
                                                <td class="">{{ $coupon->expiry }}</td>

                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <div class="edit">
                                                            <button class="btn btn-sm btn-soft-warning edit-item-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#showModal{{ $coupon->id }}"><i
                                                                class="ri-edit-2-line"></i></button>
                                                        </div>

                                                        <div class="remove">
                                                            <button class="btn btn-sm btn-soft-danger remove-item-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteRecordModal{{ $coupon->id }}"><i
                                                                class="ri-delete-bin-2-line"></i></button>
                                                        </div>
                                                        
                                                    </div>
                                                </td>
                                            </tr>
    
                                            <div class="modal fade" id="showModal{{ $coupon->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-light p-3">
                                                            <h5 class="modal-title">Chi tiết</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close" id="close-modal"></button>
                                                        </div>
                                                        <form class="tablelist-form" autocomplete="off" action="{{route('admin.coupon.update', $coupon)}}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">       
                                                                <div class="mb-3">
                                                                    <label for="customername-field" class="form-label">Code</label>
                                                                    <input type="text" id="customername-field" class="form-control" placeholder="Enter code" required name="code" value="{{$coupon->code}}"/>
                                                                    <div class="invalid-feedback">Please enter a customer name.</div>
                                                                </div>
                                    
                                                                <div class="mb-3">
                                                                    <label for="type-field" class="form-label">Type</label>
                                                                    <select class="form-control" data-trigger name="type" id="type-field" required>
                                                                        <option value="price" @selected($coupon->type == 'price' ? 'selected' : '')>Giảm giá theo đơn giá</option>
                                                                        <option value="percent" @selected($coupon->type == 'percent' ? 'selected' : '')>Giảm giá theo phần trăm (%)</option>
                                                                    </select>
                                                                </div>
                                    
                                                                <div class="mb-3">
                                                                    <label for="phone-field" class="form-label">Discount</label>
                                                                    <input type="number" id="phone-field" class="form-control" name="discount" placeholder="Enter discount" required value="{{$coupon->discount}}"/>
                                                                    <div class="invalid-feedback">Please enter a phone.</div>
                                                                </div>
                                    
                                                                <div class="mb-3">
                                                                    <label for="description-field" class="form-label">Description</label>
                                                                    <textarea name="description" class="form-control" id="description-field" cols="30" rows="5" placeholder="Enter description">{{$coupon->description}}</textarea>
                                                                    <div class="invalid-feedback">Please select a date.</div>
                                                                </div>
                                    
                                                                <div>
                                                                    <label for="limit-field" class="form-label">Limit</label>
                                                                    <input type="number" id="limit-field" class="form-control" name="limit" placeholder="Enter limit" required value="{{$coupon->limit}}"/>
                                                                    <div class="invalid-feedback">Please enter a phone.</div>
                                                                </div>
                                    
                                                                <div>
                                                                    <label for="expiry-field" class="form-label">Expiry</label>
                                                                    <input type="date" id="expiry-field" class="form-control" name="expiry" placeholder="Enter expiry" required value="{{$coupon->expiry}}"/>
                                                                    <div class="invalid-feedback">Please enter a phone.</div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="hstack gap-2 justify-content-end">
                                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-success" id="add-btn">Update coupon</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <!-- Modal -->
                                            <div class="modal fade zoomIn" id="deleteRecordModal{{ $coupon->id }}"
                                                tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close" id="btn-close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mt-2 text-center">
                                                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json"
                                                                    trigger="loop" colors="primary:#f7b84b,secondary:#f06548"
                                                                    style="width:100px;height:100px"></lord-icon>
                                                                <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                                                    <h4>Bạn chắc chắn ?</h4>
                                                                    <p class="text-muted mx-4 mb-0">Bạn có chắc muốn xóa mã này ?</p>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                                <form
                                                                    action="{{ route('admin.coupon.destroy', $coupon) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" type="button"
                                                                        class="btn w-sm btn-light"
                                                                        data-bs-dismiss="modal">Đóng</button>
                                                                    <button type="submit" class="btn w-sm btn-danger "
                                                                        id="delete-record">Chắc chắn!</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end modal -->
                                        @endforeach
    
    
                                    </tbody>
                                </table>
                                <div class="noresult" style="display: none">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#121331,secondary:#08a88a"
                                            style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Sorry! No Result Found</h5>
                                        <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find any
                                            orders for you search.</p>
                                    </div>
                                </div>
                            </div>
    
                            <div class="d-flex justify-content-end">
                                <div class="pagination-wrap hstack gap-2">
                                    {{ $coupons->links() }}
                                </div>
                            </div>

                            <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light p-3">
                                            <h5 class="modal-title" id="exampleModalLabel">Thêm coupon</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                        </div>
                                        <form class="tablelist-form" autocomplete="off" action="{{route('admin.coupon.store')}}" method="POST">
                                            @csrf
                                            <div class="modal-body">       
                                                <div class="mb-3">
                                                    <label for="customername-field" class="form-label">Code</label>
                                                    <input type="text" id="customername-field" class="form-control" placeholder="Enter code" required name="code"/>
                                                    <div class="invalid-feedback">Please enter a customer name.</div>
                                                </div>
                    
                                                <div class="mb-3">
                                                    <label for="type-field" class="form-label">Type</label>
                                                    <select class="form-control" data-trigger name="type" id="type-field" required>
                                                        <option value="price">Giảm giá theo đơn giá</option>
                                                        <option value="percent">Giảm giá theo phần trăm (%)</option>
                                                    </select>
                                                </div>
                    
                                                <div class="mb-3">
                                                    <label for="phone-field" class="form-label">Discount</label>
                                                    <input type="number" id="phone-field" class="form-control" name="discount" placeholder="Enter discount" required />
                                                    <div class="invalid-feedback">Please enter a phone.</div>
                                                </div>
                    
                                                <div class="mb-3">
                                                    <label for="description-field" class="form-label">Description</label>
                                                    <textarea name="description" class="form-control" id="description-field" cols="30" rows="5" placeholder="Enter description"></textarea>
                                                    <div class="invalid-feedback">Please select a date.</div>
                                                </div>
                    
                                                <div>
                                                    <label for="limit-field" class="form-label">Limit</label>
                                                    <input type="number" id="limit-field" class="form-control" name="limit" placeholder="Enter limit" required />
                                                    <div class="invalid-feedback">Please enter a phone.</div>
                                                </div>
                    
                                                <div>
                                                    <label for="expiry-field" class="form-label">Expiry</label>
                                                    <input type="date" id="expiry-field" class="form-control" name="expiry" placeholder="Enter expiry" required />
                                                    <div class="invalid-feedback">Please enter a phone.</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success" id="add-btn">Add coupon</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>



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
            type="text/css" />
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
    