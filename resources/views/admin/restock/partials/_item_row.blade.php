<div class="item-row border rounded-lg p-4 mb-3" id="item_row_{{ $index }}">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
            <div class="relative">
                @if(isset($product) && $product)
                    <input type="text" id="product_{{ $index }}" value="{{ $product->name }}" 
                           class="w-full px-3 py-2 border rounded-lg bg-gray-50" readonly>
                    <input type="hidden" name="items[{{ $index }}][product_id]" id="product_id_{{ $index }}" value="{{ $product->id }}">
                @else
                    <input type="text" id="product_{{ $index }}" placeholder="Search product..." 
                           class="w-full px-3 py-2 border rounded-lg" 
                           oninput="searchProduct(this, '{{ $index }}')">
                    <input type="hidden" name="items[{{ $index }}][product_id]" id="product_id_{{ $index }}" value="">
                    <div id="product_results_{{ $index }}" class="hidden absolute z-10 bg-white border rounded-lg shadow-lg w-full max-h-48 overflow-y-auto"></div>
                @endif
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
            <select name="items[{{ $index }}][supplier_id]" id="supplier_{{ $index }}" class="w-full px-3 py-2 border rounded-lg">
                <option value="">Select Supplier</option>
                @foreach($suppliers ?? [] as $supplier)
                    <option value="{{ $supplier->id }}" {{ (isset($defaultSupplier) && $defaultSupplier->id == $supplier->id) || (isset($oldData['supplier_id']) && $oldData['supplier_id'] == $supplier->id) ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
            <input type="number" name="items[{{ $index }}][quantity_requested]" required min="1" 
                   value="{{ $oldData['quantity_requested'] ?? 10 }}" 
                   class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost (LKR)</label>
            <input type="number" name="items[{{ $index }}][unit_cost]" step="0.01" id="unit_cost_{{ $index }}" 
                   value="{{ $oldData['unit_cost'] ?? '' }}"
                   class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier SKU</label>
            <input type="text" name="items[{{ $index }}][supplier_sku]" id="supplier_sku_{{ $index }}" 
                   value="{{ $oldData['supplier_sku'] ?? '' }}"
                   class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div class="flex items-end">
            <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
        </div>
    </div>
    <div id="stock_info_{{ $index }}" class="hidden text-xs text-gray-500 mt-2">
        @if(isset($product) && $product && $product->inventory)
            Current: {{ $product->inventory->quantity_on_hand ?? 0 }} | Reorder Level: {{ $product->inventory->reorder_level ?? 5 }}
        @endif
    </div>
</div>