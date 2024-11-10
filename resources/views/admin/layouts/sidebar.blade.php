<!-- removeNotificationModal -->
<div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#f7b84b,secondary:#f06548"
                        style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete
                        It!</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="#" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{asset('theme/admin/assets/images/logo-sm.png')}}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{asset('theme/admin/assets/images/logo-dark.png')}}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="#" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{asset('theme/admin/assets/images/logo-sm.png')}}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{asset('theme/admin/assets/images/logo-light.png')}}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Mục lục</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Tổng Quan</span>
                    </a>
                </li> <!-- end Dashboard Menu -->
               

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#">
                        <i class="ri-layout-3-line"></i> <span data-key="t-layouts">Danh Mục</span>
                    </a>
                </li> <!-- end Dashboard Menu -->


                <li class="nav-item">
{{--                    <a class="nav-link menu-link" href="#sidebarUsers" data-bs-toggle="collapse"--}}
{{--                        role="button" aria-expanded="false" aria-controls="sidebarUsers">--}}
{{--                        <i class="ri-account-circle-line"></i> <span data-key="t-layouts">Người Dùng</span>--}}
{{--                    </a>--}}
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="#sidebarUsers" data-bs-toggle="collapse"
                       role="button" aria-expanded="false" aria-controls="sidebarUsers">
                        <i class="ri-account-circle-line"></i> <span data-key="t-layouts">Người Dùng</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('admin.users.*') ? 'show' : '' }}" id="sidebarUsers">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.  users.index') ? 'active' : '' }}"
                                   data-key="t-horizontal">Danh Sách</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.users.create') }}" class="nav-link {{ request()->routeIs('admin.  users.create') ? 'active' : '' }}"
                                   data-key="t-detached">Thêm Mới</a>
                            </li>
                        </ul>
                    </div>
                </li>
                </li> <!-- end Dashboard Menu -->

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarCatalogueRoom" data-bs-toggle="collapse"
                       role="button" aria-expanded="false" aria-controls="sidebarCatalogueRoom">
                        <i class="ri-account-circle-line"></i> <span data-key="t-layouts">Loại phòng</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarCatalogueRoom">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#" target="_blank" class="nav-link"
                                   data-key="t-horizontal">Danh Sách</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" target="_blank" class="nav-link"
                                   data-key="t-detached">Thêm Mới</a>
                            </li>

                        </ul>
                    </div>
                </li> <!-- end Catalogue Room Menu -->

{{--                voucher--}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarVoucher" data-bs-toggle="collapse"
                       role="button" aria-expanded="false" aria-controls="sidebarCatalogueRoom">
                        <i class="ri-account-circle-line"></i> <span data-key="t-layouts">Voucher</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarVoucher">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('vouchers.index')}}" target="_self" class="nav-link"
                                   data-key="t-horizontal">Danh sách</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('vouchers.create')}}" target="_self" class="nav-link"
                                   data-key="t-horizontal">Thêm voucher</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('vouchers.list_trash')}}" target="_self" class="nav-link"
                                   data-key="t-horizontal">Danh sách xóa voucher</a>
                            </li>
                        </ul>
                    </div>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarHotels" data-bs-toggle="collapse"
                       role="button" aria-expanded="false" aria-controls="sidebarHotels">
                        <i class="ri-hotel-line"></i> <span data-key="t-layouts">Khách sạn</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarHotels">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#"  class="nav-link"
                                   data-key="t-horizontal">Danh Sách</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link"
                                   data-key="t-detached">Thêm Mới</a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('admin.rooms.index')}}" data-bs-toggle=""
                       role="button" aria-expanded="false" aria-controls="sidebarCatalogueRoom">
                        <i class="ri-account-circle-line"></i> <span data-key="t-layouts">Phòng</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarHotel" data-bs-toggle="collapse"
                       role="button" aria-expanded="false" aria-controls="sidebarHotel">
                        <i class="ri-hotel-line"></i> <span data-key="t-layouts">Khách sạn</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarHotel">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('admin.hotels.index')}}" target="_self" class="nav-link"
                                   data-key="t-horizontal">Danh sách</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.hotels.create')}}" target="_self" class="nav-link"
                                   data-key="t-horizontal">Thêm mới</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('admin.regions.index')}}" data-bs-toggle=""
                       role="button" aria-expanded="false" aria-controls="sidebarRegion">
                        <i class="ri-pin-distance-fill"></i> <span data-key="t-layouts">Miền</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('admin.cities.index')}}" data-bs-toggle=""
                       role="button" aria-expanded="false" aria-controls="sidebarRegion">
                        <i class="ri-building-4-fill"></i> <span data-key="t-layouts">Thành phố</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>