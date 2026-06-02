@extends('admin.layouts.app')

@section('page_title', 'Flash Sale Banners')

@section('content')
<div class="space-y-6">
    <!-- Flash Sale Banners List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold">🔥 Flash Sale Banners</h2>
                <p class="text-sm text-gray-500">Manage flash sale banners that appear on mobile homepage</p>
            </div>
            <a href="{{ route('admin.flash-sales.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                + Add Banner
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Discount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Custom Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($banners as $banner)
                    <tr>
                        <td class="px-6 py-4">
                            @if($banner->product)
                                <div class="font-medium">{{ $banner->product->name }}</div>
                                <div class="text-sm text-gray-500">SKU: {{ $banner->product->sku }}</div>
                            @else
                                <div class="text-red-500">Product deleted</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($banner->product)
                                @if($banner->product->hasActiveSale())
                                    <div class="text-red-600 font-bold">LKR {{ number_format($banner->product->sale_price, 2) }}</div>
                                    <div class="text-gray-400 text-sm line-through">LKR {{ number_format($banner->product->regular_price, 2) }}</div>
                                @else
                                    <div>LKR {{ number_format($banner->product->regular_price, 2) }}</div>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($banner->product && $banner->product->hasActiveSale())
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">
                                    -{{ $banner->product->discount_percent }}%
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">No sale</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($banner->custom_title)
                                <span class="text-sm">{{ Str::limit($banner->custom_title, 30) }}</span>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $banner->display_order }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $banner->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $banner->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.flash-sales.edit', $banner->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form action="{{ route('admin.flash-sales.destroy', $banner->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Remove this banner from flash sale?')">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No flash sale banners yet. 
                            <a href="{{ route('admin.flash-sales.create') }}" class="text-red-600">Add your first banner</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection