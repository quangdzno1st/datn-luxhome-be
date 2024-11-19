<?php

use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CatalogueRoomController;
use App\Http\Controllers\Api\HotelServiceController;
use App\Http\Controllers\Api\NewController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RankController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\RoomStatusController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::get('check-phone', 'checkPhone');
        Route::post('signup', 'signup');
        Route::post('password/reset', 'resetPassword');
        Route::get('notifications', 'AuthNotification');
        Route::get('notifications/{id}', 'NotificationDetail');
        Route::get('time-out', 'timeOut');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', 'logout');
            Route::get('user', 'user');
            Route::post('user/update', 'update');
            //Route::post('user/destroy', 'destroy');
            Route::post('password/change', 'changePassword');
            //            Route::post('password/reset', 'resetPassword');
            Route::post('check-distance', 'calculateDistance');
            Route::get('count-notification', 'countNotification');
        });
    });
});

Route::resource('/catalogue-rooms', CatalogueRoomController::class);
Route::get('/search/catalogue-rooms', [CatalogueRoomController::class, 'search']);
Route::fallback(function () {
    return response()->json([
        'status' => 404,
        'message' => 'Invalid Route'
    ]);
});

Route::prefix('hotels')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\HotelController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\HotelController::class, 'store']);
        Route::get('/trash', [\App\Http\Controllers\Api\HotelController::class, 'trash']);
        Route::get('/{id}', [\App\Http\Controllers\Api\HotelController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\HotelController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\HotelController::class, 'destroy']);
        Route::get('/restore/{id}', [\App\Http\Controllers\Api\HotelController::class, 'restore']);
        Route::get('/force-delete/{id}', [\App\Http\Controllers\Api\HotelController::class, 'forceDelete']);
    });

Route::prefix('regions')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\RegionController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\RegionController::class, 'store']);
        Route::get('/trash', [\App\Http\Controllers\Api\RegionController::class, 'trash']);
        Route::get('/{id}', [\App\Http\Controllers\Api\RegionController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\RegionController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\RegionController::class, 'destroy']);
        Route::get('/restore/{id}', [\App\Http\Controllers\Api\RegionController::class, 'restore']);
        Route::get('/force-delete/{id}', [\App\Http\Controllers\Api\RegionController::class, 'forceDelete']);
    });

Route::prefix('cities')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\CityController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\CityController::class, 'store']);
        Route::get('/trash', [\App\Http\Controllers\Api\CityController::class, 'trash']);
        Route::get('/{id}', [\App\Http\Controllers\Api\CityController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\CityController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\CityController::class, 'destroy']);
        Route::get('/restore/{id}', [\App\Http\Controllers\Api\CityController::class, 'restore']);
        Route::get('/force-delete/{id}', [\App\Http\Controllers\Api\CityController::class, 'forceDelete']);
    });

//Api Service
Route::prefix('services')
    ->controller(ServiceController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::put('/delete/{id}', 'delete');
        Route::put('/restore/{id}', 'restore');
        Route::delete('/{id}', 'destroy');
    });


Route::prefix('vouchers')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\VoucherController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\VoucherController::class, 'store']);
    Route::put('/{id}', [\App\Http\Controllers\Api\VoucherController::class, 'update']);
    Route::get('/{id}', [\App\Http\Controllers\Api\VoucherController::class, 'show']);
    Route::delete('/delete/{id}', [\App\Http\Controllers\Api\VoucherController::class, 'delete']);
    Route::post('/restore/{id}', [\App\Http\Controllers\Api\VoucherController::class, 'restore']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\VoucherController::class, 'destroy']);
});

//Api HotelService
Route::prefix('hotel/services')->controller(HotelServiceController::class)
    ->group(function () {
        Route::get('/{idHotel}', 'index');
        Route::post('/{idHotel}', 'store');
        Route::delete('/{id}', 'destroy');
        Route::delete('/', 'destroyMulti');
    });

Route::get('/booking/confirm', [BookingController::class, 'confirmBooking']);

//api Room
Route::prefix('rooms')
    ->controller(RoomController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::put('/delete/{id}', 'delete');
        Route::put('/restore/{id}', 'restore');
        Route::delete('/{id}', 'destroy');
    });

//rate
Route::prefix('rates')->group(function () {
    Route::get('/{numRecord}', [\App\Http\Controllers\Api\RateController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\RateController::class, 'store'])->middleware('auth');
    Route::put('/{id}', [\App\Http\Controllers\Api\RateController::class, 'update'])->middleware('auth');
    Route::get('/{id}', [\App\Http\Controllers\Api\RateController::class, 'show']);
    Route::delete('/delete/{id}', [\App\Http\Controllers\Api\RateController::class, 'delete'])->middleware('auth');
    Route::post('/restore/{id}', [\App\Http\Controllers\Api\RateController::class, 'restore'])->middleware('auth');
    Route::delete('/{id}', [\App\Http\Controllers\Api\RateController::class, 'destroy'])->middleware('auth');
    Route::get('hotels/{hotel_id}/average-rate', [\App\Http\Controllers\Api\RateController::class, 'getAverageRate']);
});
//room status
Route::patch('rooms/{room}/status', [RoomStatusController::class, 'update'])->middleware('auth:api');

//
Route::apiResource('ranks', RankController::class);

//ranks
Route::apiResource('ranks', RankController::class);

//api Room
Route::prefix('rooms')
    ->controller(RoomController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::put('/delete/{id}', 'delete');
        Route::put('/restore/{id}', 'restore');
        Route::delete('/{id}', 'destroy');
    });


