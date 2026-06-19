@extends('admin.layouts.app')

@section('page_title', 'Low Stock Products')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">⚠️ Low Stock Products</h2>
            <p class="text-sm text-gray-500">Products that are below their reorder level</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
            Back to Products
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 p-4 border-b">
        <div class="text-center p-3 bg-yellow-50 rounded-lg">
            <div class="text-xl font-bold text-yellow-600">{{ $stats['low_stock_count'] }}</div>
            <div class="text-xs text-gray-500">Low Stock</div>
        </div>
        <div class="text-center p-3 bg-red-50 rounded-lg">
            <div class="text-xl font-bold text-red-600">{{ $stats['out_of_stock_count'] }}</div>
            <div class="text-xs text-gray-500">Out of Stock</div>
        </div>
        <div class="text-center p-3 bg-green-50 rounded-lg">
            <div class="text-xl font-bold text-green-600">{{ $stats['total_products'] - $stats['low_stock_count'] - $stats['out_of_stock_count'] }}</div>
            <div class="text-xs text-gray-500">In Stock</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="px-6 py-4 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Product name or SKU" 
                       class="px-3 py-1.5 border rounded-lg text-sm w-48">
            </div>
            <div>
                <button type="submit" class="bg-gray-900 text-white px-4 py-1.5 rounded-lg text-sm">Filter</button>
                <a href="{{ route('admin.stock.low') }}" class="text-gray-500 text-sm ml-2 hover:underline">Reset</a>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Category</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Stock</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Reorder Level</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Default Supplier</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                <tr>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:underline">
                            {{ $product->name }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $product->sku }}</td>
                    <td class="px-6 py-4 text-sm">{{ $product->category->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ ($product->inventory->quantity_on_hand ?? 0) <= 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $product->inventory->quantity_on_hand ?? 0 }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm">{{ $product->inventory->reorder_level ?? 5 }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if($product->suppliers && $product->suppliers->isNotEmpty())
                            {{ $product->suppliers->first()->name ?? 'N/A' }}
                        @else
                            <span class="text-gray-400">No supplier</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.stock.restock', $product->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                            Restock
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>No low stock products found! 🎉</p>
                        <p class="text-sm text-gray-400">All products are well stocked.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection