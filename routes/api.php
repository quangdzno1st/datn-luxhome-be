<?php

use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\CatalogueRoomController;
use App\Http\Controllers\Api\NewController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;

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
Route::fallback(function () {
    return response()->json([
        'status' => 404,
        'message' => 'Invalid Route'
    ]);
});

Route::prefix('hotels')
->group(function(){
    Route::get('/', [\App\Http\Controllers\Admin\HotelController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Admin\HotelController::class, 'store']);
    Route::get('/trash', [\App\Http\Controllers\Admin\HotelController::class, 'trash']);
    Route::get('/{slug}', [\App\Http\Controllers\Admin\HotelController::class, 'show']);
    Route::put('/{slug}', [\App\Http\Controllers\Admin\HotelController::class, 'update']);
    Route::delete('/{slug}', [\App\Http\Controllers\Admin\HotelController::class, 'destroy']);
    Route::get('/restore/{slug}', [\App\Http\Controllers\Admin\HotelController::class, 'restore']);
    Route::get('/force-delete/{slug}', [\App\Http\Controllers\Admin\HotelController::class, 'forceDelete']);
});

Route::prefix('regions')
    ->group(function(){
        Route::get('/', [\App\Http\Controllers\Admin\RegionController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Admin\RegionController::class, 'store']);
        Route::get('/trash', [\App\Http\Controllers\Admin\RegionController::class, 'trash']);
        Route::get('/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'destroy']);
        Route::get('/restore/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'restore']);
        Route::get('/force-delete/{id}', [\App\Http\Controllers\Admin\RegionController::class, 'forceDelete']);
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

Route::prefix('vouchers')->group(function (){
    Route::get('/',[\App\Http\Controllers\Api\VoucherController::class,'index']);
    Route::post('/',[\App\Http\Controllers\Api\VoucherController::class,'store']);
    Route::put('/{id}',[\App\Http\Controllers\Api\VoucherController::class,'update']);
    Route::get('/{id}',[\App\Http\Controllers\Api\VoucherController::class,'show']);
    Route::delete('/delete/{id}',[\App\Http\Controllers\Api\VoucherController::class,'delete']);
    Route::post('/restore/{id}',[\App\Http\Controllers\Api\VoucherController::class,'restore']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\VoucherController::class,'destroy']);
});
