<div class="bottom-nav">
    <div class="flex justify-around items-center">
        <!-- Home Tab -->
        <a href="<?php echo e(route('mobile.home')); ?>" class="nav-item <?php echo e(request()->routeIs('mobile.home') ? 'active' : 'text-gray-500'); ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Home</span>
        </a>
        
        <!-- Shop Tab -->
        <a href="<?php echo e(route('mobile.shop')); ?>" class="nav-item <?php echo e(request()->routeIs('mobile.shop') ? 'active' : 'text-gray-500'); ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span>Shop</span>
        </a>
        
        <!-- Cart Tab -->
        <a href="<?php echo e(route('mobile.cart')); ?>" class="nav-item <?php echo e(request()->routeIs('mobile.cart') ? 'active' : 'text-gray-500'); ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span>Cart</span>
        </a>
        
        <!-- Wishlist Tab -->
        <a href="<?php echo e(route('mobile.wishlist')); ?>" class="nav-item <?php echo e(request()->routeIs('mobile.wishlist') ? 'active' : 'text-gray-500'); ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <span>Wishlist</span>
        </a>
        
        <!-- Account Tab -->
        <a href="<?php echo e(route('mobile.account')); ?>" class="nav-item <?php echo e(request()->routeIs('mobile.account') ? 'active' : 'text-gray-500'); ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>Account</span>
        </a>
    </div>
</div><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/layouts/partials/mobile-bottom-nav.blade.php ENDPATH**/ ?>