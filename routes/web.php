<?php

use App\Http\Controllers\Admin\HotelServiceController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\CatalogueRoomController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\StatisticalController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    require 'admin.php';
});

Route::get('/oke', function () {
    return view('admin.users.index');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::prefix('services')->controller(ServiceController::class)->group(function () {
        Route::get('/', 'index')->name('services.index');
        Route::post('/store', 'store')->name('services.store');
        Route::put('/update/{id}', 'update')->name('services.update');
        Route::delete('/{id}', 'destroy')->name('services.destroy');
    });

    Route::prefix('hotels')
        ->name('hotels.')
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
    Route::post('/', [\App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('vouchers.store');
    Route::put('/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'update']);
    Route::get('/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'show'])->name('vouchers.show');
    Route::delete('/delete/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'delete'])->name('vouchers.delete');
    Route::post('/restore/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'restore']);
    Route::delete('/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'destroy']);
});
Route::prefix('vouchers')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\VoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/', [\App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('vouchers.store');
    Route::put('/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'update']);
    Route::get('/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'show'])->name('vouchers.show');
    Route::delete('/delete/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'delete'])->name('vouchers.delete');
    Route::post('/restore/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'restore']);
    Route::delete('/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'destroy']);
});
Route::prefix('vouchers')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\VoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/', [\App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('vouchers.store');
    Route::put('/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'update']);
    Route::get('/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'show'])->name('vouchers.show');
    Route::delete('/delete/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'delete'])->name('vouchers.delete');
    Route::post('/restore/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'restore']);
    Route::delete('/{id}', [\App\Http\Controllers\Admin\VoucherController::class, 'destroy']);
});

Route::controller(StatisticalController::class)->group(function () {
    Route::get('/statistical', 'index')->name('statistical.index');
    Route::post('/statistical', 'handleStatistical')->name('handle.statistical');
});

Route::get('/404', function () {
    return view('admin.errors.404');
})->name('error.404');