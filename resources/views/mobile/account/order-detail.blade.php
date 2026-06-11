@extends('layouts.mobile')

@section('title', 'Order Details')

@section('content')
<div class="pb-20">
    
    <!-- Header -->
    <div class="bg-white border-b border-gray-100 px-5 py-4 sticky top-0 z-10">
        <div class="flex items-center gap-3">
            <a href="{{ route('mobile.account.orders') }}" class="text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Order Details</h1>
        </div>
    </div>
    
    <div class="p-4 space-y-4">
        <!-- Order Info -->
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-sm text-gray-500">Order #</p>
                    <p class="font-semibold text-gray-800">{{ $order->order_number }}</p>
                </div>
                <span class="px-2 py-1 text-xs rounded-full 
                    {{ $order->order_status == 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $order->order_status == 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $order->order_status == 'shipped' ? 'bg-purple-100 text-purple-700' : '' }}
                    {{ $order->order_status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                    {{ ucfirst($order->order_status) }}
                </span>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Placed on</span>
                    <span class="text-sm text-gray-800">{{ $order->created_at->format('F d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Payment Method</span>
                    <span class="text-sm text-gray-800">{{ ucfirst($order->payment_method) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Payment Status</span>
                    <span class="text-sm {{ $order->payment_status == 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Shipping Address -->
        @if($order->shippingAddress)
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-800 mb-2">Shipping Address</h3>
            <p class="text-sm text-gray-600">{{ $order->shippingAddress->full_name }}</p>
            <p class="text-sm text-gray-600">{{ $order->shippingAddress->mobile }}</p>
            <p class="text-sm text-gray-600">{{ $order->shippingAddress->address_line1 }}</p>
            @if($order->shippingAddress->address_line2)
                <p class="text-sm text-gray-600">{{ $order->shippingAddress->address_line2 }}</p>
            @endif
            <p class="text-sm text-gray-600">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->district }}</p>
        </div>
        @endif
        
        <!-- Order Items -->
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-800 mb-3">Order Items</h3>
            <div class="space-y-3">
                @foreach($order->items as $item)
                <div class="flex gap-3 pb-3 border-b border-gray-100 last:border-0">
                    <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        @if($item->product_image)
                            <img src="{{ asset('storage/' . $item->product_image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">📦</div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800 text-sm">{{ $item->product_name }}</p>
                        <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                        <p class="text-sm font-semibold text-gray-800 mt-1">LKR {{ number_format($item->total_price, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-800 mb-3">Order Summary</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Subtotal</span>
                    <span class="text-sm text-gray-800">LKR {{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Discount</span>
                    <span class="text-sm text-red-600">-LKR {{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Shipping</span>
                    <span class="text-sm text-gray-800">LKR {{ number_format($order->shipping_amount, 2) }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-100">
                    <span class="font-semibold text-gray-800">Total</span>
                    <span class="font-bold text-red-600 text-lg">LKR {{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Order Timeline -->
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-800 mb-3">Order Timeline</h3>
            <div class="space-y-3">
                <div class="flex gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 text-sm">Order Placed</p>
                        <p class="text-xs text-gray-500">{{ $order->created_at->format('F d, Y - h:i A') }}</p>
                    </div>
                </div>
                
                @if($order->order_status != 'pending')
                <div class="flex gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 text-sm">Processing</p>
                        <p class="text-xs text-gray-500">Your order is being processed</p>
                    </div>
                </div>
                @endif
                
                @if($order->order_status == 'shipped' || $order->order_status == 'delivered')
                <div class="flex gap-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 text-sm">Shipped</p>
                        @if($order->tracking_number)
                            <p class="text-xs text-gray-500">Tracking: {{ $order->tracking_number }}</p>
                        @endif
                    </div>
                </div>
                @endif
                
                @if($order->order_status == 'delivered')
                <div class="flex gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 text-sm">Delivered</p>
                        <p class="text-xs text-gray-500">Your order has been delivered</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
</div>
@endsection