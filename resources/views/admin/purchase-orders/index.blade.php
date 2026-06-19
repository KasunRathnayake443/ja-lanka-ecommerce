@extends('admin.layouts.app')

@section('page_title', 'Purchase Orders')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Purchase Orders</h2>
            <p class="text-sm text-gray-500">Manage supplier purchase orders</p>
        </div>
        <a href="{{ route('admin.purchase-orders.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Purchase Order
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 p-4 border-b">
        <div class="text-center p-2 bg-gray-50 rounded-lg">
            <div class="text-xl font-bold text-gray-800">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-500">Total</div>
        </div>
        <div class="text-center p-2 bg-gray-100 rounded-lg">
            <div class="text-xl font-bold text-gray-600">{{ $stats['draft'] }}</div>
            <div class="text-xs text-gray-500">Draft</div>
        </div>
        <div class="text-center p-2 bg-blue-50 rounded-lg">
            <div class="text-xl font-bold text-blue-600">{{ $stats['sent'] }}</div>
            <div class="text-xs text-gray-500">Sent</div>
        </div>
        <div class="text-center p-2 bg-green-50 rounded-lg">
            <div class="text-xl font-bold text-green-600">{{ $stats['received'] }}</div>
            <div class="text-xs text-gray-500">Received</div>
        </div>
        <div class="text-center p-2 bg-red-50 rounded-lg">
            <div class="text-xl font-bold text-red-600">{{ $stats['cancelled'] }}</div>
            <div class="text-xs text-gray-500">Cancelled</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="px-6 py-4 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="PO # or Supplier" 
                       class="px-3 py-1.5 border rounded-lg text-sm w-48">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="px-3 py-1.5 border rounded-lg text-sm">
                    <option value="">All</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Supplier</label>
                <select name="supplier" class="px-3 py-1.5 border rounded-lg text-sm">
                    <option value="">All</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="bg-gray-900 text-white px-4 py-1.5 rounded-lg text-sm">Filter</button>
                <a href="{{ route('admin.purchase-orders.index') }}" class="text-gray-500 text-sm ml-2 hover:underline">Reset</a>
            </div>
        </form>
    </div>

    <!-- Purchase Orders Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">PO #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Supplier</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Total</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Items</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($purchaseOrders as $po)
                <tr>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.purchase-orders.show', $po->id) }}" class="text-blue-600 hover:underline font-medium">
                            {{ $po->po_number }}
                        </a>
                        @if($po->restockRequest)
                            <div class="text-xs text-gray-400">From: {{ $po->restockRequest->request_number }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $po->supplier->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $po->order_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-right font-medium">LKR {{ number_format($po->grand_total, 2) }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs rounded-full bg-{{ $po->status_color }}-100 text-{{ $po->status_color }}-800">
                            {{ $po->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm">
                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">
                            {{ $po->items->count() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.purchase-orders.show', $po->id) }}" class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                        @if($po->status == 'draft')
                            <a href="{{ route('admin.purchase-orders.edit', $po->id) }}" class="text-amber-600 hover:text-amber-900 text-sm">Edit</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">No purchase orders found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t">
        {{ $purchaseOrders->withQueryString()->links() }}
    </div>
</div>
@endsection