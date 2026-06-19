@extends('admin.layouts.app')

@section('page_title', 'Edit Purchase Order')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Edit Purchase Order</h2>
            <p class="text-sm text-gray-500">{{ $purchaseOrder->po_number }}</p>
        </div>
        <a href="{{ route('admin.purchase-orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
            Back to POs
        </a>
    </div>

    <form action="{{ route('admin.purchase-orders.update', $purchaseOrder->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="p-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <select name="supplier_id" required class="w-full px-3 py-2 border rounded-lg @error('supplier_id') border-red-500 @enderror">
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $purchaseOrder->supplier_id == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order Date *</label>
                    <input type="date" name="order_date" value="{{ old('order_date', $purchaseOrder->order_date->format('Y-m-d')) }}" required 
                           class="w-full px-3 py-2 border rounded-lg @error('order_date') border-red-500 @enderror">
                    @error('order_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery</label>
                    <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date', $purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('Y-m-d') : '') }}" 
                           class="w-full px-3 py-2 border rounded-lg @error('expected_delivery_date') border-red-500 @enderror">
                    @error('expected_delivery_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tax Amount (LKR)</label>
                    <input type="number" name="tax_amount" step="0.01" value="{{ old('tax_amount', $purchaseOrder->tax_amount) }}" 
                           class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Amount (LKR)</label>
                    <input type="number" name="shipping_amount" step="0.01" value="{{ old('shipping_amount', $purchaseOrder->shipping_amount) }}" 
                           class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="1" class="w-full px-3 py-2 border rounded-lg">{{ old('notes', $purchaseOrder->notes) }}</textarea>
                </div>
            </div>

            <!-- Products Section -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">Products</h3>
                
                <div id="itemsContainer">
                    @foreach($purchaseOrder->items as $index => $item)
                        <div class="item-row border rounded-lg p-4 mb-3" id="item_row_{{ $index }}">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                                    <select name="items[{{ $index }}][product_id]" required class="w-full px-3 py-2 border rounded-lg">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} ({{ $product->sku }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                    <input type="number" name="items[{{ $index }}][quantity_ordered]" required min="1" 
                                           value="{{ old("items.{$index}.quantity_ordered", $item->quantity_ordered) }}" 
                                           class="w-full px-3 py-2 border rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost (LKR) *</label>
                                    <input type="number" name="items[{{ $index }}][unit_cost]" required step="0.01" 
                                           value="{{ old("items.{$index}.unit_cost", $item->unit_cost) }}"
                                           class="w-full px-3 py-2 border rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier SKU</label>
                                    <input type="text" name="items[{{ $index }}][supplier_sku]" 
                                           value="{{ old("items.{$index}.supplier_sku", $item->supplier_sku) }}"
                                           class="w-full px-3 py-2 border rounded-lg">
                                </div>
                                <div class="md:col-span-4 flex justify-end">
                                    <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" onclick="addItemRow()" class="mt-3 text-purple-600 hover:text-purple-800 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Product
                </button>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-3 pt-4 border-t mt-6">
                <a href="{{ route('admin.purchase-orders.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Update Purchase Order</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemIndex = {{ $purchaseOrder->items->count() }};

function addItemRow() {
    const container = document.getElementById('itemsContainer');
    const row = document.createElement('div');
    row.className = 'item-row border rounded-lg p-4 mb-3';
    row.id = `item_row_${itemIndex}`;
    row.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                <select name="items[${itemIndex}][product_id]" required class="w-full px-3 py-2 border rounded-lg">
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                <input type="number" name="items[${itemIndex}][quantity_ordered]" required min="1" value="10" 
                       class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost (LKR) *</label>
                <input type="number" name="items[${itemIndex}][unit_cost]" required step="0.01" 
                       class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier SKU</label>
                <input type="text" name="items[${itemIndex}][supplier_sku]" 
                       class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div class="md:col-span-4 flex justify-end">
                <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
            </div>
        </div>
    `;
    container.appendChild(row);
    itemIndex++;
}

function removeItem(button) {
    const row = button.closest('.item-row');
    if (document.querySelectorAll('.item-row').length > 1) {
        row.remove();
    } else {
        alert('You must have at least one product');
    }
}
</script>
@endpush
@endsection