<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('api-products', ProductController::class);
    Route::put('api-products/{id}/restore', [ProductController::class, 'restore']);
    Route::delete('api-products/{id}/force-delete', [ProductController::class, 'forceDelete']);
});
