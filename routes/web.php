<?php

use App\Http\Controllers\Admin\CatalogueRoomController;
use App\Http\Controllers\Admin\RoomController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    require 'admin.php';
});

Route::get('/oke', function () {
    return view('admin.users.index');
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
