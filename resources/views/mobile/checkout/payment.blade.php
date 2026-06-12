@extends('layouts.mobile')

@section('title', 'Payment')

@section('content')
<div class="min-h-screen flex items-center justify-center px-5 py-10">
    <div class="bg-white rounded-xl shadow-sm p-6 text-center w-full max-w-md">
        <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <h1 class="text-xl font-bold text-gray-800 mb-2">Payment Gateway Coming Soon</h1>
        <p class="text-gray-600 mb-6 text-sm">
            Online card payments are currently under development.<br>
            For now, please use Cash on Delivery.
        </p>
        
        <div class="bg-gray-50 rounded-xl p-4 mb-6">
            <p class="text-sm text-gray-600">Order #{{ $order->order_number }}</p>
            <p class="text-lg font-bold text-red-600">LKR {{ number_format($order->grand_total, 2) }}</p>
        </div>
        
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="{{ route('mobile.checkout.success', $order->id) }}" class="bg-gray-900 text-white px-5 py-2 rounded-xl text-sm font-semibold">
                Continue (Skip Payment)
            </a>
            <a href="{{ route('mobile.shop') }}" class="border border-gray-300 text-gray-700 px-5 py-2 rounded-xl text-sm font-semibold">
                Back to Shop
            </a>
        </div>
    </div>
</div>
@endsection