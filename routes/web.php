<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;

// Homepage (only once)
Route::get('/', function () {
    return view('home');
})->name('home');

// Placeholder routes (will implement later)
Route::get('/products', function () {
    return view('products.index');
})->name('products.index');

Route::get('/brands', function () {
    return view('brands');
})->name('brands');

Route::get('/sale', function () {
    return view('sale');
})->name('sale');

Route::get('/blog', function () {
    return view('blog');
})->name('blog');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/cart', function () {
    return view('cart');
})->name('cart');

// Account routes (customer - will implement later)
Route::middleware(['auth'])->prefix('account')->group(function () {
    Route::get('/dashboard', function () {
        return view('account.dashboard');
    })->name('account.dashboard');
    
    Route::get('/orders', function () {
        return view('account.orders');
    })->name('account.orders');
    
    Route::get('/wishlist', function () {
        return view('account.wishlist');
    })->name('account.wishlist');
    
    Route::get('/profile', function () {
        return view('account.profile');
    })->name('account.profile');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest (not logged in)
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    });
    
    // Protected (logged in)
    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});

// Customer auth routes (login, register, etc.)
require __DIR__.'/auth.php';