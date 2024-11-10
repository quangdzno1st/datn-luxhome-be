@extends('admin.layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Thêm voucher</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-auto">
                                <div class="col-sm-auto">
                                    <div>
                                        <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"
                                                id="create-btn" data-bs-target="#showModal">Danh sách
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form class="tablelist-form" autocomplete="off"
                              action="{{route('vouchers.store')}}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="discount_type" class="form-label">Loại giảm giá:</label>
                                    <select id="discount_type" name="discount_type" class="form-control">
                                        <option value="0">Tiền mặt</option>
                                        <option value="1">%</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="discount_value" class="form-label">Giá trị giảm giá:</label>
                                    <input type="number" id="discount_value" name="discount_value" step="0.01" placeholder="Nhập giá trị giảm giá" class="form-control"/>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Số lượng</label>
                                    <input type="number" id="quantity" class="form-control"
                                           name="quantity" placeholder="Nhập số lượng"
                                           value="{{ old('quantity') }}
                                                       "/>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="status">Trạng thái</label>
                                    <select class="form-control" name="status" id="">
                                        <option value="1" {{old('status')=='1'?'selected':''}}>Active</option>
                                        <option value="0" {{old('status')=='0'?'selected':''}}>Inactive</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="description"
                                           class="form-label">Mô tả</label>
                                    <textarea name="description" class="form-control"
                                              id="description" cols="30" rows="5"
                                              placeholder="Nhập mô tả"
                                              value="{{ old('description') }}"
                                    ></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="start_date"
                                           class="form-label">Ngày bắt đầu</label>
                                    <input type="date" class="form-control" name="start_date" value="{{old('start_date')}}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="end_date"
                                           class="form-label">Ngày kết thúc</label>
                                    <input type="date" class="form-control" name="end_date" value="{{old('end_date')}}">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="min_price"
                                           class="form-label">Giảm thấp nhất</label>
                                    <input type="number" class="form-control"
                                           value="{{old('min_price')}}"
                                           name="min_price"
                                    >
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="max_price"
                                           class="form-label">Giảm cao nhất</label>
                                    <input type="number" class="form-control"
                                           value="{{old('max_price')}}"
                                           name="max_price"
                                    >
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="rank"
                                           class="form-label">Rank</label>
                                    <select class="form-control" name="rank" id="">
                                        <option value="1">Hội viên Vip</option>
                                        <option value="0">Phèn</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-success" id="add-btn">Add
                                        voucher
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

            if (this.value==1) {
                discountValueInput.setAttribute('max', 100);
                discountValueInput.setAttribute('placeholder', 'Nhập % giảm giá (tối đa 100)');
            } else {
                discountValueInput.removeAttribute('max');
                discountValueInput.setAttribute('placeholder', 'Nhập giá trị giảm tiền');
            }
        });
        document.getElementById('discount_value').addEventListener('input', function() {
            const discountType = document.getElementById('discount_type').value;

            if (discountType==1 && this.value > 100) {
                this.value = 100;
            }
        });
    </script>
@endsection