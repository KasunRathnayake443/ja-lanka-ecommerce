@extends('layouts.desktop')

@section('title', 'Order Details - Ja Lanka')

@section('content')
<div class="max-w-[1400px] mx-auto px-5 md:px-10 py-10">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Sidebar -->
        <aside class="lg:w-80">
            @include('account.partials.sidebar')
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1">
            <div class="bg-white border border-gray-100 rounded-lg p-6">
                <div class="flex flex-wrap justify-between items-center mb-6">
                    <h2 class="text-2xl font-light font-['Cormorant_Garamond']">Order #{{ $order->order_number }}</h2>
                    <span class="px-3 py-1 text-sm rounded-full 
                        {{ $order->order_status == 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $order->order_status == 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $order->order_status == 'shipped' ? 'bg-purple-100 text-purple-700' : '' }}
                        {{ $order->order_status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                        {{ ucfirst($order->order_status) }}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Order Information</h3>
                        <p class="text-sm text-gray-600">Date: {{ $order->created_at->format('F d, Y') }}</p>
                        <p class="text-sm text-gray-600">Payment Method: {{ ucfirst($order->payment_method) }}</p>
                        <p class="text-sm text-gray-600">Payment Status: {{ ucfirst($order->payment_status) }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Shipping Address</h3>
                        @if($order->shippingAddress)
                            <p class="text-sm text-gray-600">{{ $order->shippingAddress->full_name }}</p>
                            <p class="text-sm text-gray-600">{{ $order->shippingAddress->mobile }}</p>
                            <p class="text-sm text-gray-600">{{ $order->shippingAddress->address_line1 }}</p>
                            @if($order->shippingAddress->address_line2)
                                <p class="text-sm text-gray-600">{{ $order->shippingAddress->address_line2 }}</p>
                            @endif
                            <p class="text-sm text-gray-600">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->district }}</p>
                        @else
                            <p class="text-sm text-gray-500">Address not available</p>
                        @endif
                    </div>
                </div>
                
                <h3 class="font-semibold text-gray-900 mb-4">Order Items</h3>
                <div class="border border-gray-100 rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Product</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Quantity</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Price</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($item->product_image)
                                            <img src="{{ asset('storage/' . $item->product_image) }}" class="w-12 h-12 object-cover rounded">
                                        @endif
                                        <span class="text-sm text-gray-800">{{ $item->product_name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-600">LKR {{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-800">LKR {{ number_format($item->total_price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-sm text-gray-600">Subtotal:</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold">LKR {{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-sm text-gray-600">Discount:</td>
                                <td class="px-4 py-3 text-right text-sm text-red-600">-LKR {{ number_format($order->discount_amount, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-sm text-gray-600">Shipping:</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold">LKR {{ number_format($order->shipping_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-base font-bold text-gray-900">Grand Total:</td>
                                <td class="px-4 py-3 text-right text-base font-bold text-red-700">LKR {{ number_format($order->grand_total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="mt-6">
                    <a href="{{ route('account.orders') }}" class="text-sm text-red-700 hover:underline">← Back to Orders</a>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection