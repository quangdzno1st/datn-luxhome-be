<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use App\Http\Controllers\Admin\AmenitiesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StatisticalController;
use App\Http\Controllers\Admin\HotelServiceController;
use App\Http\Controllers\Admin\CatalogueRoomController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\RateController;

Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.login');
});

Route::group(['middleware' => ['admin']], function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
//    Route::get('/profile', [AuthController::class, 'profile'])->name('auth.profile');
//    Route::post('/auth/update', [AuthController::class, 'update'])->name('auth.update');
//    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
////    Route::get('dashboard/active-user/{user_id}', [DashboardController::class, 'show'])->name('dashboard.active-user');
////    Route::post('dashboard/export-active-user/{user_id}', [DashboardController::class, 'exportExcel'])->name('dashboard.export-active-user');
//
//    Route::controller(CategoryController::class)->group(function () {
//        Route::get('/categories/update_status/{id?}', 'update_status')->name('category.update_status');
//        Route::get('/categories/update_hot/{id?}', 'update_hot')->name('category.update_hot');
//        Route::get('/categories/update_menu_active/{id?}', 'update_menu_active')->name('category.update_menu_active');
//        Route::post('/categories/bulk-delete', [CategoryController::class, 'bulk_delete']);
//        Route::get('/categories/sort', [CategoryController::class, 'sortView'])->name('category.sort_view');
//        Route::post('/categories/sort', [CategoryController::class, 'sort'])->name('category.sort');
//    });
//    Route::resource('categories', CategoryController::class);
//
//    //user
//    Route::resource('users', UserController::class)->middleware('can:users');

    Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('can:view-users');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('can:create-users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('can:create-users');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('can:edit-users');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('can:edit-users');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('can:delete-users');


    Route::get('/permissions', [UserController::class,'permissionsList'])->name('permissions');
    Route::get('/permissions/edit/{id}', [UserController::class,'permissionsEdit'])->name('permissions.edit');
    Route::post('/permissions/update/{id}', [UserController::class,'permissionsUpdate'])->name('permissions.update');
//    Route::get('user-delete/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy']);
//
//    // thành phố
//    Route::resource('province', ProvinceController::class);
//    // Tỉnh
//    Route::resource('district', DistrictController::class);
//    // xã
//    Route::resource('ward', WardController::class);
//
//    Route::resource('business-settings', \App\Http\Controllers\Admin\BusinessSettingController::class);
//
//    Route::get('403', function () {
//        return view('admin.content.error.403');
//    })->name('403');
//
//    Route::get('404', function () {
//        return view('admin.content.error.404');
//    })->name('404');


    Route::prefix('catalogue-rooms')
        ->controller(CatalogueRoomController::class)
        ->group(function () {
            Route::get('/', 'index')->name('catalogue-rooms.index')
                ->middleware('can:view-categories');
            Route::get('/create', 'create')->name('catalogue-rooms.create')
                ->middleware('can:create-categories');
            Route::get('/{id}', 'edit')->name('catalogue-rooms.edit')
                ->middleware('can:edit-categories');;
            Route::post('/', 'store')->name('catalogue-rooms.store')
                ->middleware('can:create-categories');;
            Route::put('/{id}', 'update')->name('catalogue-rooms.update')
                ->middleware('can:edit-categories');;
            Route::put('/delete/{id}', 'delete')->name('catalogue-rooms.delete')
                ->middleware('can:delete-categories');;
            Route::put('/restore/{id}', 'restore')->name('catalogue-rooms.restore');
            Route::delete('/{id}', 'destroy')->name('catalogue-rooms.destroy')
                ->middleware('can:delete-categories');
            Route::delete('/delete/image', 'deleteImageMulti')->name('catalogue-rooms.delete.image.multi');
        });

    Route::prefix('rooms')
        ->controller(RoomController::class)
        ->group(function () {
            Route::get('/', 'index')->name('rooms.index')
                ->middleware('can:view-rooms'); // Chặn quyền xem danh sách

            Route::get('/create', 'create')->name('rooms.create')
                ->middleware('can:create-rooms'); // Chặn quyền tạo mới

            Route::get('/{id}', 'show')->name('rooms.show')
                ->middleware('can:view-rooms'); // Chặn quyền xem chi tiết

            Route::post('/', 'store')->name('rooms.store')
                ->middleware('can:create-rooms'); // Chặn quyền tạo mới

            Route::put('/{id}', 'update')->name('rooms.update')
                ->middleware('can:edit-rooms'); // Chặn quyền chỉnh sửa

            Route::put('/delete/{id}', 'delete')->name('rooms.delete')
                ->middleware('can:delete-rooms'); // Chặn quyền xóa mềm

            Route::put('/restore/{id}', 'restore')->name('rooms.restore');

            Route::delete('/{id}', 'destroy')->name('rooms.destroy')
                ->middleware('can:delete-rooms'); // Chặn quyền xóa vĩnh viễn
        });


    Route::controller(StatisticalController::class)->group(function () {
        Route::get('/', 'index')->name('statistical.index')->middleware('can:view-amenities');
        Route::post('/statistical', 'handleStatistical')->name('handle.statistical')->middleware('can:create-amenities');
    });

    Route::prefix('services')->controller(ServiceController::class)->group(function () {
        Route::get('/', 'index')->name('services.index')->middleware('can:view-services');
        Route::post('/store', 'store')->name('services.store')->middleware('can:create-services');
        Route::put('/update/{id}', 'update')->name('services.update')->middleware('can:edit-services');
        Route::delete('/{id}', 'destroy')->name('services.destroy')->middleware('can:delete-services');
    });

    Route::prefix('amenities')->controller(AmenitiesController::class)->group(function () {
        Route::get('/', 'index')->name('amenities.index')->middleware('can:view-amenities');;
        Route::post('/store', 'store')->name('amenities.store')->middleware('can:create-amenities');;
        Route::put('/update/{id}', 'update')->name('amenities.update')->middleware('can:edit-amenities');;
        Route::delete('/{id}', 'destroy')->name('amenities.destroy')->middleware('can:delete-amenities');;
    });

    Route::prefix('hotel/services')->controller(HotelServiceController::class)->group(function () {
        Route::get("/{idHotel?}", 'index')->name("hotel.service.index");
        Route::post("/{idHotel?}", 'store')->name("hotel.service.store");
        Route::get("/delete/{id}", 'destroy')->name("hotel.service.destroy");
        Route::delete("/delete", 'destroyMulti')->name("hotel.service.destroyMulti");
    });

    Route::prefix('regions')
    ->name('regions.')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\RegionController::class, 'index'])->name('index')->middleware('can:view-region');
        Route::post('/', [\App\Http\Controllers\Admin\RegionController::class, 'store'])->name('store')->middleware('can:create-region');
        Route::get('/trash', [\App\Http\Controllers\Admin\RegionController::class, 'trash'])->name('trash');
        Route::get('/show/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'update'])->name('update')->middleware('can:edit-region');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'destroy'])->name('destroy')->middleware('can:delete-region');
        Route::get('/restore/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'restore'])->name('restore');
        Route::delete('/force-delete/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'forceDelete'])->name('forceDelete');
    });

    Route::prefix('cities')
    ->as('cities.')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CityController::class, 'index'])->name('index')->middleware('can:view-city');;
        Route::post('/', [\App\Http\Controllers\Admin\CityController::class, 'store'])->name('store')->middleware('can:create-city');;
        Route::get('/trash', [\App\Http\Controllers\Admin\CityController::class, 'trash'])->name('trash');
        Route::put('/{id}', [\App\Http\Controllers\Admin\CityController::class, 'update'])->name('update')->middleware('can:edit-city');;
        Route::delete('/{id}', [\App\Http\Controllers\Admin\CityController::class, 'destroy'])->name('destroy')->middleware('can:delete-city');;
        Route::get('/restore/{id}', [\App\Http\Controllers\Admin\CityController::class, 'restore'])->name('restore');
        Route::delete('/force-delete/{id}', [\App\Http\Controllers\Admin\CityController::class, 'forceDelete'])->name('forceDelete');
    });

    Route::prefix('hotels')
    ->name('hotels.')
    ->middleware('role')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\HotelController::class, 'index'])->name('index')->middleware('can:view-hotel');
        Route::get('/create', [\App\Http\Controllers\Admin\HotelController::class, 'create'])->name('create')->middleware('can:create-hotel');
        Route::post('/', [\App\Http\Controllers\Admin\HotelController::class, 'store'])->name('store')->middleware('can:create-hotel');
        Route::get('/trash', [\App\Http\Controllers\Admin\HotelController::class, 'trash'])->name('trash');
        Route::get('/show/{id}', [\App\Http\Controllers\Admin\HotelController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [\App\Http\Controllers\Admin\HotelController::class, 'edit'])->name('edit')->middleware('can:edit-hotel');
        Route::put('/{id}', [\App\Http\Controllers\Admin\HotelController::class, 'update'])->name('update')->middleware('can:edit-hotel');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\HotelController::class, 'destroy'])->name('destroy')->middleware('can:delete-hotel');
        Route::get('/restore/{id}', [\App\Http\Controllers\Admin\HotelController::class, 'restore'])->name('restore');
        Route::delete('/force-delete/{id}', [\App\Http\Controllers\Admin\HotelController::class, 'forceDelete'])->name('forceDelete');
    });

    Route::prefix('rates')->name('rates.')->controller(RateController::class)->group(function(){
        //route của superadmin
        Route::get('/hotels', 'listRatesAllHotels')->name('hotels');
        Route::get('/hotel/{hotelId}', 'listRatesOneHotel')->name('hotel');
        Route::get('/trash/hotel/{hotelId}', 'listRatesOneHotelTrash')->name('hotel.trash');

        //route của hotelier
        Route::get('/hotelier', 'getRatesByHotelIdOfHotelier')->name('hotel.hotelier');
        Route::get('/trash/hotelier', 'getRatesByHotelIdOfHotelierTrash')->name('hotel.trash.hotelier');

        //route 2 thằng đều dùng được
        Route::post('/hidden/{rateId}', 'rateHidden')->name('hidden');
        Route::post('/restore/{rateId}', 'rateRestore')->name('restore');
        Route::delete('/destroy/{rateId}', 'rateDestroy')->name('destroy');
    });

    Route::prefix('comments')->name('comments.')->controller(CommentController::class)->group(function(){
        Route::post('/store', 'store')->name('store');
        Route::put('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });
});

//voucher
Route::prefix('admin/vouchers')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\VoucherController::class, 'index'])->name('vouchers.index')->middleware('can:view-vouchers');
    Route::get('/create', [\App\Http\Controllers\Admin\VoucherController::class, 'create'])->name('vouchers.create')->middleware('can:create-vouchers');
    Route::post('/issue-voucher', [\App\Http\Controllers\Admin\VoucherController::class, 'issueVoucher'])->name('vouchers.issue_voucher');
    Route::post('/', [\App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('vouchers.store')->middleware('can:create-vouchers');
    Route::get('/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'edit'])->name('vouchers.edit')->middleware('can:edit-vouchers');
    Route::put('/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'update'])->name('vouchers.update')->middleware('can:edit-vouchers');
    Route::delete('/delete/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'delete'])->name('vouchers.delete')->middleware('can:delete-vouchers');
    Route::get('/list-trash', [\App\Http\Controllers\Admin\VoucherController::class, 'list_trash'])->name('vouchers.list_trash');
    Route::post('/restore/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'restore'])->name('vouchers.restore');
    Route::delete('/force_delete/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'destroy'])->name('vouchers.force_delete');
});

//order
Route::prefix('admin/orders')->group(function () {
    Route::get('/page={page}', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index')->middleware('can:view-orders');
    Route::post('/checkout/{id}', [\App\Http\Controllers\Admin\OrderDetailController::class, 'updateStatus'])->name('orders.checkout')->middleware('can:edit-orders');;
    Route::get('/show/{order}', [\App\Http\Controllers\Admin\OrderDetailController::class, 'showOrderDetail'])->name('orders.show')->middleware('can:edit-orders');;
    Route::post('/checkin/{orderId}', [\App\Http\Controllers\Admin\OrderDetailController::class, 'checkinOrder'])->name('orders.checkin')->middleware('can:edit-orders');;
    Route::post('/addBookingServices/{id}', [\App\Http\Controllers\Admin\BookingServicesInOrderDetailController::class, 'addBookingServicesInOrderDetail'])->name('orders.addBookingServices')->middleware('can:edit-orders');;
    //update status payment and order
    Route::post('/not_accepted_cancel/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'not_accepted_cancel'])->name('orders.not_accepted_cancel')->middleware('can:edit-orders');;
    Route::post('/accepted_cancel/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'accepted_cancel'])->name('orders.accepted_cancel')->middleware('can:edit-orders');;
});