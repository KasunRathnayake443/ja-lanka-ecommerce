<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FlashSaleController as AdminFlashSaleController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\StoreManagementController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CouponController;
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
use Illuminate\Support\Facades\Route;

// ========== DESKTOP ROUTES ==========
Route::middleware(['detect.mobile'])->group(function () {

    Route::get('/', fn() => view('home'))->name('home');
    Route::get('/about', fn() => view('about'))->name('about');
    Route::get('/contact', fn() => view('contact'))->name('contact');
    Route::get('/shop', fn() => view('product.shop'))->name('shop');
    Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/sale', fn() => view('product.sale'))->name('sale');

    // ── API ──────────────────────────────────────────────────────────────────
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/products/filters', [ProductController::class, 'getFilters'])->name('products.filters');
        Route::get('/products',         [ProductController::class, 'getProducts'])->name('products');
        Route::get('/search',           [ProductController::class, 'search'])->name('search');

        Route::get('/banners',          [BannerController::class, 'getActiveBanners']);
        Route::get('/flash-sales',      [FlashSaleController::class, 'getActiveBanners']);

        Route::get('/cart',             [CartController::class, 'getCart'])->name('cart');
        Route::post('/cart/add',        [CartController::class, 'add'])->name('cart.add');
        Route::put('/cart/{id}',        [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{id}',     [CartController::class, 'remove'])->name('cart.remove');

        Route::middleware(['auth'])->group(function () {
            Route::get('/wishlist',                [WishlistController::class, 'getWishlist'])->name('wishlist');
            Route::post('/wishlist/{productId}',   [WishlistController::class, 'toggle'])->name('wishlist.toggle');
            Route::delete('/wishlist/{productId}', [WishlistController::class, 'remove'])->name('wishlist.remove');
        });
    });

    // ── Account ──────────────────────────────────────────────────────────────
    Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
        Route::get('/dashboard',                    [AccountController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders',                       [AccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}',                  [AccountController::class, 'orderDetail'])->name('order.detail');
        Route::get('/wishlist',                     [AccountController::class, 'wishlist'])->name('wishlist');
        Route::get('/profile',                      [AccountController::class, 'profile'])->name('profile');
        Route::put('/profile',                      [AccountController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password',                     [AccountController::class, 'changePassword'])->name('password.update');
        Route::get('/addresses',                    [AddressController::class, 'index'])->name('addresses');
        Route::post('/addresses',                   [AddressController::class, 'store'])->name('addresses.store');
        Route::put('/addresses/{id}',               [AddressController::class, 'update'])->name('addresses.update');
        Route::delete('/addresses/{id}',            [AddressController::class, 'destroy'])->name('addresses.destroy');
        Route::put('/addresses/{id}/set-default',   [AddressController::class, 'setDefault'])->name('addresses.set-default');
        Route::post('/addresses/geocode',           [AddressController::class, 'geocode'])->name('addresses.geocode');
    });

    // ── Checkout ─────────────────────────────────────────────────────────────
    Route::middleware(['auth'])->prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/',                     [CheckoutController::class, 'index'])->name('index');
        Route::post('/place-order',         [CheckoutController::class, 'placeOrder'])->name('place-order');
        Route::get('/success/{orderId}',    [CheckoutController::class, 'success'])->name('success');
        Route::get('/payment/{orderId}',    [CheckoutController::class, 'payment'])->name('payment');
        Route::post('/apply-coupon',        [CheckoutController::class, 'applyCoupon'])->name('apply-coupon');
        Route::post('/remove-coupon',       [CheckoutController::class, 'removeCoupon'])->name('remove-coupon');
    });

    // ── Food ─────────────────────────────────────────────────────────────────
    Route::prefix('food')->name('food.')->group(function () {
        Route::get('/',        [FoodController::class, 'index'])->name('index');
        Route::get('/{slug}',  [FoodController::class, 'show'])->name('show');
    });
});

// ========== MOBILE ROUTES ==========
Route::prefix('mobile')->name('mobile.')->group(function () {

    Route::get('/',           fn() => view('mobile.home'))->name('home');
    Route::get('/shop',       fn() => view('mobile.shop'))->name('shop');
    Route::get('/cart',       fn() => view('mobile.cart'))->name('cart');
    Route::get('/wishlist',   fn() => view('mobile.wishlist'))->name('wishlist');
    Route::get('/sale',       fn() => view('mobile.sale'))->name('sale');
    Route::get('/about',      fn() => view('mobile.about'))->name('about');
    Route::get('/contact',    fn() => view('mobile.contact'))->name('contact');
    Route::get('/product/{slug}', [ProductController::class, 'mobileShow'])->name('product');
    Route::get('/account',    [MobileAccountController::class, 'index'])->name('account');

    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/dashboard',                [MobileAccountController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders',                   [MobileAccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}',              [MobileAccountController::class, 'orderDetail'])->name('order.detail');
        Route::get('/wishlist',                 [MobileAccountController::class, 'wishlist'])->name('wishlist');
        Route::get('/profile',                  [MobileAccountController::class, 'profile'])->name('profile');
        Route::put('/profile',                  [MobileAccountController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password',                 [MobileAccountController::class, 'changePassword'])->name('password.update');
        Route::get('/addresses',                [MobileAccountController::class, 'addresses'])->name('addresses');
        Route::post('/address',                 [MobileAccountController::class, 'storeAddress'])->name('address.store');
        Route::put('/address/{id}',             [MobileAccountController::class, 'updateAddress'])->name('address.update');
        Route::delete('/address/{id}',          [MobileAccountController::class, 'deleteAddress'])->name('address.delete');
        Route::put('/address/{id}/set-default', [MobileAccountController::class, 'setDefault'])->name('address.set-default');
    });

    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/',                     [MobileCheckoutController::class, 'index'])->name('index');
        Route::post('/place-order',         [MobileCheckoutController::class, 'placeOrder'])->name('place-order');
        Route::get('/success/{orderId}',    [MobileCheckoutController::class, 'success'])->name('success');
        Route::get('/payment/{orderId}',    [MobileCheckoutController::class, 'payment'])->name('payment');
        Route::post('/apply-coupon',        [MobileCheckoutController::class, 'applyCoupon'])->name('apply-coupon');
        Route::post('/remove-coupon',       [MobileCheckoutController::class, 'removeCoupon'])->name('remove-coupon');
    });
});

// ========== ADMIN ROUTES ==========
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('login',  [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');

    Route::middleware('admin')->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout',   [AuthController::class, 'logout'])->name('logout');

        // ── Store Management ──────────────────────────────────────────────────
        Route::prefix('store')->name('store.')->group(function () {
            Route::get('/', [StoreManagementController::class, 'index'])->name('index');
            Route::post('category',         [StoreManagementController::class, 'storeCategory'])->name('category.store');
            Route::put('category/{id}',     [StoreManagementController::class, 'updateCategory'])->name('category.update');
            Route::delete('category/{id}',  [StoreManagementController::class, 'deleteCategory'])->name('category.delete');
            Route::post('brand',            [StoreManagementController::class, 'storeBrand'])->name('brand.store');
            Route::put('brand/{id}',        [StoreManagementController::class, 'updateBrand'])->name('brand.update');
            Route::delete('brand/{id}',     [StoreManagementController::class, 'deleteBrand'])->name('brand.delete');
            Route::post('origin',           [StoreManagementController::class, 'storeOrigin'])->name('origin.store');
            Route::put('origin/{id}',       [StoreManagementController::class, 'updateOrigin'])->name('origin.update');
            Route::delete('origin/{id}',    [StoreManagementController::class, 'deleteOrigin'])->name('origin.delete');
        });

        // ── Products ──────────────────────────────────────────────────────────
        Route::resource('products', AdminProductController::class);

        // ── Banners ───────────────────────────────────────────────────────────
        Route::resource('banners', AdminBannerController::class);
        Route::post('banners/update-order', [AdminBannerController::class, 'updateOrder'])->name('banners.update-order');

        // ── Flash Sales ───────────────────────────────────────────────────────
        Route::resource('flash-sales', AdminFlashSaleController::class);
        Route::post('flash-sales/auto-add', [AdminFlashSaleController::class, 'autoAdd'])->name('flash-sales.auto-add');

        // ── Customers ─────────────────────────────────────────────────────────
        Route::resource('customers', CustomerController::class);
        Route::post('customers/{id}/toggle-status',  [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
        Route::post('customers/{id}/reset-password', [CustomerController::class, 'resetPassword'])->name('customers.reset-password');
        Route::get('customers/{id}/impersonate',     [CustomerController::class, 'impersonate'])->name('customers.impersonate');
        Route::get('stop-impersonate',               [CustomerController::class, 'stopImpersonate'])->name('stop-impersonate');

        // ── Orders ────────────────────────────────────────────────────────────
        Route::prefix('orders')->name('orders.')->group(function () {

            Route::get('/',  [OrderController::class, 'index'])->name('index');

            // Static/action routes MUST be defined before /{id} wildcard
            Route::get('/create-manual',         [OrderController::class, 'createManual'])->name('create-manual');
            Route::post('/store-manual',         [OrderController::class, 'storeManual'])->name('store-manual');
            Route::get('/search-products',       [OrderController::class, 'searchProducts'])->name('search-products');
            Route::post('/validate-coupon',      [OrderController::class, 'validateCoupon'])->name('validate-coupon');
            Route::get('/get-user-details/{id}', [OrderController::class, 'getUserDetails'])->name('get-user-details');

            // Wildcard /{id} routes come LAST
            Route::get('/{id}',                 [OrderController::class, 'show'])->name('show');
            Route::post('/{id}/update-status',  [OrderController::class, 'updateStatus'])->name('update-status');
            Route::post('/{id}/update-payment', [OrderController::class, 'updatePaymentStatus'])->name('update-payment');
            Route::delete('/{id}',              [OrderController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/invoice',         [OrderController::class, 'invoice'])->name('invoice');
        });

        // ── Coupons ───────────────────────────────────────────────────────────
        Route::resource('coupons', CouponController::class);
        Route::post('coupons/{id}/toggle-status', [CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
        Route::get('coupons/{id}/usage',          [CouponController::class, 'usage'])->name('coupons.usage');
    });
});

require __DIR__.'/auth.php';