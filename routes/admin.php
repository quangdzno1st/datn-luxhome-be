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


use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['guest:admin']], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.login');
});
//
//Route::group(['middleware' => ['auth:admin']], function () {
//    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
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
//    Route::resource('users', UserController::class);
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
//});
//
