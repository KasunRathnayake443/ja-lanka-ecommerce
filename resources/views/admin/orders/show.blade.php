@extends('admin.layouts.app')

@section('page_title', 'Order Details')

@section('content')
<div class="space-y-6">
    
    <!-- Order Header -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold">Order #{{ $order->order_number }}</h2>
                <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('F d, Y h:i A') }}</p>
            </div>
            <a href="{{ route('admin.orders.invoice', $order->id) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">
                Download Invoice
            </a>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Customer Info -->
            <div>
                <h3 class="font-semibold text-gray-800 mb-2">Customer Information</h3>
                @if($order->user)
                    <p class="text-sm">{{ $order->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                    <p class="text-sm text-gray-600">{{ $order->user->mobile ?? 'No phone' }}</p>
                @else
                    <p class="text-sm text-gray-500">Guest Customer</p>
                @endif
            </div>
            
            <!-- Shipping Address -->
            <div>
                <h3 class="font-semibold text-gray-800 mb-2">Shipping Address</h3>
                @if($order->shippingAddress)
                    <p class="text-sm">{{ $order->shippingAddress->full_name }}</p>
                    <p class="text-sm text-gray-600">{{ $order->shippingAddress->mobile }}</p>
                    <p class="text-sm text-gray-600">{{ $order->shippingAddress->address_line1 }}</p>
                    <p class="text-sm text-gray-600">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->district }}</p>
                @else
                    <p class="text-sm text-gray-500">No address</p>
                @endif
            </div>
            
            <!-- Order Status Update -->
            <div>
                <h3 class="font-semibold text-gray-800 mb-2">Update Status</h3>
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-2">
                    @csrf
                    <select name="order_status" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Tracking Number" class="w-full px-3 py-2 border rounded-lg text-sm">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm hover:bg-blue-700">Update Status</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Order Items -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-semibold">Order Items</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">SKU</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Quantity</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Unit Price</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($item->product_image)
                                    <img src="{{ asset('storage/' . $item->product_image) }}" class="w-10 h-10 object-cover rounded">
                                @endif
                                <span class="font-medium">{{ $item->product_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->product_sku }}</td>
                        <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 text-right">LKR {{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-6 py-4 text-right font-semibold">LKR {{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-6 py-3 text-right font-semibold">Subtotal:</td>
                        <td class="px-6 py-3 text-right">LKR {{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr>
                        <td colspan="4" class="px-6 py-3 text-right font-semibold text-green-600">Discount:</td>
                        <td class="px-6 py-3 text-right text-green-600">-LKR {{ number_format($order->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="4" class="px-6 py-3 text-right font-semibold">Shipping:</td>
                        <td class="px-6 py-3 text-right">LKR {{ number_format($order->shipping_amount, 2) }}</td>
                    </tr>
                    <tr class="border-t">
                        <td colspan="4" class="px-6 py-3 text-right font-bold text-lg">Total:</td>
                        <td class="px-6 py-3 text-right font-bold text-lg text-red-600">LKR {{ number_format($order->grand_total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <!-- Payment Info -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-semibold">Payment Information</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600">Payment Method:</p>
                <p class="font-medium">{{ ucfirst($order->payment_method) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Payment Status:</p>
                <form action="{{ route('admin.orders.update-payment', $order->id) }}" method="POST" class="inline">
                    @csrf
                    <select name="payment_status" class="px-3 py-1 border rounded-lg text-sm">
                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                    <button type="submit" class="ml-2 bg-gray-600 text-white px-3 py-1 rounded-lg text-sm">Update</button>
                </form>
            </div>
            @if($order->coupon_code)
            <div>
                <p class="text-sm text-gray-600">Coupon Applied:</p>
                <p class="font-medium">{{ $order->coupon_code }} (-LKR {{ number_format($order->coupon_discount, 2) }})</p>
            </div>
            @endif
        </div>
    </div>
    
</div>
@endsection