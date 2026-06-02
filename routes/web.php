<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StoreManagementController;
use App\Http\Controllers\Admin\ProductController;  
use App\Http\Controllers\FoodController;

// Homepage
Route::get('/', function () {
    return view('home');
})->name('home');

// Food Routes (Public)
Route::prefix('food')->name('food.')->group(function () {
    Route::get('/', [FoodController::class, 'index'])->name('index');
    Route::get('/{slug}', [FoodController::class, 'show'])->name('show');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    });
    
    // Protected routes
    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        
        // Store Management (Categories, Brands, Origins)
        Route::prefix('store')->name('store.')->group(function () {
            Route::get('/', [StoreManagementController::class, 'index'])->name('index');
            Route::post('category', [StoreManagementController::class, 'storeCategory'])->name('category.store');
            Route::put('category/{id}', [StoreManagementController::class, 'updateCategory'])->name('category.update');
            Route::delete('category/{id}', [StoreManagementController::class, 'deleteCategory'])->name('category.delete');
            Route::post('brand', [StoreManagementController::class, 'storeBrand'])->name('brand.store');
            Route::put('brand/{id}', [StoreManagementController::class, 'updateBrand'])->name('brand.update');
            Route::delete('brand/{id}', [StoreManagementController::class, 'deleteBrand'])->name('brand.delete');
            Route::post('origin', [StoreManagementController::class, 'storeOrigin'])->name('origin.store');
            Route::put('origin/{id}', [StoreManagementController::class, 'updateOrigin'])->name('origin.update');
            Route::delete('origin/{id}', [StoreManagementController::class, 'deleteOrigin'])->name('origin.delete');
        });
        
        // Product Management
        Route::resource('products', ProductController::class);
    });
});

require __DIR__.'/auth.php';