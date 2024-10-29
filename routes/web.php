<?php

use App\Http\Controllers\Admin\HotelServiceController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\CatalogueRoomController;
use App\Http\Controllers\Admin\RoomController;
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
});


//Route::middleware('auth:sanctum')->group(function () {
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

Route::post('/upload-image', [CatalogueRoomController::class, 'storeImage'])->name('upload-image');

//});
Route::prefix('hotel-services')->controller(HotelServiceController::class)->group(function () {
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