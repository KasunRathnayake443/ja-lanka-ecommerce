<div class="bg-white shadow-sm sticky top-0 z-30 px-4 py-3 flex items-center justify-between">
    <!-- Logo -->
    <a href="<?php echo e(route('mobile.home')); ?>" class="text-xl font-bold">
        <span class="text-red-600">Ja</span><span class="text-gray-800">Lanka</span>
    </a>
    
    <!-- Search Bar (always visible on mobile) -->
    <div class="flex-1 mx-4">
        <div class="relative">
            <input type="text" id="mobileSearchTrigger" placeholder="Search products..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500"
                   onclick="openSearch()">
            <svg class="absolute right-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>
    
    <!-- Cart Icon -->
    <a href="<?php echo e(route('mobile.cart')); ?>" class="relative">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        <span id="mobileCartCount" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center hidden">0</span>
    </a>
</div><?php /**PATH C:\Users\User\Herd\ja-lanka-ecommerce\resources\views/layouts/partials/mobile-header.blade.php ENDPATH**/ ?>