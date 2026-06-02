@extends('layouts.desktop')

@section('title', 'Home')

@section('content')
<div class="container mx-auto px-6 py-12">
    <h1 class="text-4xl font-bold text-center mb-4">Welcome to Ja Lanka</h1>
    <p class="text-center text-gray-600 mb-12">Your destination for global flavors and quality appliances</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <a href="{{ route('products.food') }}" class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-8 text-center text-white hover:shadow-lg transition">
            <div class="text-6xl mb-4">🍜</div>
            <h2 class="text-2xl font-bold">Food Items</h2>
            <p class="mt-2">Japanese, Korean, Italian & more</p>
        </a>
        
        <a href="{{ route('products.appliance') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-8 text-center text-white hover:shadow-lg transition">
            <div class="text-6xl mb-4">🔌</div>
            <h2 class="text-2xl font-bold">Appliances</h2>
            <p class="mt-2">Premium kitchen appliances</p>
        </a>
    </div>
</div>
@endsection