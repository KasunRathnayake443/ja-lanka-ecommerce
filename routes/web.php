<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StoreManagementController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\FoodController;

// ========== DESKTOP ROUTES ==========
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/shop', function () {
    return view('product.shop');
})->name('shop');

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/dashboard', function () {
        return view('account.dashboard');
    })->name('dashboard');
    
    Route::get('/orders', function () {
        return view('account.orders');
    })->name('orders');
});

// ========== API ROUTES ==========
Route::prefix('api')->name('api.')->group(function () {
    // FILTERS must come before /products to avoid route conflict
    Route::get('/products/filters', [ProductController::class, 'getFilters'])->name('products.filters');
    Route::get('/products', [ProductController::class, 'getProducts'])->name('products');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    
    Route::get('/cart', [CartController::class, 'getCart'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/wishlist', [WishlistController::class, 'getWishlist'])->name('wishlist');
        Route::post('/wishlist/{productId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
        Route::delete('/wishlist/{productId}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    });
});

// ========== MOBILE ROUTES ==========
Route::prefix('mobile')->name('mobile.')->group(function () {
    Route::get('/', function () {
        return view('mobile.home');
    })->name('home');
    
    Route::get('/shop', function () {
        return view('mobile.shop');
    })->name('shop');
    
    Route::get('/product/{slug}', [ProductController::class, 'mobileShow'])->name('product');
    
    Route::get('/cart', function () {
        return view('mobile.cart');
    })->name('cart');
    
    Route::get('/wishlist', function () {
        return view('mobile.wishlist');
    })->name('wishlist');
    
    Route::get('/account', function () {
        return view('mobile.account');
    })->name('account');
});

// ========== FOOD ROUTES ==========
Route::prefix('food')->name('food.')->group(function () {
    Route::get('/', [FoodController::class, 'index'])->name('index');
    Route::get('/{slug}', [FoodController::class, 'show'])->name('show');
});

// ========== ADMIN ROUTES ==========
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    
    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        
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
        
        Route::resource('products', AdminProductController::class);
    });
});

require __DIR__.'/auth.php';