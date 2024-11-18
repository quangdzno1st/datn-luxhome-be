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


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StatisticalController;
use App\Http\Controllers\Admin\HotelServiceController;
use App\Http\Controllers\Admin\CatalogueRoomController;

Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.login');
});

Route::group(['middleware' => ['role']], function () {
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
    Route::resource('users', UserController::class)->middleware('can:users');
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
            Route::get('/', 'index')->name('catalogue-rooms.index');
            Route::get('/create', 'create')->name('catalogue-rooms.create');
            Route::get('/{id}', 'show')->name('catalogue-rooms.show');
            Route::post('/', 'store')->name('catalogue-rooms.store');
            Route::put('/{id}', 'update')->name('catalogue-rooms.update');
            Route::put('/delete/{id}', 'delete')->name('catalogue-rooms.delete');
            Route::put('/restore/{id}', 'restore')->name('catalogue-rooms.restore');
            Route::delete('/{id}', 'destroy')->name('catalogue-rooms.destroy');
        });

    Route::prefix('rooms')
        ->controller(RoomController::class)
        ->group(function () {
            Route::get('/', 'index')->name('rooms.index');
            Route::get('/create', 'create')->name('rooms.create');
            Route::get('/{id}', 'show')->name('rooms.show');
            Route::post('/', 'store')->name('rooms.store');
            Route::put('/{id}', 'update')->name('rooms.update');
            Route::put('/delete/{id}', 'delete')->name('rooms.delete');
            Route::put('/restore/{id}', 'restore')->name('rooms.restore');
            Route::delete('/{id}', 'destroy')->name('rooms.destroy');
        });

    Route::controller(StatisticalController::class)->group(function () {
        Route::get('/', 'index')->name('statistical.index');
        Route::post('/statistical', 'handleStatistical')->name('handle.statistical');
    });

    Route::prefix('services')->controller(ServiceController::class)->group(function () {
        Route::get('/', 'index')->name('services.index');
        Route::post('/store', 'store')->name('services.store');
        Route::put('/update/{id}', 'update')->name('services.update');
        Route::delete('/{id}', 'destroy')->name('services.destroy');
    });

    Route::prefix('hotel/services')->controller(HotelServiceController::class)->group(function () {
        Route::get("/{idHotel?}", 'index')->name("hotel.service.index");
        Route::post("/{idHotel?}", 'store')->name("hotel.service.store");
        Route::get("/delete/{id}", 'destroy')->name("hotel.service.destroy");
        Route::delete("/delete", 'destroyMulti')->name("hotel.service.destroyMulti");
    });
});
//
//voucher
