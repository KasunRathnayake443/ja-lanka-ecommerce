@extends('admin.layouts.app')

@section('page_title', 'Customer Details')

@section('content')
<div class="space-y-6">
    
    <!-- Customer Profile Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <h2 class="text-xl font-semibold">Customer Profile</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.customers.edit', $customer->id) }}" class="bg-green-600 text-white px-4 py-1 rounded-lg text-sm hover:bg-green-700">Edit Profile</a>
                @if($customer->is_active)
                    <a href="{{ route('admin.customers.toggle-status', $customer->id) }}" class="bg-red-600 text-white px-4 py-1 rounded-lg text-sm hover:bg-red-700" onclick="return confirm('Block this customer?')">Block Customer</a>
                @else
                    <a href="{{ route('admin.customers.toggle-status', $customer->id) }}" class="bg-green-600 text-white px-4 py-1 rounded-lg text-sm hover:bg-green-700" onclick="return confirm('Activate this customer?')">Activate Customer</a>
                @endif
                <a href="{{ route('admin.customers.impersonate', $customer->id) }}" class="bg-blue-600 text-white px-4 py-1 rounded-lg text-sm hover:bg-blue-700">Login as Customer</a>
            </div>
        </div>
        
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Profile Photo -->
                <div class="text-center">
                    @if($customer->profile_photo)
                        <img src="{{ asset('storage/' . $customer->profile_photo) }}" class="w-32 h-32 rounded-full object-cover mx-auto">
                    @else
                        <div class="w-32 h-32 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                            <span class="text-4xl font-bold text-red-600">{{ substr($customer->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                
                <!-- Customer Info -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-500 uppercase">Full Name</label>
                        <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase">Email Address</label>
                        <p class="font-medium text-gray-900">{{ $customer->email }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase">Mobile Number</label>
                        <p class="font-medium text-gray-900">{{ $customer->mobile ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase">Account Status</label>
                        <p><span class="px-2 py-1 text-xs rounded-full {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $customer->is_active ? 'Active' : 'Blocked' }}</span></p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase">Member Since</label>
                        <p class="font-medium text-gray-900">{{ $customer->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase">Last Update</label>
                        <p class="font-medium text-gray-900">{{ $customer->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="text-2xl font-bold text-blue-600">{{ $totalOrders }}</div>
            <div class="text-sm text-gray-500">Total Orders</div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="text-2xl font-bold text-green-600">LKR {{ number_format($totalSpent, 2) }}</div>
            <div class="text-sm text-gray-500">Total Spent</div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="text-2xl font-bold text-purple-600">{{ $wishlistCount }}</div>
            <div class="text-sm text-gray-500">Wishlist Items</div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="text-2xl font-bold text-orange-600">{{ $addressCount }}</div>
            <div class="text-sm text-gray-500">Saved Addresses</div>
        </div>
    </div>
    
    <!-- Reset Password Form -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-semibold">Reset Password</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.customers.reset-password', $customer->id) }}" method="POST" class="max-w-md">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">Reset Password</button>
            </form>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-semibold">Order History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Payment</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:underline">#{{ $order->order_number }}</a>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm">{{ $order->items->sum('quantity') }}</td>
                        <td class="px-6 py-4 text-sm font-semibold">LKR {{ number_format($order->grand_total, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $order->order_status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->order_status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->order_status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No orders yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Addresses -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-semibold">Saved Addresses</h3>
        </div>
        <div class="p-6">
            @if($customer->addresses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($customer->addresses as $address)
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="font-semibold">{{ $address->label }}</span>
                            @if($address->is_default)
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">Default</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600">{{ $address->full_name }}</p>
                        <p class="text-sm text-gray-600">{{ $address->mobile }}</p>
                        <p class="text-sm text-gray-600">{{ $address->address_line1 }}</p>
                        <p class="text-sm text-gray-600">{{ $address->city }}, {{ $address->district }}</p>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 py-8">No saved addresses</p>
            @endif
        </div>
    </div>
</div>
@endsection