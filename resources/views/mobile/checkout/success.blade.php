@extends('layouts.mobile')

@section('title', 'Order Success')

@section('content')
<div class="min-h-screen flex items-center justify-center px-5 py-10">
    <div class="text-center">
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Order Confirmed!</h1>
        <p class="text-gray-600 mb-6">Thank you for your purchase</p>
        
        <div class="bg-gray-50 rounded-xl p-6 mb-6">
            <p class="text-sm text-gray-500 mb-2">Order Number</p>
            <p class="text-xl font-bold text-gray-800">{{ $order->order_number }}</p>
            <p class="text-sm text-gray-500 mt-4">A confirmation email has been sent to your registered email address.</p>
        </div>
        
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('mobile.account.orders') }}" class="bg-gray-900 text-white px-6 py-3 rounded-xl font-semibold">
                My Orders
            </a>
            <a href="{{ route('mobile.shop') }}" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-semibold">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection