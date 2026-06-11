@extends('layouts.desktop')

@section('title', 'About Us - Ja Lanka')

@section('content')

<!-- Hero Section -->
<section class="relative h-[50vh] min-h-[400px] bg-cover bg-center flex items-center" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('/images/about-hero.jpg');">
    <div class="container mx-auto px-6 text-center text-white">
        <h1 class="text-5xl md:text-6xl font-bold font-['Playfair_Display'] mb-4">About Ja Lanka</h1>
        <div class="w-20 h-0.5 bg-red-600 mx-auto mb-6"></div>
        <p class="text-lg max-w-2xl mx-auto">
            Bringing authentic global flavors and premium kitchen appliances to Sri Lanka
        </p>
    </div>
</section>

<!-- Our Story -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-3xl mx-auto text-center mb-12">
            <span class="text-red-600 text-sm uppercase tracking-wider">Our Story</span>
            <h2 class="text-3xl md:text-4xl font-bold font-['Playfair_Display'] text-gray-900 mt-2">From Passion to Purpose</h2>
            <div class="w-16 h-px bg-red-600 mx-auto mt-4"></div>
        </div>
        
        <div class="max-w-4xl mx-auto">
            <p class="text-gray-600 leading-relaxed mb-6 text-center">
                Ja Lanka was born from a simple idea: to bring the world's most authentic culinary experiences 
                to homes across Sri Lanka.
            </p>
            <p class="text-gray-600 leading-relaxed mb-6 text-center">
                We travel the world to source the finest Japanese kitchen appliances and authentic ingredients 
                from Italy, Korea, Japan, and beyond. Every product in our collection is hand-picked for its 
                quality, authenticity, and ability to transform everyday cooking.
            </p>
            <p class="text-gray-600 leading-relaxed text-center">
                Today, we proudly serve thousands of customers across Sri Lanka, bringing global flavors 
                and premium kitchen solutions to homes and restaurants.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
            <div class="text-center">
                <div class="text-4xl font-bold text-red-600 mb-2">5000+</div>
                <div class="text-gray-600">Happy Customers</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-red-600 mb-2">50+</div>
                <div class="text-gray-600">Premium Brands</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-red-600 mb-2">100%</div>
                <div class="text-gray-600">Authentic Products</div>
            </div>
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <span class="text-red-600 text-sm uppercase tracking-wider">What We Believe</span>
            <h2 class="text-3xl md:text-4xl font-bold font-['Playfair_Display'] text-gray-900 mt-2">Our Values</h2>
            <div class="w-16 h-px bg-red-600 mx-auto mt-4"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl p-8 text-center shadow-sm">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Authenticity</h3>
                <p class="text-gray-600">Genuine products sourced directly from their country of origin.</p>
            </div>
            
            <div class="bg-white rounded-xl p-8 text-center shadow-sm">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Global Selection</h3>
                <p class="text-gray-600">Curated collection from culinary destinations worldwide.</p>
            </div>
            
            <div class="bg-white rounded-xl p-8 text-center shadow-sm">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Customer First</h3>
                <p class="text-gray-600">Your satisfaction is at the heart of everything we do.</p>
            </div>
        </div>
    </div>
</section>

<!-- What We Offer -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <span class="text-red-600 text-sm uppercase tracking-wider">Our Offerings</span>
            <h2 class="text-3xl md:text-4xl font-bold font-['Playfair_Display'] text-gray-900 mt-2">What We Bring</h2>
            <div class="w-16 h-px bg-red-600 mx-auto mt-4"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <div class="flex gap-5 p-6 border border-gray-100 rounded-xl">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                        <span class="text-2xl">🍜</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Authentic Food</h3>
                    <p class="text-gray-600">Japanese matcha, Korean kimchi, Italian pasta, and more.</p>
                </div>
            </div>
            
            <div class="flex gap-5 p-6 border border-gray-100 rounded-xl">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                        <span class="text-2xl">🔌</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Premium Appliances</h3>
                    <p class="text-gray-600">Rice cookers, kettles from Zojirushi, Tiger, Panasonic.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <span class="text-red-600 text-sm uppercase tracking-wider">Behind the Brand</span>
            <h2 class="text-3xl md:text-4xl font-bold font-['Playfair_Display'] text-gray-900 mt-2">Meet Our Team</h2>
            <div class="w-16 h-px bg-red-600 mx-auto mt-4"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
            <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                <div class="w-24 h-24 bg-red-100 rounded-full mx-auto flex items-center justify-center mb-4">
                    <span class="text-3xl">👨‍🍳</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Kasun Rathnayake</h3>
                <p class="text-red-600 text-sm mb-3">Founder & CEO</p>
                <p class="text-gray-600 text-sm">A culinary enthusiast with a passion for authentic global flavors.</p>
            </div>
            
            <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                <div class="w-24 h-24 bg-red-100 rounded-full mx-auto flex items-center justify-center mb-4">
                    <span class="text-3xl">👩‍🍳</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Amanda Perera</h3>
                <p class="text-red-600 text-sm mb-3">Head of Curation</p>
                <p class="text-gray-600 text-sm">Selects the finest products from around the world.</p>
            </div>
            
            <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                <div class="w-24 h-24 bg-red-100 rounded-full mx-auto flex items-center justify-center mb-4">
                    <span class="text-3xl">📦</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Ruwan Silva</h3>
                <p class="text-red-600 text-sm mb-3">Customer Experience</p>
                <p class="text-gray-600 text-sm">Ensures every customer has a delightful experience.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-red-600">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to Explore?</h2>
        <p class="text-white/90 mb-8 max-w-2xl mx-auto">Discover authentic global flavors and premium kitchen appliances</p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('shop') }}" class="bg-white text-red-600 hover:bg-gray-100 px-8 py-3 rounded-full font-semibold transition">
                Shop Now
            </a>
            <a href="{{ route('contact') }}" class="border-2 border-white text-white hover:bg-white hover:text-red-600 px-8 py-3 rounded-full font-semibold transition">
                Contact Us
            </a>
        </div>
    </div>
</section>

@endsection