<header id="mainHeader" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 <?php echo e(request()->routeIs('home') ? 'bg-black/30 backdrop-blur-sm' : 'bg-white shadow-md'); ?>">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="<?php echo e(route('home')); ?>" class="text-2xl font-bold transition-colors duration-300">
                <span class="logo-part1 <?php echo e(request()->routeIs('home') ? 'text-white' : 'text-red-600'); ?>">Ja</span>
                <span class="logo-part2 <?php echo e(request()->routeIs('home') ? 'text-white/90' : 'text-gray-800'); ?>">Lanka</span>
            </a>
            
            <!-- Center Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="<?php echo e(route('home')); ?>" class="nav-link transition <?php echo e(request()->routeIs('home') ? 'text-white/90 hover:text-white' : 'text-gray-700 hover:text-red-600'); ?>">
                    Home
                </a>
                <a href="<?php echo e(route('shop')); ?>?type=food" class="nav-link transition <?php echo e(request()->routeIs('home') ? 'text-white/90 hover:text-white' : 'text-gray-700 hover:text-red-600'); ?>">
                    Foods
                </a>
                <a href="<?php echo e(route('shop')); ?>?type=appliance" class="nav-link transition <?php echo e(request()->routeIs('home') ? 'text-white/90 hover:text-white' : 'text-gray-700 hover:text-red-600'); ?>">
                    Appliances
                </a>
                <a href="<?php echo e(route('about')); ?>" class="nav-link transition <?php echo e(request()->routeIs('home') ? 'text-white/90 hover:text-white' : 'text-gray-700 hover:text-red-600'); ?>">
                    About Us
                </a>
                <a href="<?php echo e(route('contact')); ?>" class="nav-link transition <?php echo e(request()->routeIs('home') ? 'text-white/90 hover:text-white' : 'text-gray-700 hover:text-red-600'); ?>">
                    Contact Us
                </a>
            </nav>
            
            <!-- Right Icons -->
            <div class="flex items-center space-x-5">
                <!-- Search Icon -->
                <button id="desktopSearchBtn" onclick="toggleSearch()" class="transition">
                    <svg class="w-5 h-5 icon-white transition <?php echo e(request()->routeIs('home') ? 'text-white/90 hover:text-white' : 'text-gray-700 hover:text-red-600'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                
                <!-- Cart Icon -->
                <a href="#" onclick="openCartModal(); return false;" class="relative transition">
                    <svg class="w-6 h-6 icon-white transition <?php echo e(request()->routeIs('home') ? 'text-white/90 hover:text-white' : 'text-gray-700 hover:text-red-600'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span id="cartCount" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                </a>

                <!-- Wishlist Icon -->
                <a href="#" onclick="openWishlistModal(); return false;" class="relative transition">
                    <svg class="w-6 h-6 icon-white transition <?php echo e(request()->routeIs('home') ? 'text-white/90 hover:text-white' : 'text-gray-700 hover:text-red-600'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </a>

                <!-- User Menu -->
                <?php if(auth()->guard()->check()): ?>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="transition focus:outline-none">
                            <?php if(Auth::user()->profile_photo): ?>
                                <img src="<?php echo e(asset('storage/' . Auth::user()->profile_photo)); ?>" class="w-8 h-8 rounded-full object-cover <?php echo e(request()->routeIs('home') ? 'border-2 border-white' : ''); ?>">
                            <?php else: ?>
                                <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                                </div>
                            <?php endif; ?>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-100" style="display: none;">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900"><?php echo e(Auth::user()->name); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e(Auth::user()->email); ?></p>
                            </div>
                            <a href="<?php echo e(route('account.dashboard')); ?>" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Dashboard
                            </a>
                            <a href="<?php echo e(route('account.orders')); ?>" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                My Orders
                            </a>
                            <a href="<?php echo e(route('account.wishlist')); ?>" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                Wishlist
                            </a>
                            <a href="<?php echo e(route('account.profile')); ?>" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile Settings
                            </a>
                            <hr class="my-1 border-gray-100">
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="flex w-full items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="transition">
                        <svg class="w-6 h-6 icon-white transition <?php echo e(request()->routeIs('home') ? 'text-white/90 hover:text-white' : 'text-gray-700 hover:text-red-600'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Search Bar -->
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
</header>

<script>
    function toggleSearch() {
        const searchBar = document.getElementById('desktopSearchBar');
        if (searchBar) {
            searchBar.classList.toggle('hidden');
            if (!searchBar.classList.contains('hidden')) {
                document.getElementById('desktopSearchInput')?.focus();
            }
        }
    }
    
    // Close search when clicking outside
    document.addEventListener('click', function(event) {
        const searchBar = document.getElementById('desktopSearchBar');
        const searchBtn = document.getElementById('desktopSearchBtn');
        if (searchBar && !searchBar.classList.contains('hidden')) {
            if (!searchBar.contains(event.target) && !searchBtn?.contains(event.target)) {
                searchBar.classList.add('hidden');
            }
        }
    });
    
    // Change header style on scroll (only for homepage)
    <?php if(request()->routeIs('home')): ?>
    window.addEventListener('scroll', function() {
        const header = document.getElementById('mainHeader');
        const scrollPosition = window.scrollY;
        
        if (scrollPosition > 100) {
            header.classList.remove('bg-black/30', 'backdrop-blur-sm');
            header.classList.add('bg-white', 'shadow-md');
            
            // Change text colors to dark
            document.querySelectorAll('.nav-link').forEach(el => {
                el.classList.remove('text-white/90', 'text-white');
                el.classList.add('text-gray-700', 'hover:text-red-600');
            });
            
            // Change icon colors
            document.querySelectorAll('.icon-white').forEach(el => {
                el.classList.remove('text-white/90', 'text-white');
                el.classList.add('text-gray-700', 'hover:text-red-600');
            });
            
            // Logo colors
            const logoPart1 = document.querySelector('.logo-part1');
            const logoPart2 = document.querySelector('.logo-part2');
            if (logoPart1) {
                logoPart1.classList.remove('text-white');
                logoPart1.classList.add('text-red-600');
                logoPart2.classList.remove('text-white/90');
                logoPart2.classList.add('text-gray-800');
            }
        } else {
            header.classList.remove('bg-white', 'shadow-md');
            header.classList.add('bg-black/30', 'backdrop-blur-sm');
            
            // Change text colors back to white
            document.querySelectorAll('.nav-link').forEach(el => {
                el.classList.remove('text-gray-700');
                el.classList.add('text-white/90');
            });
            
            // Change icon colors back to white
            document.querySelectorAll('.icon-white').forEach(el => {
                el.classList.remove('text-gray-700');
                el.classList.add('text-white/90');
            });
            
            // Logo colors back to white
            const logoPart1 = document.querySelector('.logo-part1');
            const logoPart2 = document.querySelector('.logo-part2');
            if (logoPart1) {
                logoPart1.classList.remove('text-red-600');
                logoPart1.classList.add('text-white');
                logoPart2.classList.remove('text-gray-800');
                logoPart2.classList.add('text-white/90');
            }
        }
    });
    
    // Trigger on load
    window.dispatchEvent(new Event('scroll'));
    <?php endif; ?>
</script><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/layouts/partials/desktop-header.blade.php ENDPATH**/ ?>