@extends('admin.layouts.master')
@section('title')
    Danh sách mã giảm giá
@endsection

@section('content')
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
                {{-- <div class="card-header">
                    <h4 class="card-title mb-0">Danh sách phiếu giảm giá</h4>
                </div><!-- end card header --> --}}

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row align-items-center g-3">
                            <!-- Nút Thêm voucher -->
                            <div class="col-auto">
                                <div class="">
{{--                                    <label for="code"></label>--}}
                                </div>
                                @if(\Illuminate\Support\Facades\Auth::user()->type!=\App\Models\User::STAFF)
                                <a href="{{ route('admin.vouchers.create') }}">
                                    <button type="button" class="btn btn-success add-btn" id="create-btn">
                                        <i class="ri-add-line align-bottom me-1"></i> Thêm phiếu giảm giá
                                    </button>
                                </a>
                                @endif
                            </div>

                            <!-- Form Tìm kiếm -->
                            <div class="col">
                                <form action="" method="GET" class="d-flex align-items-center gap-2">
                                    @if(\Illuminate\Support\Facades\Auth::user()->type==\App\Models\User::ADMIN)
                                        <div class="mb-0">
                                            <select name="hotel" class="form-select">
                                                <option value="">Chọn khách sạn</option>
                                                @foreach($hotels as $hotel)
                                                    <option value="{{$hotel->id}}" {{ request('hotel') == $hotel->id ? 'selected' : '' }}>{{$hotel->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="form-group mb-0">
                                        <div class="">
{{--                                        <label for="code">Nhập mã giảm giá:</label>--}}
                                    </div>
                                    <input
                                            type="text"
                                            name="code"
                                            class="form-control"
                                            placeholder="Nhập mã giảm giá..."
                                            value="{{ old('code') }}">
                                    </div>
                                        <div class="form-group mb-0">
                                            <div class="">
{{--                                                <label for="code"></label>--}}
                                            </div>
                                            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                                        </div>
                                </form>
                            </div>

                            <!-- Nút Phát voucher -->
                            <div class="col-auto">
                                <div class="">
{{--                                    <label for="code"></label>--}}
                                </div>
                                <button type="button" class="btn btn-success add-btn" id="issue-btn" data-bs-toggle="modal" data-bs-target="#showModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Phát phiếu giảm giá
                                </button>
                            </div>
                        </div>


                        <div class="table-responsive table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap" id="customerTable">
                                <thead class="table-light">
                                <tr>
                                    <th class="" data-sort="customer_name">
{{--                                        <div class="form-check">--}}
{{--                                            <input class="form-check-input" type="checkbox" name="" value="">--}}
{{--                                        </div>--}}
                                    </th>
                                    <th class="" data-sort="customer_name">Mã giảm giá</th>
                                    <th class="" data-sort="customer_name">Thumbnail</th>
                                    <th class="" data-sort="customer_name">Mô tả</th>
                                    <th class="" data-sort="email">Trạng thái</th>
                                    <th class="" data-sort="phone">Số lượng</th>
                                    <th class="" data-sort="status">Giá trị giảm giá</th>
                                    <th class="" data-sort="action">Ngày bắt đầu</th>
                                    <th class="" data-sort="action">Ngày kết thúc</th>
                                    @if(\Illuminate\Support\Facades\Auth::user()->type != \App\Models\User::STAFF)
                                    <th class="" data-sort="action">Hành động</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @foreach($vouchers as $voucher)
                                    <tr>
                                        <td scope="row">
                                            @if((is_null($voucher['start_date']) || is_null($voucher['end_date']) ||
                                                \Carbon\Carbon::parse($voucher['end_date'])->format('Y-m-d') >= \Carbon\Carbon::today()->format('Y-m-d')) &&
                                                \App\Constant\Enum\ActiveStatusEnum::isActive($voucher['status']))
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="chk_child"
                                                           value="{{ $voucher['code'] }}">
                                                </div>
                                            @endif
                                        </td>
                                        <td class="date">{{ $voucher['code'] }}</td>
                                        <td class="date">
                                            <img width="90px" height="90px"
                                                 src="{{ Storage::url( $voucher['thumbnail']) }}"
                                                 alt="Image">
                                        </td>
                                        <td class="customer_name">{{$voucher->description}}</td>
                                        @if($voucher->status==1)
                                            <td class="status"><span
                                                        class="badge bg-success-subtle text-success text-uppercase">Hoạt động</span>
                                            </td>
                                        @else
                                            <td class="status"><span
                                                        class="badge bg-danger-subtle text-danger text-uppercase">Không hoạt động</span>
                                            </td>
                                        @endif
                                        <td class="number">{{ $voucher['quantity'] }}</td>
                                        <td class="number">{{ $voucher['discount_type'] == 1 ? $voucher['discount_value'] . "%" : number_format($voucher['discount_value']) ."VND"}}</td>
                                        <td class="date">{{$voucher['start_date'] ? \Carbon\Carbon::parse($voucher->start_date)->format('d-m-Y') : ''}}</td>
                                        <td class="date">{{$voucher['end_date'] ? \Carbon\Carbon::parse($voucher->end_date )->format('d-m-Y') : ''}}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <div class="edit">
                                                    @if(\Illuminate\Support\Facades\Auth::user()->type != \App\Models\User::STAFF)
                                                    <a href="{{route('admin.vouchers.edit',$voucher->id)}}">
                                                        <button class="btn btn-sm btn-success edit-item-btn"
                                                                data-bs-toggle="modal" data-bs-target="">Sửa
                                                        </button>
                                                    </a>
                                                    @endif
                                                </div>
{{--                                                <div class="remove">--}}
{{--                                                    @if(\Illuminate\Support\Facades\Auth::user()->type != \App\Models\User::STAFF)--}}
{{--                                                        <button class="btn btn-sm btn-soft-danger remove-item-btn"--}}
{{--                                                                data-bs-toggle="modal"--}}
{{--                                                                data-bs-target="#deleteRecordModal{{ $voucher->id }}"><i--}}
{{--                                                                    class="ri-delete-bin-2-line"></i>--}}
{{--                                                        </button>--}}
{{--                                                    @endif--}}
{{--                                                </div>--}}

                                                <!-- Modal -->
                                                <div class="modal fade zoomIn" id="deleteRecordModal{{ $voucher->id }}"
                                                     tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close" id="btn-close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mt-2 text-center">
                                                                    <lord-icon
                                                                            src="https://cdn.lordicon.com/gsqxdxog.json"
                                                                            trigger="loop"
                                                                            colors="primary:#f7b84b,secondary:#f06548"
                                                                            style="width:100px;height:100px"></lord-icon>
                                                                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                                                        <h4>Bạn chắc chắn ?</h4>
                                                                        <p class="text-muted mx-4 mb-0">Bạn có chắc muốn
                                                                            xóa mã
                                                                            này ?</p>
                                                                    </div>
                                                                </div>
{{--                                                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">--}}
{{--                                                                    <form--}}
{{--                                                                            action="{{ route('admin.vouchers.delete', $voucher['id']) }}"--}}
{{--                                                                            method="post">--}}
{{--                                                                        @csrf--}}
{{--                                                                        @method('DELETE')--}}
{{--                                                                        <button type="button" type="button"--}}
{{--                                                                                class="btn w-sm btn-light"--}}
{{--                                                                                data-bs-dismiss="modal">Đóng--}}
{{--                                                                        </button>--}}
{{--                                                                        <button type="submit"--}}
{{--                                                                                class="btn w-sm btn-danger "--}}
{{--                                                                                id="delete-record">Chắc chắn!--}}
{{--                                                                        </button>--}}
{{--                                                                    </form>--}}
{{--                                                                </div>--}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end modal -->
                                            </div>
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
                                    <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find any
                                        orders for you search.</p>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-light p-3">
                                        <h5 class="modal-title" id="exampleModalLabel">Phát voucher cho người dùng</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close" id="close-modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="mb-3">
                                                <label for="discount_type" class="form-label">Theo xếp hạng:</label>
                                                <select id="discount_type" name="rank" class="form-control">
                                                    <option value="-1">Vui lòng chọn</option>
                                                    @foreach (\App\Constant\Enum\UserRankEnum::cases() as $rank)
                                                        <option value="{{ $rank->value }}">
                                                            {{ $rank->getRankName() }} (Tiền đã chi từ {{ number_format($rank->getRequiredMoney()) }} VND)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="cleave-numeral" class="form-label">Tổng chi tiêu
                                                        từ (VND):</label>
                                                    <input type="text" class="form-control"
                                                           name="total_amount_ordered_from"
                                                           placeholder="Tổng chi tiêu từ" id="cleave-numeral-from" min="0">
                                                    @error('total_amount_ordered_from')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="cleave-numeral" class="form-label">Tổng chi tiêu
                                                        đến (VND):</label>
                                                    <input type="text" class="form-control"
                                                           name="total_amount_ordered_to"
                                                           placeholder="Tổng chi tiêu đến" id="cleave-numeral-to" min="0">
                                                    @error('total_amount_ordered_to')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng
                                            </button>
                                            <button type="submit" class="btn btn-success" id="issue-voucher-btn">
                                                Phát phiếu giảm giá
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $vouchers->appends(request()->query())->links() }}
                    </div>
                    
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
@endsection


@section('script-libs')
    <!-- cleave.js -->
    <script src="{{ asset('theme/admin/assets/libs/cleave.js/cleave.min.js') }}"></script>
    <!-- form masks init -->
    <script src="{{ asset('theme/admin/assets/js/pages/form-masks.init.js') }}"></script>

    <script>
        new Cleave('#cleave-numeral-from', {
            numeral: true, // Kích hoạt chế độ số
            numeralThousandsGroupStyle: 'thousand' // Định dạng phân cách nghìn
        });

        new Cleave('#cleave-numeral-to', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });


        // Chọn tất cả các checkbox
        const checkboxes = document.querySelectorAll('input[name="chk_child"]');
        const issueBtn = document.getElementById('issue-btn');

        // Hàm kiểm tra trạng thái checkbox
        function updateButtonState() {
            const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked); // Kiểm tra nếu có checkbox nào được chọn
            issueBtn.disabled = !isChecked; // Nếu không có checkbox nào được chọn, disable nút
        }

        // Lắng nghe sự kiện thay đổi trên tất cả checkbox
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateButtonState);
        });

        // Khởi tạo trạng thái nút khi tải trang
        updateButtonState();

        document.addEventListener('DOMContentLoaded', function () {
            const issueVoucherBtn = document.getElementById('issue-voucher-btn');
            const discountTypeSelect = document.getElementById('discount_type');
            const totalAmountFrom = document.getElementById('cleave-numeral-from');
            const totalAmountTo = document.getElementById('cleave-numeral-to');
            const checkboxes = document.querySelectorAll('input[type="checkbox"]'); // Giả sử bạn có checkboxs để chọn voucher

            // Hàm kiểm tra xem các điều kiện có thỏa mãn không
            function checkFormValidity() {
                const isRankSelected = discountTypeSelect.value >= "0"; // Kiểm tra xếp hạng có được chọn
                const isTotalAmountFromFilled = totalAmountFrom.value.trim() !== ""; // Kiểm tra "Tổng chi tiêu từ"
                const isTotalAmountToFilled = totalAmountTo.value.trim() !== ""; // Kiểm tra "Tổng chi tiêu đến"
                const isVoucherSelected = Array.from(checkboxes).some(checkbox => checkbox.checked); // Kiểm tra nếu ít nhất 1 checkbox được chọn

                console.log(discountTypeSelect.value, isRankSelected)
                // Kiểm tra xem các điều kiện có thỏa mãn
                if ((isRankSelected || isTotalAmountFromFilled || isTotalAmountToFilled) && isVoucherSelected) {
                    issueVoucherBtn.disabled = false; // Bật nút phát voucher
                } else {
                    issueVoucherBtn.disabled = true; // Tắt nút phát voucher
                }
            }

            // Lắng nghe sự thay đổi trên các trường nhập liệu
            discountTypeSelect.addEventListener('change', checkFormValidity);
            totalAmountFrom.addEventListener('input', checkFormValidity);
            totalAmountTo.addEventListener('input', checkFormValidity);
            Array.from(checkboxes).forEach(checkbox => {
                checkbox.addEventListener('change', checkFormValidity);
            });

            // Kiểm tra khi tải trang để đảm bảo trạng thái nút đúng
            checkFormValidity();

            // Thực hiện gửi yêu cầu khi nhấn nút phát voucher
            issueVoucherBtn.addEventListener('click', function () {
                const selectedRank = discountTypeSelect.value; // Lấy giá trị rank
                const totalAmountFrom = document.getElementById('cleave-numeral-from').value; // Lấy tổng chi tiêu từ
                const totalAmountTo = document.getElementById('cleave-numeral-to').value; // Lấy tổng chi tiêu đến
                const selectedVouchers = Array.from(checkboxes)
                    .filter(checkbox => checkbox.checked) // Lọc các checkbox được chọn
                    .map(checkbox => checkbox.value); // Lấy giá trị của checkbox

                console.log(selectedVouchers)
                $.ajax({
                    url: '{{ route('admin.vouchers.issue_voucher') }}',
                    method: 'POST', // Sử dụng phương thức POST thay vì GET để gửi dữ liệu
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF Token
                    },
                    data: {
                        vouchers: selectedVouchers, // Gửi dữ liệu voucher
                        rank: selectedRank,
                        total_amount_ordered_from: totalAmountFrom,
                        total_amount_ordered_to: totalAmountTo
                    },
                    success: function (response) {
                        alert(response.message);
                        console.log(response.message)
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.log(JSON.parse(xhr.responseText).message)

                        alert(decodeURIComponent(JSON.parse(xhr.responseText).message));
                    }
                });
            });
        });

    </script>
@endsection

