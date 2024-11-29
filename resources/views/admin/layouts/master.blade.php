<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('theme/admin/assets/images/favicon.ico') }}">

    @yield('style-libs')

    <!-- Layout config Js -->
    <script src="{{ asset('theme/admin/assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('theme/admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('theme/admin/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    @yield('styles')

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('admin.layouts.header')

        <!-- ========== App Menu ========== -->
        @include('admin.layouts.sidebar')
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    @yield('content')
                    
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            @include('admin.layouts.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <script>
        const PATH_ROOT = '{{ asset('theme/admin') }}';
    </script>

    <!-- JAVASCRIPT -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="{{ asset('theme/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/js/plugins.js') }}"></script>

    @yield('script-libs')

    <!-- App js -->
    <script src="{{ asset('theme/admin/assets/js/app.js') }}"></script>

    @yield('scripts')
</body>
<script>
    function showToast(message, bgc) {
        console.log(bgc)

        Toastify({
            text: message, // Nội dung thông báo
            gravity: "top", // Vị trí: top/bottom
            position: "right", // Vị trí cụ thể: left/center/right
            duration: 5000, // Thời gian hiển thị (ms)
            close: true, // Hiển thị nút đóng
            style: {
                background: bgc, // Màu xanh lục giống hình
                color: "#fff", // Màu chữ trắng
                borderRadius: "5px", // Tùy chỉnh bo góc
                padding: "10px 20px", // Khoảng cách trong thông báo
            },
        }).showToast();
    }

    @if(session('success'))
    document.addEventListener('DOMContentLoaded', function () {
        showToast("{{ session('success') }}", '#20c997');
    });
    @elseif(session('error'))
    document.addEventListener('DOMContentLoaded', function () {
        showToast("{{ session('error') }}", '#ff6b6b');
    });
    @endif
</script>
</html>
