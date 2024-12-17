<?php

use App\Http\Controllers\Admin\CatalogueRoomController;
use App\Http\Controllers\Admin\HotelServiceController;
use App\Http\Controllers\Client\AccountSettingController;
use App\Http\Controllers\Client\Auth\ForgotPasswordController;
use App\Http\Controllers\Client\Auth\LoginController;
use App\Http\Controllers\Client\Auth\RegisterController;
use App\Http\Controllers\Client\CityController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\HotelController;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    require 'admin.php';
});

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/search', [\App\Http\Controllers\Client\HotelController::class, 'search'])->name('home.search');
Route::get('/hotel/{hotel_id}', [\App\Http\Controllers\Client\HotelController::class, 'show'])->name('hotel.show');


Route::post('/upload-image', [CatalogueRoomController::class, 'storeImage'])->name('upload-image');

//});
Route::prefix('hotel/services')->controller(HotelServiceController::class)->group(function () {
    Route::get("/{idHotel}", 'index')->name("hotel.service.index");
    Route::post("/{idHotel}", 'store')->name("hotel.service.store");
    Route::get("/delete/{id}", 'destroy')->name("hotel.service.destroy");
    Route::delete("/delete", 'destroyMulti')->name("hotel.service.destroyMulti");
});

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

Route::get('/test/theme', function () {
    return view('client.bookingdetail');
});


//client
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/hotel/{id}', [HomeController::class, 'searchByPage'])->name('home.hotel.detail');
Route::get('/hotel/booking/{id}', [HotelController::class, 'booking'])->name('hotel.booking');

Route::get('/payment-order/{id}', [AccountSettingController::class, 'paymentOrder'])->name('orders.payment');
Route::get('/payment-return', [AccountSettingController::class, 'paymentReturn'])->name('orders.paymentReturn');
Route::get('/cities/search-by-page', [CityController::class, 'searchByPage'])->name('cities.searchByPage');

//api booking
Route::prefix('orders')
    ->controller(AccountSettingController::class)
    ->group(function () {
        // Route::get('/', 'index')->name('orders.index');
        Route::get('/services', 'orderService')->name('orders.services');
        Route::get('/confirm', 'confirmOrder')->name('orders.confirm');
        Route::post('/', 'store')->name('orders.store');
        Route::get('/cancel/{id}', 'cancelOrder')->name('orders.cancel');
        Route::get('/{id}', 'show')->name('orders.detail');
    });

Route::get('/hotel/{id}', [HomeController::class, 'searchByPage'])->name('home.hotel.detail');
Route::get('/hotel/booking/{id}', [HotelController::class, 'booking'])->name('hotel.booking');
Route::get('/payment-order/{id}', [AccountSettingController::class, 'paymentOrder'])->name('orders.payment');
Route::get('/payment-return', [AccountSettingController::class, 'paymentReturn'])->name('orders.paymentReturn');
Route::get('/cities/search-by-page', [CityController::class, 'searchByPage'])->name('cities.searchByPage');

Route::middleware('is.login')->group(function () {
    Route::get('/orders', [AccountSettingController::class, 'index'])->name('orders.index');
    Route::post('/update/user', [AccountSettingController::class, 'changeUserInfo'])->name('client.update.user');
    Route::post('/change/password/user', [AccountSettingController::class, 'changePassword'])->name('client.change.password.user');
    Route::post('rating/{orderId}', [AccountSettingController::class, 'rating'])->name('client.rating');
});

Route::get('/login', [LoginController::class, 'index'])->name('client.login');
Route::post('/login', [LoginController::class, 'login'])->name('client.login');
Route::get('/logout', [LoginController::class, 'logout'])->name('client.logout');
Route::get('/register', [RegisterController::class, 'index'])->name('client.register');
Route::post('/register', [RegisterController::class, 'register'])->name('client.register');
Route::get('/password/reset', [ForgotPasswordController::class, 'showFormForgot'])->name('client.password.reset');
Route::post('/password/reset', [ForgotPasswordController::class, 'sendMailReset'])->name('client.password.reset');
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'showFormResetPassword'])->name('client.show.form.reset');
Route::post('/password/reset/update', [ForgotPasswordController::class, 'ResetUpdatePassword'])->name('client.reset.update');


Route::get('/test-redis', function () {
    // Gửi một giá trị vào Redis
    Redis::set('name', 'John Doe');

    // Lấy giá trị từ Redis
    $value = Redis::get('name');

    // Hiển thị giá trị lấy từ Redis
    return $value; // Kết quả sẽ là "John Doe"
});

//Route::get('admin/orders/show/{orderId}/available-services', [\App\Http\Controllers\Admin\OrderDetailController::class, 'availableServices']);
