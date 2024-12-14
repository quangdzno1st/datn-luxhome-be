@extends('admin.layouts.master')


@section('content')
    <div class="select-hotel-container">
        <h1>Chọn khách sạn</h1>
        <form action="{{ route('rooms.index') }}" method="get">
            <div class="form-group">
                <label for="hotel_id">Chọn khách sạn</label>
                <select name="hotel_id" id="hotel_id" class="form-control">
                    <option value="">Chọn khách sạn</option>
                    @foreach ($hotels as $hotel)
                        <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Chọn</button>
        </form>
    </div>
@endsection

@section('styles')
    <style>
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch; /* Hỗ trợ cuộn mượt trên các thiết bị di động */
        }

        #customerTable {
            width: 50%;
            table-layout: auto; /* Giúp bảng tự điều chỉnh theo nội dung */
        }

        .hotel-image-wrapper img {
            max-width: 100px; /* Giới hạn kích thước ảnh để tránh tràn bảng */
            height: auto;
            display: block;
            margin: 0 auto; /* Căn giữa ảnh trong ô */
        }

        .table th, .table td {
            white-space: nowrap; /* Giữ nội dung trong một dòng nếu có thể */
        }

        @media (max-width: 768px) {
            /* Điều chỉnh kích thước và căn lề trên màn hình nhỏ */
            .hotel-image-wrapper img {
                max-width: 70px;
            }

            .table th, .table td {
                font-size: 12px; /* Giảm kích thước chữ để hiển thị dễ đọc hơn */
            }

        }

        td {
            white-space: nowrap;
            /* Không xuống dòng */
            overflow: hidden;
            /* Ẩn phần văn bản thừa */
            text-overflow: ellipsis;
            /* Hiển thị dấu ... khi vượt quá */
            max-width: 150px;
            /* Đặt chiều rộng tối đa */

        }

        .hotel-image-wrapper img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .hotel-image-wrapper img:hover {
            transform: scale(1.05);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
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

@section('scripts')
    <script>
        function deleteMultiple() {
            let formDelMulti = document.querySelector("#deleteMulti");
            let checkboxes = formDelMulti.querySelectorAll(".service");

            // Kiểm tra xem có ít nhất một checkbox được chọn hay không
            let hasChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

            if (confirm("Bạn có muốn xóa các dịch vụ khách sạn này không?")) {
                if (!hasChecked) {
                    // Nếu chưa chọn checkbox nào, hiển thị thông báo
                    alert("Bạn cần chọn ít nhất 1 dịch vụ");
                    return;
                }
                formDelMulti.submit();
            }
        }


        document.getElementById('nameSearch').addEventListener('input', function () {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#customerTable tbody tr'); // Các hàng trong bảng

            rows.forEach(row => {
                const nameCell = row.querySelector('#searchName'); // Lấy ô Tên trong mỗi hàng
                const nameText = nameCell ? nameCell.textContent.toLowerCase() : '';

                // Kiểm tra nếu tên khách sạn có chứa từ khóa tìm kiếm
                if (nameText.includes(searchValue)) {
                    row.style.display = ''; // Hiển thị hàng
                } else {
                    row.style.display = 'none'; // Ẩn hàng nếu không khớp với từ khóa tìm kiếm
                }
            });
        });


    </script>

    <script>
        function addImageGallery() {
            let id = 'gen' + '_' + Math.random().toString(36).substring(2, 15).toLowerCase();
            let html = `
        <div class="col-md-4" id="${id}_item">
            <label for="${id}" class="form-label">Image</label>
            <div class="d-flex">
                <input type="file" class="form-control" name="images[]" id="${id}">
                <button type="button" class="btn btn-danger" onclick="removeImageGallery('${id}_item')">
                    <span class="bx bx-trash"></span>
                </button>
            </div>
        </div>
    `;

            $('#gallery_list').append(html);
        }

        function removeImageGallery(id) {
            $('#' + id).remove()
        }
    </script>

@endsection
