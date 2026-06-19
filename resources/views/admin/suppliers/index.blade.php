@extends('admin.layouts.app')

@section('page_title', 'Suppliers')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Supplier Management</h2>
            <p class="text-sm text-gray-500">Manage your product suppliers</p>
        </div>
        <a href="{{ route('admin.suppliers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Supplier
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 p-4 border-b">
        <div class="text-center p-3 bg-gray-50 rounded-lg">
            <div class="text-xl font-bold text-gray-800">{{ $totalSuppliers }}</div>
            <div class="text-xs text-gray-500">Total Suppliers</div>
        </div>
        <div class="text-center p-3 bg-green-50 rounded-lg">
            <div class="text-xl font-bold text-green-600">{{ $activeSuppliers }}</div>
            <div class="text-xs text-gray-500">Active</div>
        </div>
        <div class="text-center p-3 bg-red-50 rounded-lg">
            <div class="text-xl font-bold text-red-600">{{ $inactiveSuppliers }}</div>
            <div class="text-xs text-gray-500">Inactive</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="px-6 py-4 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, contact, email..." 
                       class="px-3 py-1.5 border rounded-lg text-sm w-48">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="px-3 py-1.5 border rounded-lg text-sm">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-gray-900 text-white px-4 py-1.5 rounded-lg text-sm">Filter</button>
                <a href="{{ route('admin.suppliers.index') }}" class="text-gray-500 text-sm ml-2 hover:underline">Reset</a>
            </div>
        </form>
    </div>

    <!-- Suppliers Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Supplier</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Products</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($suppliers as $supplier)
                <tr>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $supplier->name }}</div>
                        <div class="text-sm text-gray-500">{{ $supplier->city ?? 'No location' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        {{ $supplier->contact_person ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        {{ $supplier->phone ?? $supplier->mobile ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        {{ $supplier->email ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                            {{ $supplier->products()->count() }} products
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $supplier->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                        <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="text-amber-600 hover:text-amber-900 text-sm">Edit</a>
                        <form action="{{ route('admin.suppliers.toggle-status', $supplier->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm {{ $supplier->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                                {{ $supplier->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">No suppliers found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t">
        {{ $suppliers->withQueryString()->links() }}
    </div>
</div>
@endsection