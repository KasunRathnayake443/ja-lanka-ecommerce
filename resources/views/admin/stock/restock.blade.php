@extends('admin.layouts.app')

@section('page_title', 'Create Restock Request')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Create Restock Request</h2>
            <p class="text-sm text-gray-500">Request stock for: {{ $product->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.stock.low') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
                Back to Stock
            </a>
        </div>
    </div>

    <div class="p-6">
        <!-- Product Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Product</span>
                    <p class="font-medium">{{ $product->name }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">SKU</span>
                    <p class="font-medium">{{ $product->sku }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Current Stock</span>
                    <p class="font-medium {{ ($product->inventory->quantity_on_hand ?? 0) <= ($product->inventory->reorder_level ?? 5) ? 'text-red-600' : 'text-green-600' }}">
                        {{ $product->inventory->quantity_on_hand ?? 0 }}
                    </p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Reorder Level</span>
                    <p class="font-medium text-yellow-600">{{ $product->inventory->reorder_level ?? 5 }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.stock.restock.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <select name="supplier_id" required class="w-full px-3 py-2 border rounded-lg @error('supplier_id') border-red-500 @enderror">
                        <option value="">Select Supplier</option>
                        @foreach($product->suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $supplier->pivot->is_default ? 'selected' : '' }}>
                                {{ $supplier->name }}
                                @if($supplier->pivot->is_default) (Default) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @if($product->suppliers->count() == 0)
                        <p class="text-red-500 text-xs mt-1">
                            No suppliers assigned to this product. 
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:underline">Add a supplier first.</a>
                        </p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity to Restock *</label>
                    <input type="number" name="quantity" required min="1" 
                           value="{{ old('quantity', max($product->inventory->reorder_level ?? 5, 10)) }}" 
                           class="w-full px-3 py-2 border rounded-lg @error('quantity') border-red-500 @enderror">
                    @error('quantity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery Date</label>
                    <input type="date" name="expected_delivery_date" 
                           value="{{ old('expected_delivery_date', now()->addDays(7)->format('Y-m-d')) }}" 
                           class="w-full px-3 py-2 border rounded-lg @error('expected_delivery_date') border-red-500 @enderror">
                    @error('expected_delivery_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Cost (LKR)</label>
                    <input type="number" name="unit_cost" step="0.01" placeholder="Cost per unit" 
                           value="{{ old('unit_cost') }}"
                           class="w-full px-3 py-2 border rounded-lg @error('unit_cost') border-red-500 @enderror">
                    @error('unit_cost')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border rounded-lg" 
                              placeholder="Any special notes about this restock request...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t mt-6">
                <a href="{{ route('admin.stock.low') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Create Restock Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection