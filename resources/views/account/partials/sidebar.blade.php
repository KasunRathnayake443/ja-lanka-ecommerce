<div class="bg-white border border-gray-100 rounded-lg overflow-hidden sticky top-24">
    <div class="p-6 text-center border-b border-gray-100">
        @if(Auth::user()->profile_photo)
            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-20 h-20 rounded-full mx-auto object-cover mb-3">
        @else
            <div class="w-20 h-20 bg-red-100 rounded-full mx-auto flex items-center justify-center mb-3">
                <span class="text-3xl font-bold text-red-700">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
        @endif
        <h3 class="font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
    </div>
    
    <nav class="p-4 space-y-1">
        <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg {{ request()->routeIs('account.dashboard') ? 'bg-red-50 text-red-700' : 'hover:bg-gray-50' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Dashboard</span>
        </a>
        
        <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg {{ request()->routeIs('account.orders') ? 'bg-red-50 text-red-700' : 'hover:bg-gray-50' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span>My Orders</span>
        </a>
        
        <a href="{{ route('account.wishlist') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg {{ request()->routeIs('account.wishlist') ? 'bg-red-50 text-red-700' : 'hover:bg-gray-50' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <span>Wishlist</span>
        </a>
        
        <a href="{{ route('account.profile') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg {{ request()->routeIs('account.profile') ? 'bg-red-50 text-red-700' : 'hover:bg-gray-50' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>Profile Settings</span>
        </a>
        
        <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t border-gray-100">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</div>