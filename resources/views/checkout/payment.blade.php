@extends('layouts.desktop')

@section('title', 'Payment - Ja Lanka')

@section('content')
<div class="container mx-auto px-6 py-16">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Payment Gateway Coming Soon</h1>
            <p class="text-gray-600 mb-6">
                Online card payments are currently under development.<br>
                For now, please use Cash on Delivery.
            </p>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-600">Order #{{ $order->order_number }}</p>
                <p class="text-xl font-bold text-red-600">LKR {{ number_format($order->grand_total, 2) }}</p>
            </div>
            
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('checkout.success', $order->id) }}" class="bg-gray-900 text-white px-6 py-2 rounded-lg hover:bg-gray-800">
                    Continue (Skip Payment)
                </a>
                <a href="{{ route('shop') }}" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-50">
                    Back to Shop
                </a>
            </div>
        </div>
    </div>
</div>
@endsection