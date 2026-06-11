@extends('layouts.mobile')

@section('title', 'My Orders')

@section('content')
<div class="pb-20">
    
    <!-- Header -->
    <div class="bg-white border-b border-gray-100 px-5 py-4 sticky top-0 z-10">
        <div class="flex items-center gap-3">
            <a href="{{ route('mobile.account.dashboard') }}" class="text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800">My Orders</h1>
        </div>
    </div>
    
    <!-- Orders List -->
    <div class="p-4 space-y-4">
        @forelse($orders as $order)
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="font-semibold text-gray-800">#{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-500">{{ $order->created_at->format('F d, Y') }}</p>
                </div>
                <span class="px-2 py-1 text-xs rounded-full 
                    {{ $order->order_status == 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $order->order_status == 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $order->order_status == 'shipped' ? 'bg-purple-100 text-purple-700' : '' }}
                    {{ $order->order_status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                    {{ ucfirst($order->order_status) }}
                </span>
            </div>
            
            <div class="space-y-2 mb-3">
                @foreach($order->items->take(2) as $item)
                <div class="flex gap-3">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden">
                        @if($item->product_image)
                            <img src="{{ asset('storage/' . $item->product_image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">📦</div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">{{ $item->product_name }}</p>
                        <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-800">LKR {{ number_format($item->total_price, 2) }}</p>
                </div>
                @endforeach
                @if($order->items->count() > 2)
                <p class="text-xs text-gray-500 text-center">+{{ $order->items->count() - 2 }} more items</p>
                @endif
            </div>
            
            <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                <div>
                    <p class="text-xs text-gray-500">Total Amount</p>
                    <p class="text-lg font-bold text-red-600">LKR {{ number_format($order->grand_total, 2) }}</p>
                </div>
                <a href="{{ route('mobile.account.order.detail', $order->id) }}" class="text-sm text-red-600 font-medium">View Details →</a>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <p class="text-gray-500">No orders yet</p>
            <a href="{{ route('mobile.shop') }}" class="inline-block mt-3 text-red-600">Start Shopping →</a>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="px-4 pb-4">
        {{ $orders->links() }}
    </div>
    @endif
    
</div>
@endsection