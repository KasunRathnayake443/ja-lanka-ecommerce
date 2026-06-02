@extends('layouts.mobile')

@section('title', 'Home')

@section('content')
<div class="p-4">
    <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-xl p-6 text-white mb-6">
        <h2 class="text-xl font-bold mb-2">Welcome to Ja Lanka</h2>
        <p class="text-sm opacity-90">Global flavors delivered to your door</p>
    </div>
    
    <div class="grid grid-cols-2 gap-4">
        <a href="{{ route('mobile.shop') }}?type=food" class="bg-green-50 rounded-xl p-4 text-center">
            <span class="text-3xl">🍜</span>
            <p class="font-semibold mt-1">Food Items</p>
        </a>
        <a href="{{ route('mobile.shop') }}?type=appliance" class="bg-blue-50 rounded-xl p-4 text-center">
            <span class="text-3xl">🔌</span>
            <p class="font-semibold mt-1">Appliances</p>
        </a>
    </div>
    
    <div class="mt-6 text-center text-gray-500 text-sm">
        <p>Products will appear here once added to database</p>
    </div>
</div>
@endsection