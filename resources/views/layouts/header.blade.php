<header class="bg-white shadow-md sticky top-0 z-30">
    <nav class="container mx-auto px-4 py-3">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-4">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-red-600">
                    Ja<span class="text-gray-800">Lanka</span>
                </a>
            </div>
            
            <!-- Desktop Navigation (hidden on mobile) -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="#" class="text-gray-700 hover:text-red-600 transition">Food</a>
                <a href="#" class="text-gray-700 hover:text-red-600 transition">Appliances</a>
                <a href="#" class="text-gray-700 hover:text-red-600 transition">Brands</a>
                <a href="#" class="text-gray-700 hover:text-red-600 transition">Sale</a>
                <a href="#" class="text-gray-700 hover:text-red-600 transition">Blog</a>
                <a href="#" class="text-gray-700 hover:text-red-600 transition">Contact</a>
            </div>
            
            <!-- Right Icons -->
            <div class="flex items-center space-x-4">
                <!-- Search Icon -->
                <button id="searchBtn" class="text-gray-600 hover:text-red-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                
                <!-- Cart Icon -->
                <a href="#" class="relative text-gray-600 hover:text-red-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span id="cartCount" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                </a>
                
                <!-- User Icon -->
                @auth
                    <div class="relative">
                        <button id="userMenuBtn" class="flex items-center space-x-1 text-gray-600 hover:text-red-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                        </button>
                        <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden z-20">
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Dashboard</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Orders</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Wishlist</a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-red-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </a>
                @endauth
                
                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="md:hidden text-gray-600 hover:text-red-600 transition focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Search Bar -->
        <div id="searchBar" class="hidden mt-3">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search products..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500">
                <button id="closeSearch" class="absolute right-3 top-2 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>
</header>

<!-- Mobile Menu Overlay (outside header, at body level) -->
<div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden transition-opacity duration-300"></div>

<!-- Mobile Menu (outside header, at body level) -->
<div id="mobileMenu" class="fixed top-0 left-0 w-64 h-full bg-white shadow-xl z-50 transform -translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto">
    <div class="p-4 border-b">
        <div class="flex justify-between items-center">
            <span class="text-xl font-bold text-red-600">Ja Lanka</span>
            <button id="closeMobileMenu" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
    </div>
    <div class="py-4">
        <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-red-50">🍜 Food</a>
        <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-red-50">🔌 Appliances</a>
        <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-red-50">🏷️ Brands</a>
        <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-red-50">🔥 Sale</a>
        <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-red-50">📝 Blog</a>
        <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-red-50">📞 Contact</a>
    </div>
    
    @guest
        <div class="p-4 border-t">
            <a href="{{ route('login') }}" class="block w-full text-center bg-red-600 text-white py-2 rounded-lg mb-2">Login</a>
            <a href="{{ route('register') }}" class="block w-full text-center border border-red-600 text-red-600 py-2 rounded-lg">Register</a>
        </div>
    @endguest
</div>