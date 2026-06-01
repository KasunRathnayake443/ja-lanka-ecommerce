@extends('layouts.app')

@section('title', 'Global Flavors Mart - Italian, Korean, Japanese & More')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-red-700 to-orange-600 rounded-2xl p-8 text-white mb-12">
        <h1 class="text-3xl md:text-5xl font-bold mb-4">Welcome to Ja Lanka</h1>
        <p class="text-lg md:text-xl mb-4">Your passport to international cuisine!</p>
        <p class="text-md mb-6">Discover authentic Italian pastas, Korean kimchi, Japanese matcha, and more from around the world.</p>
        <a href="{{ route('products.index') }}" class="inline-block bg-white text-red-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
            Start Your Culinary Journey →
        </a>
    </div>
    
    <!-- Cuisine Categories Grid -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Shop by Cuisine</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @php
                $cuisines = [
                    ['name' => 'Italian', 'icon' => '🇮🇹', 'color' => 'bg-green-50', 'route' => 'italian'],
                    ['name' => 'Korean', 'icon' => '🇰🇷', 'color' => 'bg-red-50', 'route' => 'korean'],
                    ['name' => 'Japanese', 'icon' => '🇯🇵', 'color' => 'bg-pink-50', 'route' => 'japanese'],
                    ['name' => 'Asian', 'icon' => '🌏', 'color' => 'bg-yellow-50', 'route' => 'asian'],
                    ['name' => 'Appliances', 'icon' => '🔌', 'color' => 'bg-blue-50', 'route' => 'appliances'],
                ];
            @endphp
            
            @foreach($cuisines as $cuisine)
            <a href="#" class="{{ $cuisine['color'] }} rounded-2xl p-6 text-center hover:shadow-lg transition transform hover:-translate-y-1">
                <div class="text-5xl mb-3">{{ $cuisine['icon'] }}</div>
                <h3 class="font-semibold text-gray-800">{{ $cuisine['name'] }}</h3>
            </a>
            @endforeach
        </div>
    </div>
    
    <!-- Featured Products Section -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Featured Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Product cards will be dynamic -->
            <div class="bg-white rounded-lg shadow p-4 text-center border">
                <div class="text-4xl mb-3">🍝</div>
                <p class="font-semibold">Italian Pasta</p>
                <p class="text-gray-500 text-sm">Coming soon...</p>
            </div>
        </div>
    </div>
    
    <!-- Cuisine Highlights -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        <div class="bg-gradient-to-r from-green-100 to-green-200 rounded-2xl p-6">
            <div class="text-4xl mb-3">🇮🇹</div>
            <h3 class="text-xl font-bold mb-2">Italian Specialties</h3>
            <p class="text-gray-700">Authentic pasta, premium olive oils, aged balsamic, and traditional sauces direct from Italy.</p>
        </div>
        
        <div class="bg-gradient-to-r from-red-100 to-red-200 rounded-2xl p-6">
            <div class="text-4xl mb-3">🇰🇷</div>
            <h3 class="text-xl font-bold mb-2">Korean Favorites</h3>
            <p class="text-gray-700">Kimchi, Gochujang, instant ramyeon, and Korean BBQ sauces for authentic K-food.</p>
        </div>
        
        <div class="bg-gradient-to-r from-pink-100 to-pink-200 rounded-2xl p-6">
            <div class="text-4xl mb-3">🇯🇵</div>
            <h3 class="text-xl font-bold mb-2">Japanese Essentials</h3>
            <p class="text-gray-700">Sushi-grade nori, premium matcha, udon noodles, and traditional seasonings.</p>
        </div>
        
        <div class="bg-gradient-to-r from-blue-100 to-blue-200 rounded-2xl p-6">
            <div class="text-4xl mb-3">🔌</div>
            <h3 class="text-xl font-bold mb-2">Kitchen Appliances</h3>
            <p class="text-gray-700">Japanese rice cookers, electric kettles, and air fryers for your global kitchen.</p>
        </div>
    </div>
</div>
@endsection