<div class="item-row border rounded-lg p-4 mb-3" id="item_row_{{ $index }}">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
            <div class="relative">
                @if(isset($productData) && $productData)
                    <input type="text" id="product_{{ $index }}" value="{{ $productData['product_name'] ?? $productData['name'] ?? '' }}" 
                           class="w-full px-3 py-2 border rounded-lg bg-gray-50" readonly>
                    <input type="hidden" name="items[{{ $index }}][product_id]" id="product_id_{{ $index }}" value="{{ $productData['product_id'] ?? $productData['id'] ?? '' }}">
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
            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
            <input type="number" name="items[{{ $index }}][quantity_ordered]" required min="1" 
                   value="{{ $productData['quantity_ordered'] ?? 10 }}" 
                   class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost (LKR) *</label>
            <input type="number" name="items[{{ $index }}][unit_cost]" required step="0.01" id="unit_cost_{{ $index }}" 
                   value="{{ $productData['unit_cost'] ?? '' }}"
                   class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier SKU</label>
            <input type="text" name="items[{{ $index }}][supplier_sku]" 
                   value="{{ $productData['supplier_sku'] ?? '' }}"
                   class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div class="md:col-span-4 flex justify-end">
            <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
        </div>
    </div>
</div>