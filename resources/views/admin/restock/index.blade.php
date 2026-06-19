@extends('admin.layouts.app')

@section('page_title', 'Restock Requests')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Restock Requests</h2>
            <p class="text-sm text-gray-500">Manage product restock requests</p>
        </div>
        <a href="{{ route('admin.restock.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Request
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-3 p-4 border-b">
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
        <div class="text-center p-2 bg-indigo-50 rounded-lg">
            <div class="text-xl font-bold text-indigo-600">{{ $stats['acknowledged'] }}</div>
            <div class="text-xs text-gray-500">Ack.</div>
        </div>
        <div class="text-center p-2 bg-purple-50 rounded-lg">
            <div class="text-xl font-bold text-purple-600">{{ $stats['ordered'] }}</div>
            <div class="text-xs text-gray-500">Ordered</div>
        </div>
        <div class="text-center p-2 bg-yellow-50 rounded-lg">
            <div class="text-xl font-bold text-yellow-600">{{ $stats['partially_received'] ?? 0 }}</div>
            <div class="text-xs text-gray-500">Partial</div>
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
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Request # or Supplier" 
                       class="px-3 py-1.5 border rounded-lg text-sm w-48">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="px-3 py-1.5 border rounded-lg text-sm">
                    <option value="">All</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="acknowledged" {{ request('status') == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                    <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>Ordered</option>
                    <option value="partially_received" {{ request('status') == 'partially_received' ? 'selected' : '' }}>Partially Received</option>
                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
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
                <label class="block text-xs font-medium text-gray-500 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-1.5 border rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-3 py-1.5 border rounded-lg text-sm">
            </div>
            <div>
                <button type="submit" class="bg-gray-900 text-white px-4 py-1.5 rounded-lg text-sm">Filter</button>
                <a href="{{ route('admin.restock.index') }}" class="text-gray-500 text-sm ml-2 hover:underline">Reset</a>
            </div>
        </form>
    </div>

    <!-- Requests Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Request #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Supplier</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Items</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Created By</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($requests as $request)
                <tr>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.restock.show', $request->id) }}" class="text-blue-600 hover:underline font-medium">
                            {{ $request->request_number }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        {{ $request->supplier->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        {{ $request->request_date ? $request->request_date->format('M d, Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-center text-sm">
                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">
                            {{ $request->items->count() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs rounded-full bg-{{ $request->status_color }}-100 text-{{ $request->status_color }}-800">
                            {{ $request->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm">
                        {{ $request->createdBy->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.restock.show', $request->id) }}" class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                        @if($request->status == 'draft')
                            <a href="{{ route('admin.restock.edit', $request->id) }}" class="text-amber-600 hover:text-amber-900 text-sm">Edit</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">No restock requests found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t">
        {{ $requests->withQueryString()->links() }}
    </div>
</div>
@endsection