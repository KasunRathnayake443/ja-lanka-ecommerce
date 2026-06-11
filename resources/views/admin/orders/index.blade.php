@extends('admin.layouts.app')

@section('page_title', 'Orders')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <div>
            <h2 class="text-xl font-semibold">Order Management</h2>
            <p class="text-sm text-gray-500">Manage customer orders</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3 p-4 border-b">
        <div class="text-center p-2 bg-gray-50 rounded-lg">
            <div class="text-xl font-bold text-gray-800">{{ $totalOrders }}</div>
            <div class="text-xs text-gray-500">Total</div>
        </div>
        <div class="text-center p-2 bg-yellow-50 rounded-lg">
            <div class="text-xl font-bold text-yellow-600">{{ $pendingOrders }}</div>
            <div class="text-xs text-gray-500">Pending</div>
        </div>
        <div class="text-center p-2 bg-blue-50 rounded-lg">
            <div class="text-xl font-bold text-blue-600">{{ $processingOrders }}</div>
            <div class="text-xs text-gray-500">Processing</div>
        </div>
        <div class="text-center p-2 bg-purple-50 rounded-lg">
            <div class="text-xl font-bold text-purple-600">{{ $shippedOrders }}</div>
            <div class="text-xs text-gray-500">Shipped</div>
        </div>
        <div class="text-center p-2 bg-green-50 rounded-lg">
            <div class="text-xl font-bold text-green-600">{{ $deliveredOrders }}</div>
            <div class="text-xs text-gray-500">Delivered</div>
        </div>
        <div class="text-center p-2 bg-red-50 rounded-lg">
            <div class="text-xl font-bold text-red-600">{{ $cancelledOrders }}</div>
            <div class="text-xs text-gray-500">Cancelled</div>
        </div>
        <div class="text-center p-2 bg-green-50 rounded-lg">
            <div class="text-xl font-bold text-green-600">LKR {{ number_format($totalRevenue, 0) }}</div>
            <div class="text-xs text-gray-500">Revenue</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="px-6 py-4 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #" 
                       class="px-3 py-1.5 border rounded-lg text-sm w-48">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Order Status</label>
                <select name="status" class="px-3 py-1.5 border rounded-lg text-sm">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Payment Status</label>
                <select name="payment_status" class="px-3 py-1.5 border rounded-lg text-sm">
                    <option value="">All</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-gray-900 text-white px-4 py-1.5 rounded-lg text-sm">Filter</button>
                <a href="{{ route('admin.orders.index') }}" class="text-gray-500 text-sm ml-2 hover:underline">Reset</a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Payment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:underline font-medium">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        @if($order->user)
                            {{ $order->user->name }}
                        @else
                            <span class="text-gray-400">Guest</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 font-semibold">LKR {{ number_format($order->grand_total, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $order->order_status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->order_status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->order_status == 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $order->order_status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->order_status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">No orders found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection