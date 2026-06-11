@extends('admin.layouts.app')

@section('page_title', 'Customers')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex flex-wrap justify-between items-center gap-4">
        <div>
            <h2 class="text-xl font-semibold">Customer Management</h2>
            <p class="text-sm text-gray-500">Manage your customers</p>
        </div>
        
        <!-- Statistics Cards -->
        <div class="flex gap-4 text-sm">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-800">{{ $totalCustomers }}</div>
                <div class="text-xs text-gray-500">Total</div>
            </div>
            <div class="text-center border-l pl-4">
                <div class="text-2xl font-bold text-green-600">{{ $activeCustomers }}</div>
                <div class="text-xs text-gray-500">Active</div>
            </div>
            <div class="text-center border-l pl-4">
                <div class="text-2xl font-bold text-red-600">{{ $inactiveCustomers }}</div>
                <div class="text-xs text-gray-500">Blocked</div>
            </div>
            <div class="text-center border-l pl-4">
                <div class="text-2xl font-bold text-blue-600">{{ $newThisMonth }}</div>
                <div class="text-xs text-gray-500">New This Month</div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="px-6 py-4 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, mobile..." 
                       class="px-3 py-1.5 border rounded-lg text-sm w-64">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="px-3 py-1.5 border rounded-lg text-sm">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-1.5 border rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-3 py-1.5 border rounded-lg text-sm">
            </div>
            <div>
                <button type="submit" class="bg-gray-900 text-white px-4 py-1.5 rounded-lg text-sm">Filter</button>
                <a href="{{ route('admin.customers.index') }}" class="text-gray-500 text-sm ml-2 hover:underline">Reset</a>
            </div>
        </form>
    </div>
    
    <!-- Customers Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Orders</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Total Spent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                </td>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($customers as $customer)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($customer->profile_photo)
                                <img src="{{ asset('storage/' . $customer->profile_photo) }}" class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold text-red-600">{{ substr($customer->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                                <div class="text-xs text-gray-500">{{ $customer->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600">{{ $customer->mobile ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600">{{ $customer->created_at->format('M d, Y') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-gray-900">{{ $customer->orders_count ?? 0 }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-gray-900">
                            LKR {{ number_format($customer->orders_sum_grand_total ?? 0, 2) }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $customer->is_active ? 'Active' : 'Blocked' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="text-green-600 hover:text-green-900 mr-2">Edit</a>
                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this customer?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        No customers found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t">
        {{ $customers->withQueryString()->links() }}
    </div>
</div>
@endsection