<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ✅ Redirect root to dashboard
Route::get('/', fn() => redirect('/dashboard'));

// ✅ Dashboard (must be authenticated)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ✅ Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Products Routes
|--------------------------------------------------------------------------
*/

// 🟢 Public access (anyone can view)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// 🔒 Authenticated users only
Route::middleware('auth')->group(function () {
    // ✅ Put “create” route before “{product}” to avoid conflict
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // 🧩 Soft Delete Actions (for admin panel)
    Route::get('/products/trashed', [ProductController::class, 'trashed'])->name('products.trashed');
    Route::put('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('/products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.forceDelete');
});

// 🟢 Product Details (public)
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Categories Routes
|--------------------------------------------------------------------------
*/

// 🟢 Public access
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// 🔒 Authenticated users only
Route::middleware('auth')->group(function () {
    // ✅ “create” before “{category}” to avoid conflict
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // 🧩 Soft Delete Actions
    Route::get('/categories/trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
    Route::put('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');
});

// 🟢 Category Details (public)
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

require __DIR__.'/auth.php';
