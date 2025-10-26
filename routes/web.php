<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return redirect('/products');
});

Route::resource('products', ProductController::class);
Route::resource('categories', \App\Http\Controllers\CategoryController::class);
