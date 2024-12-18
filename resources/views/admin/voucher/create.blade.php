@extends('admin.layouts.master')
@section('title')
    Thêm mới mã giảm giá
@endsection

@section('content')
<!-- start page title -->

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Thêm mã giảm giá</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('admin.vouchers.index')}}">Danh sách</a></li>
                    <li class="breadcrumb-item active">Thêm Mới</li>
                </ol>
            </div>

        </div>
    </div>
</div>

<!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                {{-- <div class="card-header">
                    <h4 class="card-title mb-0">Thêm voucher</h4>
                </div><!-- end card header --> --}}

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-auto">
                                <div class="col-sm-auto">
                                    <div>
                                        <a href="{{ route('admin.vouchers.index') }}">
                                            <button type="button" class="btn btn-primary bg-gradient waves-effect waves-primary me-2" data-bs-toggle="modal"
                                                    id="create-btn" >
                                                <i class="ri-arrow-left-s-line"></i> Danh sách
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form class="tablelist-form" autocomplete="off" enctype="multipart/form-data"
                            action="{{ route('admin.vouchers.store') }}" method="POST">
                            @csrf
                            {{-- @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif --}}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-3 col">
                                        <label class="form-label" for="thumbnail">Ảnh đại diện<span class="text-danger">*</span></label>
                                        <input class="form-control" id="thumbnail" type="file" name="thumbnail"
                                            {{ old('thumbnail') }} accept="image/png, image/gif, image/jpeg">
                                        @error('thumbnail')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col">
                                        <label for="status">Trạng thái<span class="text-danger">*</span></label>
                                        <select class="form-control" name="status" id="">
                                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Không hoạt động
                                            </option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-3 col">
                                        <label for="discount_type" class="form-label">Loại giảm giá<span class="text-danger">*</span></label>
                                        <select id="discount_type" name="discount_type" class="form-select">
                                            <option value="0">Tiền mặt</option>
                                            <option value="1">%</option>
                                        </select>
                                        @error('discount_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col">
                                        <label for="discount_value" class="form-label">Giá trị giảm giá<span class="text-danger">*</span></label>
                                        <input type="number" id="discount_value" name="discount_value" step="0.01"
                                            min="1" placeholder="Nhập giá trị giảm giá" class="form-control" />
                                        @error('discount_value')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col">
                                        <label for="quantity" class="form-label">Số lượng<span class="text-danger">*</span></label>
                                        <input type="number" id="quantity" class="form-control" name="quantity"
                                            placeholder="Nhập số lượng" min="1"
                                            value="{{ old('quantity') }}
                                                           " />
                                        @error('quantity')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>

                                    <div class="mb-3 col">
                                        <label for="max_price" class="form-label">Giá giảm tối đa<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" value="{{ old('max_price') }}"
                                            name="max_price" placeholder="Nhập giá tối đa">
                                        @error('max_price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-3 col">
                                        <label for="start_date" class="form-label">Ngày bắt đầu</label>
                                        <input type="date" class="form-control" name="start_date"
                                            value="{{ old('start_date') }}">
                                        @error('start_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>

                                    <div class="mb-3 col">
                                        <label for="end_date" class="form-label">Ngày kết thúc</label>
                                        <input type="date" class="form-control" name="end_date"
                                            value="{{ old('end_date') }}">
                                        @error('end_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- <div class="mb-3 col">
                                        <label for="condition_rank"
                                               class="form-label">Thứ hạng có thể sử dụng</label>
                                        <select name="conditional_rank" id="condition_rank" class="form-select">
                                            <option value="">Chọn thứ hạng</option>
                                            <option value="1">Người dùng hạng nhất</option>
                                            <option value="2">Người dùng hạng hai</option>
                                            <option value="3">Người dùng hạng ba</option>
                                        </select>

                                    </div> --}}
                                    <div class="mb-3 col-6">
                                        <label for="" class="form-label">Tổng tiền đơn đặt có thể sử dụng<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="conditional_total_amount"
                                            min="1" value="{{ old('conditional_total_amount') }}"
                                            placeholder="Nhập tổng tiền đơn đặt">
                                        @error('conditional_total_amount')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả<span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control" id="description" cols="30" rows="5"
                                        placeholder="Nhập mô tả" value="{{ old('description') }}"></textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>
                            <div class="modal-footer">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-success" id="add-btn">Thêm mới
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
@endsection
@section('scripts')
    <script>
        document.getElementById('discount_type').addEventListener('change', function() {
            const discountValueInput = document.getElementById('discount_value');

            if (this.value == 1) {
                discountValueInput.setAttribute('max', 100);
                discountValueInput.setAttribute('placeholder', 'Nhập % giảm giá (tối đa 100)');
            } else {
                discountValueInput.removeAttribute('max');
                discountValueInput.setAttribute('placeholder', 'Nhập giá trị giảm tiền');
            }
        });
        document.getElementById('discount_value').addEventListener('input', function() {
            const discountType = document.getElementById('discount_type').value;

            if (discountType == 1 && this.value > 100) {
                this.value = 100;
            }
        });
    </script>
@endsection
