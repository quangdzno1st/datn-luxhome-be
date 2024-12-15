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
                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete
                        It!
                    </button>
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
                <img src="{{ asset('theme/admin/assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('theme/admin/assets/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="#" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('theme/admin/assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('theme/admin/assets/images/logo.png') }}" alt="" width="100px">
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
                @can('view_overview')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.statistical.index') ? 'active' : '' }}"
                            href="{{ route('admin.statistical.index') }}">
                            <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Tổng Quan</span>
                        </a>
                    </li> <!-- end Dashboard Menu -->
                @endcan

                @can('view_region')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.regions.index') ? 'active' : '' }}"
                            href="{{ route('admin.regions.index') }}" data-bs-toggle="" role="button" aria-expanded="false"
                            aria-controls="sidebarRegion">
                            <i class="ri-pin-distance-fill"></i> <span data-key="t-layouts">Miền</span>
                        </a>
                    </li>
                @endcan

                @can('view_city')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}"
                            href="{{ route('admin.cities.index') }}" data-bs-toggle="" role="button" aria-expanded="false"
                            aria-controls="sidebarRegion">
                            <i class="bx bxs-city"></i> <span data-key="t-layouts">Thành phố</span>
                        </a>
                    </li>
                @endcan

                @can('view_users')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                            href="#sidebarUsers" data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarUsers">
                            <i class="ri-account-circle-line"></i> <span data-key="t-layouts">Người Dùng</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->routeIs('admin.users.*') ? 'show' : '' }}"
                            id="sidebarUsers">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}"
                                        data-key="t-horizontal">Danh Sách</a>
                                </li>
                                @can('create_users')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.users.create') }}"
                                            class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}"
                                            data-key="t-detached">Thêm Mới</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
 @can('view_banners')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}"
                        href="{{ route('admin.banners.index') }}">
                        <i class="ri-image-fill"></i> <span data-key="t-layouts">Ảnh Banner</span>
                    </a>
                </li> <!-- end Dashboard Menu -->
@endcan
                @can('view_hotel')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}"
                            href="{{ route('admin.hotels.index') }}" data-bs-toggle="" role="button"
                            aria-expanded="false" aria-controls="sidebarRegion">
                            <i class="ri-hotel-line"></i> <span data-key="t-layouts">Khách sạn</span>
                        </a>
                    </li>
                @endcan

                @can('view_services')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.services.index') ? 'active' : '' }}"
                            href="{{ route('admin.services.index') }}">
                            <i class="ri-customer-service-line"></i> <span data-key="t-layouts">Dịch vụ</span>
                        </a>
                    </li> <!-- end Dashboard Menu -->
                @endcan

                @can('view_categories')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.catalogue-rooms.*') ? 'active' : '' }}"
                            href="{{ route('admin.catalogue-rooms.index') }}" data-bs-toggle="" role="button"
                            aria-expanded="false" aria-controls="sidebarRegion">
                            <i class="ri-layout-3-line"></i> <span data-key="t-layouts">Loại phòng</span>
                        </a>
                    </li>
                @endcan

                @can('view_amenities')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.amenities.*') ? 'active' : '' }}"
                            href="#sidebarAmenities" data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarAmenities">
                            <i class="ri-customer-service-line"></i> <span data-key="t-layouts">Tiện nghi</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->routeIs('admin.amenities.*') ? 'show' : '' }}"
                            id="sidebarAmenities">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.amenities.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.amenities.index') ? 'active' : '' }}"
                                        data-key="t-horizontal">Danh Sách</a>
                                </li>
                                {{--                            <li class="nav-item"> --}}
                                {{--                                <a href="{{ route('admin.amenities.create') }}" --}}
                                {{--                                    class="nav-link {{ request()->routeIs('admin.amenities.create') ? 'active' : '' }}" --}}
                                {{--                                    data-key="t-detached">Thêm Mới</a> --}}
                                {{--                            </li> --}}
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('view_rooms')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.rooms.index') ? 'active' : '' }}"
                            href="{{ route('admin.rooms.index') }}" data-bs-toggle="" role="button"
                            aria-expanded="false" aria-controls="sidebarCatalogueRoom">

                            <i class="ri-hotel-bed-line"></i> <span data-key="t-layouts">Phòng</span>
                        </a>
                    </li>
                @endcan

                @can('view_vouchers')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}" href="{{ route('admin.vouchers.index') }}" data-bs-toggle=""
                            role="button" aria-expanded="false" aria-controls="sidebarRegion">
                            <i class="ri-coupon-2-line"></i> <span data-key="t-layouts">Phiếu giảm giá</span>
                        </a>
                    </li>
                @endcan

                @can('view_orders')
                    <li class="nav-item">

                        <a class="nav-link menu-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                            href="{{ route('admin.orders.index') }}" data-bs-toggle="" role="button"
                            aria-expanded="false" aria-controls="sidebarCatalogueRoom">

                            <i class="ri-hotel-bed-line"></i> <span data-key="t-layouts">Đơn hàng</span>
                        </a>
                    </li>
                @endcan

                @can('view_reviews')
                    @if (Auth::user()->type == 2)
                        <li class="nav-item">

                            <a class="nav-link menu-link {{ request()->routeIs('admin.rates.*') ? 'active' : '' }}"
                                href="{{ route('admin.rates.hotels') }}" data-bs-toggle="" role="button"
                                aria-expanded="false" aria-controls="sidebarRegion">

                                <i class="ri-star-s-line"></i> <span data-key="t-layouts">Đánh giá</span>
                            </a>
                        </li>
                    @else
                        <li class="nav-item">

                            <a class="nav-link menu-link {{ request()->routeIs('admin.rates.hotel.*') ? 'active' : '' }}"
                                href="{{ route('admin.rates.hotel.hotelier') }}" data-bs-toggle="" role="button"
                                aria-expanded="false" aria-controls="sidebarRegion">

                                <i class="ri-star-s-line"></i> <span data-key="t-layouts">Đánh giá</span>
                            </a>
                        </li>
                    @endif
                @endcan

                @if (Auth::user()->type == \App\Models\User::ADMIN)
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.permissions') ? 'active' : '' }}"
                            href="{{ route('admin.permissions') }}">
                            <i class="ri-group-line"></i> <span data-key="t-layouts">Phân quyền</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
