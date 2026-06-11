@extends('layouts.mobile')

@section('title', 'Account')

@section('content')
<div class="pb-20">
    
    <!-- Profile Header -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 px-5 py-6 text-white">
        <div class="flex items-center gap-4">
            @if(Auth::user() && Auth::user()->profile_photo)
                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-16 h-16 rounded-full object-cover border-2 border-white">
            @elseif(Auth::user())
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <span class="text-2xl font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
            @else
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            @endif
            <div>
                @if(Auth::user())
                    <h2 class="text-xl font-bold">{{ Auth::user()->name }}</h2>
                    <p class="text-sm text-white/80">{{ Auth::user()->email }}</p>
                @else
                    <h2 class="text-xl font-bold">Guest User</h2>
                    <p class="text-sm text-white/80">Please login to access your account</p>
                @endif
            </div>
        </div>
    </div>
    
    @auth
    <!-- Stats Cards (Only for logged in users) -->
    <div class="grid grid-cols-3 gap-3 p-4">
        <div class="bg-white rounded-xl p-3 text-center shadow-sm border border-gray-100">
            <div class="text-2xl font-bold text-red-600">{{ $totalOrders ?? 0 }}</div>
            <div class="text-xs text-gray-500">Orders</div>
        </div>
        <div class="bg-white rounded-xl p-3 text-center shadow-sm border border-gray-100">
            <div class="text-2xl font-bold text-red-600">LKR {{ number_format($totalSpent ?? 0, 0) }}</div>
            <div class="text-xs text-gray-500">Spent</div>
        </div>
        <div class="bg-white rounded-xl p-3 text-center shadow-sm border border-gray-100">
            <div class="text-2xl font-bold text-red-600">{{ $wishlistCount ?? 0 }}</div>
            <div class="text-xs text-gray-500">Wishlist</div>
        </div>
    </div>
    
    <!-- Menu Items (Logged In) -->
    <div class="p-4 space-y-3">
        <a href="{{ route('mobile.account.dashboard') }}" class="flex items-center justify-between p-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Dashboard</h3>
                    <p class="text-xs text-gray-500">View your account overview</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
        
        <a href="{{ route('mobile.account.orders') }}" class="flex items-center justify-between p-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">My Orders</h3>
                    <p class="text-xs text-gray-500">Track and view orders</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
        
        <a href="{{ route('mobile.account.wishlist') }}" class="flex items-center justify-between p-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Wishlist</h3>
                    <p class="text-xs text-gray-500">Your saved items</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
        
        <a href="{{ route('mobile.account.profile') }}" class="flex items-center justify-between p-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Profile Settings</h3>
                    <p class="text-xs text-gray-500">Update your information</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
        
        <a href="{{ route('mobile.account.addresses') }}" class="flex items-center justify-between p-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Address Book</h3>
                    <p class="text-xs text-gray-500">Manage your addresses</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
        
        <div class="pt-4 border-t border-gray-100 mt-2">
            <a href="{{ route('mobile.about') }}" class="flex items-center justify-between p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">About Us</h3>
                        <p class="text-xs text-gray-500">Learn about our story</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            
            <a href="{{ route('mobile.contact') }}" class="flex items-center justify-between p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Contact Us</h3>
                        <p class="text-xs text-gray-500">Get in touch with us</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        
        <!-- Logout Button -->
        <div class="pt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 p-3 bg-red-50 rounded-xl text-red-600 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
    
    @else
    <!-- Not Logged In Menu -->
    <div class="p-4 space-y-3">
        <div class="bg-white rounded-xl p-8 text-center shadow-sm border border-gray-100">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            <p class="text-gray-600 mb-4">Login to access your account</p>
            <a href="{{ route('login') }}" class="inline-block bg-red-600 text-white px-8 py-2 rounded-lg font-semibold">Login</a>
            <p class="text-sm text-gray-500 mt-4">
                Don't have an account? <a href="{{ route('register') }}" class="text-red-600">Register</a>
            </p>
        </div>
        
        <!-- About & Contact for guests -->
        <div class="pt-4">
            <a href="{{ route('mobile.about') }}" class="flex items-center justify-between p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">About Us</h3>
                        <p class="text-xs text-gray-500">Learn about our story</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            
            <a href="{{ route('mobile.contact') }}" class="flex items-center justify-between p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Contact Us</h3>
                        <p class="text-xs text-gray-500">Get in touch with us</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
    @endauth
    
</div>
@endsection