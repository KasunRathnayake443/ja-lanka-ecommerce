@extends('admin.layouts.app')

@section('page_title', 'Products')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">📦 Products</h2>
            <p class="text-sm text-gray-500">Manage your food and appliance products</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
            + Add Product
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Name / SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                <tr>
                    <td class="px-6 py-4">
                        @if($product->images->first())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-12 h-12 object-cover rounded">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-400">📷</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $product->name }}</div>
                        <div class="text-sm text-gray-500">SKU: {{ $product->sku }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $product->type == 'food' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($product->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        LKR {{ number_format($product->regular_price, 2) }}
                        @if($product->sale_price)
                            <div class="text-sm text-red-600">Sale: LKR {{ number_format($product->sale_price, 2) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @php $stock = $product->inventory->quantity_on_hand ?? 0; @endphp
                        @if($stock > 10)
                            <span class="text-green-600">{{ $stock }} in stock</span>
                        @elseif($stock > 0)
                            <span class="text-orange-600">Only {{ $stock }} left</span>
                        @else
                            <span class="text-red-600">Out of stock</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="{{ $product->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        No products yet. <a href="{{ route('admin.products.create') }}" class="text-red-600">Create your first product</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t">
        {{ $products->links() }}
    </div>
</div>
@endsection