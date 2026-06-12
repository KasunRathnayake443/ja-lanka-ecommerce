@extends('admin.layouts.app')

@section('page_title', 'Dashboard')

@section('content')
<!-- Stats Cards Row 1 -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm uppercase tracking-wide">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">LKR {{ number_format($thisMonthSales, 2) }}</p>
                <p class="text-xs text-gray-400 mt-1">This month</p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm uppercase tracking-wide">Total Orders</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $thisMonthOrders }}</p>
                <p class="text-xs text-green-600 mt-1">{{ $ordersGrowth > 0 ? '+' : '' }}{{ $ordersGrowth }}% vs last month</p>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm uppercase tracking-wide">Total Customers</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalCustomers }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $thisMonthCustomers }} new this month</p>
            </div>
            <div class="bg-purple-100 rounded-full p-3">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm uppercase tracking-wide">Products</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalProducts }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $lowStockProducts->count() }} low stock</p>
            </div>
            <div class="bg-orange-100 rounded-full p-3">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards Row 2 -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800">Today's Overview</h3>
            <span class="text-xs text-gray-400">{{ now()->format('M d, Y') }}</span>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Orders:</span>
                <span class="font-semibold">{{ $todayOrders }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Sales:</span>
                <span class="font-semibold">LKR {{ number_format($todaySales, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">New Customers:</span>
                <span class="font-semibold">{{ $todayCustomers }}</span>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Order Status</h3>
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Pending:</span>
                <span class="font-semibold text-yellow-600">{{ $orderStatuses['pending'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Processing:</span>
                <span class="font-semibold text-blue-600">{{ $orderStatuses['processing'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Shipped:</span>
                <span class="font-semibold text-purple-600">{{ $orderStatuses['shipped'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Delivered:</span>
                <span class="font-semibold text-green-600">{{ $orderStatuses['delivered'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Cancelled:</span>
                <span class="font-semibold text-red-600">{{ $orderStatuses['cancelled'] }}</span>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Payment Summary</h3>
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Paid:</span>
                <span class="font-semibold text-green-600">LKR {{ number_format($paymentStatuses['paid'], 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Pending:</span>
                <span class="font-semibold text-yellow-600">{{ $paymentStatuses['pending'] }} orders</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Failed:</span>
                <span class="font-semibold text-red-600">{{ $paymentStatuses['failed'] }} orders</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Refunded:</span>
                <span class="font-semibold text-orange-600">{{ $paymentStatuses['refunded'] }} orders</span>
            </div>
        </div>
    </div>
</div>

<!-- Weekly Sales Chart -->
<div class="bg-white rounded-xl shadow-md p-6 mb-8">
    <h3 class="font-semibold text-gray-800 mb-6">Last 7 Days Sales</h3>
    <div class="relative">
        <div class="flex items-end justify-between gap-2 h-64">
            @foreach($weeklySales as $day)
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full relative" style="height: 200px;">
                    <div class="absolute bottom-0 left-0 right-0 bg-blue-500 rounded-t-lg transition-all duration-300 hover:bg-blue-600" 
                         style="height: {{ ($day['sales'] / max(collect($weeklySales)->max('sales'), 1)) * 100 }}%;">
                        <div class="absolute -top-7 left-0 right-0 text-center">
                            <span class="text-xs font-semibold text-gray-700 bg-white px-1 rounded">
                                LKR {{ number_format($day['sales'], 0) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-xs font-medium text-gray-600">{{ $day['day'] }}</div>
                <div class="text-xs text-gray-400">{{ $day['orders'] }} order(s)</div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Chart Legend -->
    <div class="flex justify-center gap-6 mt-6 pt-4 border-t border-gray-100">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-blue-500 rounded"></div>
            <span class="text-xs text-gray-500">Sales Amount</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-gray-300 rounded"></div>
            <span class="text-xs text-gray-500">Order Count below bar</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Recent Orders -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-red-600 hover:underline">View All</a>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($recentOrders as $order)
            <div class="px-6 py-4 flex justify-between items-center">
                <div>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="font-medium text-blue-600 hover:underline">
                        {{ $order->order_number }}
                    </a>
                    <div class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">LKR {{ number_format($order->grand_total, 2) }}</div>
                    <span class="text-xs px-2 py-0.5 rounded-full 
                        {{ $order->order_status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $order->order_status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $order->order_status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                        {{ ucfirst($order->order_status) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-500">No orders yet</div>
            @endforelse
        </div>
    </div>
    
    <!-- Top Selling Products -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="font-semibold text-gray-800">Top Selling Products (30 days)</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($topProducts as $product)
            <div class="px-6 py-4 flex justify-between items-center">
                <div>
                    <div class="font-medium text-gray-800">{{ $product->name }}</div>
                    <div class="text-xs text-gray-500">SKU: {{ $product->sku }}</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold text-green-600">{{ $product->total_sold }} sold</div>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-500">No sales data yet</div>
            @endforelse
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Low Stock Products -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">⚠️ Low Stock Alert</h3>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-red-600 hover:underline">Manage Products</a>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($lowStockProducts as $product)
            <div class="px-6 py-4 flex justify-between items-center">
                <div>
                    <div class="font-medium text-gray-800">{{ $product->name }}</div>
                    <div class="text-xs text-gray-500">SKU: {{ $product->sku }}</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold text-orange-600">{{ $product->inventory->quantity_on_hand }} left</div>
                    <div class="text-xs text-gray-500">Reorder at: {{ $product->inventory->reorder_level }}</div>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-500">All products have sufficient stock</div>
            @endforelse
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Quick Stats</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-gray-800">{{ $totalCategories }}</div>
                <div class="text-xs text-gray-500">Categories</div>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-gray-800">{{ $totalBrands }}</div>
                <div class="text-xs text-gray-500">Brands</div>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-gray-800">{{ $totalCoupons }}</div>
                <div class="text-xs text-gray-500">Active Coupons</div>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-gray-800">{{ number_format($thisMonthSales / max($thisMonthOrders, 1), 0) }}</div>
                <div class="text-xs text-gray-500">Avg. Order Value</div>
            </div>
        </div>
    </div>
</div>
@endsection