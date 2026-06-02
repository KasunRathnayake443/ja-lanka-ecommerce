@extends('admin.layouts.app')

@section('page_title', 'Add Flash Sale Banner')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <h2 class="text-xl font-semibold">Add Flash Sale Banner</h2>
        <p class="text-sm text-gray-500">Select a product to feature as a flash sale banner</p>
    </div>
    
    <form action="{{ route('admin.flash-sales.store') }}" method="POST">
        @csrf
        
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Product *</label>
                <select name="product_id" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                    <option value="">-- Select a product --</option>
                    @foreach($products as $product)
                        @php
                            $hasSale = $product->hasActiveSale();
                            $discount = $hasSale ? $product->discount_percent : 0;
                        @endphp
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} 
                            @if($hasSale)
                                - LKR {{ number_format($product->sale_price, 2) }} 
                                ({{ $discount }}% OFF)
                            @else
                                - LKR {{ number_format($product->regular_price, 2) }}
                            @endif
                        </option>
                    @endforeach
                </select>
                
                @if($products->isEmpty())
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">No products available to add.</p>
                        <p class="text-xs text-yellow-600 mt-1">Add products first in Product Management.</p>
                    </div>
                @endif
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Custom Title (Optional)</label>
                <input type="text" name="custom_title" placeholder="e.g., 🔥 Weekend Special!" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                <p class="text-xs text-gray-500 mt-1">Leave empty to use product name</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Custom Subtitle (Optional)</label>
                <input type="text" name="custom_subtitle" placeholder="e.g., Limited time offer!" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                <input type="number" name="display_order" value="0" class="w-32 px-3 py-2 border rounded-lg">
                <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
            </div>
            
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                    <span class="text-sm font-medium text-gray-700">Active (show on homepage)</span>
                </label>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <a href="{{ route('admin.flash-sales.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                Add Banner
            </button>
        </div>
    </form>
</div>
@endsection