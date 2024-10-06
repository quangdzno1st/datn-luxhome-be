<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    require 'admin.php';
});

Route::get('/oke', function (){
return 1;
});