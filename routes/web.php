<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FlashSaleController as AdminFlashSaleController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\StoreManagementController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Mobile\AccountController as MobileAccountController;
use App\Http\Controllers\Mobile\CheckoutController as MobileCheckoutController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CouponController;
use Illuminate\Support\Facades\Route;

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

Route::get('/sale', function () {
    return view('product.sale');
})->name('sale');

// ========== API ROUTES ==========
Route::prefix('api')->name('api.')->group(function () {
    // FILTERS must come before /products to avoid route conflict
    Route::get('/products/filters', [ProductController::class, 'getFilters'])->name('products.filters');
    Route::get('/products', [ProductController::class, 'getProducts'])->name('products');
    Route::get('/search', [ProductController::class, 'search'])->name('search');

    // Banners API
    Route::get('/banners', [BannerController::class, 'getActiveBanners']);

    // Flash Sale API (for frontend)
    Route::get('/flash-sales', [FlashSaleController::class, 'getActiveBanners']);

    // Cart API
    Route::get('/cart', [CartController::class, 'getCart'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Wishlist API
    Route::middleware(['auth'])->group(function () {
        Route::get('/wishlist', [WishlistController::class, 'getWishlist'])->name('wishlist');
        Route::post('/wishlist/{productId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
        Route::delete('/wishlist/{productId}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    });

    // Search API
    Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');
});

// ========== DESKTOP ACCOUNT ROUTES ==========
Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AccountController::class, 'orderDetail'])->name('order.detail');
    Route::get('/wishlist', [AccountController::class, 'wishlist'])->name('wishlist');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [AccountController::class, 'changePassword'])->name('password.update');
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{id}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::put('/addresses/{id}/set-default', [AddressController::class, 'setDefault'])->name('addresses.set-default');
    Route::post('/addresses/geocode', [AddressController::class, 'geocode'])->name('addresses.geocode');
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

    Route::get('/account', [MobileAccountController::class, 'index'])->name('account');

    Route::get('/sale', function () {
        return view('mobile.sale');
    })->name('sale');

    Route::get('/about', function () {
        return view('mobile.about');
    })->name('about');
    
    Route::get('/contact', function () {
        return view('mobile.contact');
    })->name('contact');

    // Mobile Account Sub-pages
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/dashboard', [MobileAccountController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [MobileAccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [MobileAccountController::class, 'orderDetail'])->name('order.detail');
        Route::get('/wishlist', [MobileAccountController::class, 'wishlist'])->name('wishlist');
        Route::get('/profile', [MobileAccountController::class, 'profile'])->name('profile');
        Route::put('/profile', [MobileAccountController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [MobileAccountController::class, 'changePassword'])->name('password.update');
        Route::get('/addresses', [MobileAccountController::class, 'addresses'])->name('addresses');
        Route::post('/address', [MobileAccountController::class, 'storeAddress'])->name('address.store');
        Route::put('/address/{id}', [MobileAccountController::class, 'updateAddress'])->name('address.update');
        Route::delete('/address/{id}', [MobileAccountController::class, 'deleteAddress'])->name('address.delete');
        Route::put('/address/{id}/set-default', [MobileAccountController::class, 'setDefault'])->name('address.set-default');
    });

    // Mobile Checkout Routes
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [App\Http\Controllers\Mobile\CheckoutController::class, 'index'])->name('index');
        Route::post('/place-order', [App\Http\Controllers\Mobile\CheckoutController::class, 'placeOrder'])->name('place-order');
        Route::get('/success/{orderId}', [App\Http\Controllers\Mobile\CheckoutController::class, 'success'])->name('success');
        Route::get('/payment/{orderId}', [App\Http\Controllers\Mobile\CheckoutController::class, 'payment'])->name('payment');
        Route::post('/apply-coupon', [App\Http\Controllers\Mobile\CheckoutController::class, 'applyCoupon'])->name('apply-coupon');
        Route::post('/remove-coupon', [App\Http\Controllers\Mobile\CheckoutController::class, 'removeCoupon'])->name('remove-coupon');
    });
});

// ========== FOOD ROUTES ==========
Route::prefix('food')->name('food.')->group(function () {
    Route::get('/', [FoodController::class, 'index'])->name('index');
    Route::get('/{slug}', [FoodController::class, 'show'])->name('show');
});

// ========== CHECKOUT ROUTES ==========
Route::middleware(['auth'])->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('place-order');
    Route::get('/success/{orderId}', [CheckoutController::class, 'success'])->name('success');
    Route::get('/payment/{orderId}', [CheckoutController::class, 'payment'])->name('payment');
    
    // Coupon routes
    Route::post('/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('apply-coupon');
    Route::post('/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('remove-coupon');
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

        // Banner Management
        Route::resource('banners', AdminBannerController::class);
        Route::post('banners/update-order', [AdminBannerController::class, 'updateOrder'])->name('banners.update-order');

        // Flash Sale Management (Admin)
        Route::resource('flash-sales', AdminFlashSaleController::class);
        Route::post('flash-sales/auto-add', [AdminFlashSaleController::class, 'autoAdd'])->name('flash-sales.auto-add');

        // Customer Management
        Route::resource('customers', CustomerController::class);
        Route::post('customers/{id}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
        Route::post('customers/{id}/reset-password', [CustomerController::class, 'resetPassword'])->name('customers.reset-password');
        Route::get('customers/{id}/impersonate', [CustomerController::class, 'impersonate'])->name('customers.impersonate');
        Route::get('stop-impersonate', [CustomerController::class, 'stopImpersonate'])->name('stop-impersonate');

        // Order Management
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{id}', [OrderController::class, 'show'])->name('show');
            Route::post('/{id}/update-status', [OrderController::class, 'updateStatus'])->name('update-status');
            Route::post('/{id}/update-payment', [OrderController::class, 'updatePaymentStatus'])->name('update-payment');
            Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/invoice', [OrderController::class, 'invoice'])->name('invoice');
        });

        // Coupon Management
        Route::resource('coupons', CouponController::class);
        Route::post('/coupons/{id}/toggle-status', [CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
        Route::get('/coupons/{id}/usage', [CouponController::class, 'usage'])->name('coupons.usage');
            });

    
});

require __DIR__.'/auth.php';