<?php

use App\Http\Controllers\Admin\CatalogueRoomController;
use App\Http\Controllers\Admin\HotelServiceController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StatisticalController;
use App\Http\Controllers\Client\AccountSettingController;
use App\Http\Controllers\Client\CityController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\HotelController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    require 'admin.php';
});

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/search', [\App\Http\Controllers\Client\HotelController::class, 'search'])->name('home.search');
Route::get('/hotel/{hotel_id}', [\App\Http\Controllers\Client\HotelController::class, 'show'])->name('hotel.show');
Route::post('/ok', function (\Illuminate\Http\Request $request){
    dd($request->except('_token'));
})->name('ok');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::prefix('services')->controller(ServiceController::class)->group(function () {
        Route::get('/', 'index')->name('services.index');
        Route::post('/store', 'store')->name('services.store');
        Route::put('/update/{id}', 'update')->name('services.update');
        Route::delete('/{id}', 'destroy')->name('services.destroy');
    });

    Route::prefix('hotels')
        ->name('hotels.')
        ->middleware('role')
        ->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\HotelController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\HotelController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\HotelController::class, 'store'])->name('store');
            Route::get('/trash', [\App\Http\Controllers\Admin\HotelController::class, 'trash'])->name('trash');
            Route::get('/show/{id}', [\App\Http\Controllers\Admin\HotelController::class, 'show'])->name('show');
            Route::get('/edit/{id}', [\App\Http\Controllers\Admin\HotelController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\HotelController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\HotelController::class, 'destroy'])->name('destroy');
            Route::get('/restore/{id}', [\App\Http\Controllers\Admin\HotelController::class, 'restore'])->name('restore');
            Route::delete('/force-delete/{id}', [\App\Http\Controllers\Admin\HotelController::class, 'forceDelete'])->name('forceDelete');
        });

    Route::prefix('regions')
        ->name('regions.')
        ->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\RegionController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\RegionController::class, 'store'])->name('store');
            Route::get('/trash', [\App\Http\Controllers\Admin\RegionController::class, 'trash'])->name('trash');
            Route::get('/show/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'show'])->name('show');
            Route::put('/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'destroy'])->name('destroy');
            Route::get('/restore/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'restore'])->name('restore');
            Route::delete('/force-delete/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'forceDelete'])->name('forceDelete');
        });
    Route::prefix('cities')
        ->as('cities.')
        ->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\CityController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\CityController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\CityController::class, 'store'])->name('store');
            Route::get('/trash', [\App\Http\Controllers\Admin\CityController::class, 'trash'])->name('trash');
            Route::get('/show/{id}', [\App\Http\Controllers\Admin\CityController::class, 'show'])->name('show');
            Route::put('/{id}', [\App\Http\Controllers\Admin\CityController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\CityController::class, 'destroy'])->name('destroy');
            Route::get('/restore/{id}', [\App\Http\Controllers\Admin\CityController::class, 'restore'])->name('restore');
            Route::delete('/force-delete/{id}', [\App\Http\Controllers\Admin\CityController::class, 'forceDelete'])->name('forceDelete');
        });
});

Route::post('/upload-image', [CatalogueRoomController::class, 'storeImage'])->name('upload-image');

//});
Route::prefix('hotel/services')->controller(HotelServiceController::class)->group(function () {
    Route::get("/{idHotel}", 'index')->name("hotel.service.index");
    Route::post("/{idHotel}", 'store')->name("hotel.service.store");
    Route::get("/delete/{id}", 'destroy')->name("hotel.service.destroy");
    Route::delete("/delete", 'destroyMulti')->name("hotel.service.destroyMulti");
});
//voucher
Route::prefix('vouchers')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('/create', [\App\Http\Controllers\Admin\VoucherController::class, 'create'])->name('vouchers.create');
    Route::post('/', [\App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('vouchers.store');
    Route::get('/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'edit'])->name('vouchers.edit');
    Route::put('/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'update'])->name('vouchers.update');
    Route::delete('/delete/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'delete'])->name('vouchers.delete');
    Route::get('/list-trash', [\App\Http\Controllers\Admin\VoucherController::class, 'list_trash'])->name('vouchers.list_trash');
    Route::post('/restore/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'restore'])->name('vouchers.restore');
    Route::delete('/force_delete/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'destroy'])->name('vouchers.force_delete');
});

Route::controller(StatisticalController::class)->group(function () {
    Route::get('/statistical', 'index')->name('statistical.index');
    Route::post('/statistical', 'handleStatistical')->name('handle.statistical');
});

Route::get('/404', function () {
    return view('admin.errors.404');
})->name('error.404');
////rate
//Route::prefix('rates')->group(function () {
//    Route::get('/', [\App\Http\Controllers\Admin\RateController::class, 'index'])->name('rates.index');
//    Route::get('/', [\App\Http\Controllers\Admin\RateController::class, 'create'])->name('rates.create');
//    Route::post('/', [\App\Http\Controllers\Admin\RateController::class, 'store'])->name('rates.store');
//    Route::put('/{id}', [\App\Http\Controllers\Admin\RateController::class, 'update'])->name('rates.update');
//    Route::get('/{id}', [\App\Http\Controllers\Admin\RateController::class, 'show'])->name('rates.show');
//    Route::delete('/delete/{id}', [\App\Http\Controllers\Admin\RateController::class, 'delete'])->name('rates.delete');
//    Route::post('/restore/{id}', [\App\Http\Controllers\Admin\RateController::class, 'restore']);
//    Route::delete('/{id}', [\App\Http\Controllers\Admin\RateController::class, 'destroy']);
//});
//Route::middleware(['auth', 'admin'])->group(function () {
//    Route::get('/admin/rates', [RateController::class, 'index'])->name('rates.index');
//    Route::post('/admin/rates/{rate}/status', [RateController::class, 'updateStatus'])->name('rates.updateStatus');
//    Route::delete('/admin/rates/{rate}', [RateController::class, 'destroy'])->name('rates.destroy');
//});
Route::prefix('rates')->group(function () {
    Route::get('/admin/rates', [\App\Http\Controllers\Admin\RateController::class, 'index'])->name('rates.index');
    Route::post('/admin/rates/{rate}/status', [\App\Http\Controllers\Admin\RateController::class, 'updateStatus'])->name('rates.updateStatus');
    Route::delete('/admin/rates/{rate}', [\App\Http\Controllers\Admin\RateController::class, 'destroy'])->name('rates.destroy');
});

//order
Route::prefix('admin/orders')->group(function () {
    Route::get('/page={page}', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/check-payable/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'checkPayable'])->name('orders.checkPayable');
});

Route::get('/test/theme', function () {
    return view('client.booking');
});


//client
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/hotel/{id}', [HomeController::class, 'searchByPage'])->name('home.hotel.detail');
Route::get('/hotel/booking/{id}', [HotelController::class, 'booking'])->name('hotel.booking');
Route::get('/orders', [AccountSettingController::class, 'index'])->name('orders.index');
Route::get('/payment-order/{id}', [AccountSettingController::class, 'paymentOrder'])->name('orders.payment');
Route::get('/payment-return', [AccountSettingController::class, 'paymentReturn'])->name('orders.paymentReturn');
Route::get('/cities/search-by-page', [CityController::class, 'searchByPage'])->name('cities.searchByPage');
