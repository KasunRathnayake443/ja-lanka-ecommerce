@extends('layouts.mobile')

@section('title', 'About Us')

@section('content')
<div class="pb-20">
    
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-red-700 to-red-800 py-16 px-4 text-center text-white">
        <h1 class="text-3xl font-bold mb-3">About Ja Lanka</h1>
        <div class="w-12 h-0.5 bg-white/50 mx-auto mb-4"></div>
        <p class="text-sm opacity-90">Bringing authentic global flavors to Sri Lanka</p>
    </div>
    
    <!-- Our Story -->
    <div class="p-5 bg-white border-b border-gray-100">
        <div class="text-center mb-4">
            <span class="text-red-600 text-xs uppercase tracking-wider">Our Story</span>
            <h2 class="text-2xl font-bold text-gray-800 mt-1">From Passion to Purpose</h2>
        </div>
        <p class="text-gray-600 text-sm leading-relaxed text-center mb-3">
            Ja Lanka was born from a simple idea: to bring the world's most authentic culinary experiences 
            to homes across Sri Lanka.
        </p>
        <p class="text-gray-600 text-sm leading-relaxed text-center mb-3">
            We travel the world to source authentic ingredients from Italy, Korea, Japan, and beyond.
        </p>
        <p class="text-gray-600 text-sm leading-relaxed text-center">
            Today, we proudly serve thousands of customers across Sri Lanka.
        </p>
    </div>
    
    <!-- Stats -->
    <div class="grid grid-cols-3 gap-2 p-5 bg-gray-50">
        <div class="text-center">
            <div class="text-2xl font-bold text-red-600">5000+</div>
            <div class="text-xs text-gray-500">Customers</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-red-600">50+</div>
            <div class="text-xs text-gray-500">Brands</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-red-600">100%</div>
            <div class="text-xs text-gray-500">Authentic</div>
        </div>
    </div>
    
    <!-- Our Values -->
    <div class="p-5 bg-white">
        <div class="text-center mb-5">
            <span class="text-red-600 text-xs uppercase tracking-wider">What We Believe</span>
            <h2 class="text-2xl font-bold text-gray-800 mt-1">Our Values</h2>
        </div>
        
        <div class="space-y-4">
            <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Authenticity</h3>
                    <p class="text-sm text-gray-500">Genuine products sourced directly from origin.</p>
                </div>
            </div>
            
            <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Global Selection</h3>
                    <p class="text-sm text-gray-500">Curated collection from around the world.</p>
                </div>
            </div>
            
            <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Customer First</h3>
                    <p class="text-sm text-gray-500">Your satisfaction is our priority.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- What We Offer -->
    <div class="p-5 bg-gray-50">
        <div class="text-center mb-5">
            <span class="text-red-600 text-xs uppercase tracking-wider">Our Offerings</span>
            <h2 class="text-2xl font-bold text-gray-800 mt-1">What We Bring</h2>
        </div>
        
        <div class="space-y-3">
            <div class="flex gap-3 p-4 bg-white rounded-xl">
                <span class="text-2xl">🍜</span>
                <div>
                    <h3 class="font-semibold text-gray-800">Authentic Food</h3>
                    <p class="text-sm text-gray-500">Japanese matcha, Korean kimchi, Italian pasta</p>
                </div>
            </div>
            <div class="flex gap-3 p-4 bg-white rounded-xl">
                <span class="text-2xl">🔌</span>
                <div>
                    <h3 class="font-semibold text-gray-800">Premium Appliances</h3>
                    <p class="text-sm text-gray-500">Zojirushi, Tiger, Panasonic</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Team -->
    <div class="p-5 bg-white">
        <div class="text-center mb-5">
            <span class="text-red-600 text-xs uppercase tracking-wider">Behind the Brand</span>
            <h2 class="text-2xl font-bold text-gray-800 mt-1">Our Team</h2>
        </div>
        
        <div class="space-y-4">
            <div class="flex gap-4 items-center p-4 bg-gray-50 rounded-xl">
                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl">👨‍🍳</span>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Kasun Rathnayake</h3>
                    <p class="text-xs text-red-600">Founder & CEO</p>
                    <p class="text-xs text-gray-500 mt-1">Culinary enthusiast with passion for global flavors</p>
                </div>
            </div>
            
            <div class="flex gap-4 items-center p-4 bg-gray-50 rounded-xl">
                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl">👩‍🍳</span>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Amanda Perera</h3>
                    <p class="text-xs text-red-600">Head of Curation</p>
                    <p class="text-xs text-gray-500 mt-1">Selects finest products from around the world</p>
                </div>
            </div>
            
            <div class="flex gap-4 items-center p-4 bg-gray-50 rounded-xl">
                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl">📦</span>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Ruwan Silva</h3>
                    <p class="text-xs text-red-600">Customer Experience</p>
                    <p class="text-xs text-gray-500 mt-1">Ensures delightful customer experience</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA -->
    <div class="m-4 p-5 bg-red-600 rounded-xl text-center text-white">
        <h3 class="text-xl font-bold mb-2">Ready to Explore?</h3>
        <p class="text-sm text-white/80 mb-4">Discover authentic global flavors</p>
        <a href="{{ route('mobile.shop') }}" class="inline-block bg-white text-red-600 px-6 py-2 rounded-full text-sm font-semibold hover:bg-gray-100 transition">
            Shop Now →
        </a>
    </div>
    
</div>
@endsection