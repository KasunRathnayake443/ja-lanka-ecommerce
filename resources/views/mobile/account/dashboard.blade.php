@extends('layouts.mobile')

@section('title', 'My Account')

@section('content')
<div class="pb-20">
    
    <!-- Profile Header -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 px-5 py-6 text-white">
        <div class="flex items-center gap-4">
            @if(Auth::user()->profile_photo)
                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-16 h-16 rounded-full object-cover border-2 border-white">
            @else
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <span class="text-2xl font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
            @endif
            <div>
                <h2 class="text-xl font-bold">{{ Auth::user()->name }}</h2>
                <p class="text-sm text-white/80">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-3 gap-3 p-4">
        <div class="bg-white rounded-xl p-3 text-center shadow-sm border border-gray-100">
            <div class="text-2xl font-bold text-red-600">{{ $totalOrders }}</div>
            <div class="text-xs text-gray-500">Orders</div>
        </div>
        <div class="bg-white rounded-xl p-3 text-center shadow-sm border border-gray-100">
            <div class="text-2xl font-bold text-red-600">LKR {{ number_format($totalSpent, 0) }}</div>
            <div class="text-xs text-gray-500">Spent</div>
        </div>
        <div class="bg-white rounded-xl p-3 text-center shadow-sm border border-gray-100">
            <div class="text-2xl font-bold text-red-600">{{ $wishlistCount }}</div>
            <div class="text-xs text-gray-500">Wishlist</div>
        </div>
    </div>
    
    <!-- Menu Items -->
    <div class="p-4 space-y-3">
        <a href="{{ route('mobile.account.orders') }}" class="flex items-center justify-between p-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">My Orders</h3>
                    <p class="text-xs text-gray-500">View your order history</p>
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
    </div>
    
    <!-- Recent Orders -->
    @if($recentOrders->count() > 0)
    <div class="p-4 pt-2">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-gray-800">Recent Orders</h3>
            <a href="{{ route('mobile.account.orders') }}" class="text-xs text-red-600">View All</a>
        </div>
        <div class="space-y-3">
            @foreach($recentOrders as $order)
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">#{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full 
                        {{ $order->order_status == 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $order->order_status == 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $order->order_status == 'shipped' ? 'bg-purple-100 text-purple-700' : '' }}
                        {{ $order->order_status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                        {{ ucfirst($order->order_status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                    <p class="text-sm font-semibold text-gray-800">LKR {{ number_format($order->grand_total, 2) }}</p>
                    <a href="{{ route('mobile.account.order.detail', $order->id) }}" class="text-xs text-red-600">View Details →</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="p-8 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        <p class="text-gray-500">No orders yet</p>
        <a href="{{ route('mobile.shop') }}" class="inline-block mt-3 text-red-600 text-sm">Start Shopping →</a>
    </div>
    @endif
    
    <!-- Logout Button -->
    <div class="p-4 pt-2">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 p-3 bg-gray-100 rounded-xl text-gray-700 font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </button>
        </form>
    </div>
    
</div>
@endsection