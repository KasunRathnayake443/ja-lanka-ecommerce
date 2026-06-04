<header class="bg-white shadow-md sticky top-0 z-30">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="<?php echo e(route('home')); ?>" class="text-2xl font-bold">
                <span class="text-red-600">Ja</span><span class="text-gray-800">Lanka</span>
            </a>
            
           <!-- Center Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="<?php echo e(route('home')); ?>" class="text-gray-700 hover:text-red-600 transition <?php echo e(request()->routeIs('home') ? 'text-red-600 font-semibold' : ''); ?>">
                    Home
                </a>
                <a href="<?php echo e(route('shop')); ?>?type=food" class="text-gray-700 hover:text-red-600 transition <?php echo e(request()->get('type') == 'food' ? 'text-red-600 font-semibold' : ''); ?>">
                    Foods
                </a>
                <a href="<?php echo e(route('shop')); ?>?type=appliance" class="text-gray-700 hover:text-red-600 transition <?php echo e(request()->get('type') == 'appliance' ? 'text-red-600 font-semibold' : ''); ?>">
                    Appliances
                </a>
                <a href="<?php echo e(route('about')); ?>" class="text-gray-700 hover:text-red-600 transition <?php echo e(request()->routeIs('about') ? 'text-red-600 font-semibold' : ''); ?>">
                    About Us
                </a>
                <a href="<?php echo e(route('contact')); ?>" class="text-gray-700 hover:text-red-600 transition <?php echo e(request()->routeIs('contact') ? 'text-red-600 font-semibold' : ''); ?>">
                    Contact Us
                </a>
            </nav>
            
            <!-- Right Icons -->
            <div class="flex items-center space-x-5">
                <!-- Search Icon -->
                <button id="desktopSearchBtn" onclick="toggleSearch()" class="text-gray-600 hover:text-red-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                
                <!-- Cart Icon -->
                <a href="#" onclick="openCartModal(); return false;" class="relative text-gray-600 hover:text-red-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span id="cartCount" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                </a>

                <!-- Wishlist Icon -->
                <a href="#" onclick="openWishlistModal(); return false;" class="relative text-gray-600 hover:text-red-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </a>



                
                <!-- Login/User Icon -->
                <?php if(auth()->guard()->check()): ?>
                    <div class="relative">
                        <button id="userMenuBtn" class="text-gray-600 hover:text-red-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </button>
                        <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden z-20">
                            <a href="<?php echo e(route('account.dashboard')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Dashboard</a>
                            <a href="<?php echo e(route('account.orders')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Orders</a>
                            <hr class="my-1">
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="text-gray-600 hover:text-red-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Search Bar (hidden by default) -->
        <div id="desktopSearchBar" class="hidden mt-4">
            <div class="relative">
                <input type="text" id="desktopSearchInput" placeholder="Search for products..." 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500">
                <button onclick="toggleSearch()" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // User dropdown toggle
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');
        
        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });
            
            document.addEventListener('click', function() {
                userDropdown.classList.add('hidden');
            });
        }
    </script>
</header><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/layouts/partials/desktop-header.blade.php ENDPATH**/ ?>