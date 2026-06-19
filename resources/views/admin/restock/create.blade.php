@extends('admin.layouts.app')

@section('page_title', 'Create Restock Request')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Create Restock Request</h2>
            <p class="text-sm text-gray-500">Request stock from suppliers</p>
        </div>
        <a href="{{ route('admin.restock.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
            Back to Requests
        </a>
    </div>

    <form action="{{ route('admin.restock.store') }}" method="POST" id="restockForm">
        @csrf
        <div class="p-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <select name="supplier_id" id="supplier_id" required class="w-full px-3 py-2 border rounded-lg @error('supplier_id') border-red-500 @enderror">
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ ($defaultSupplier && $defaultSupplier->id == $supplier->id) || old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Request Date *</label>
                    <input type="date" name="request_date" value="{{ old('request_date', date('Y-m-d')) }}" required 
                           class="w-full px-3 py-2 border rounded-lg @error('request_date') border-red-500 @enderror">
                    @error('request_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery Date</label>
                    <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date', date('Y-m-d', strtotime('+7 days'))) }}" 
                           class="w-full px-3 py-2 border rounded-lg @error('expected_delivery_date') border-red-500 @enderror">
                    @error('expected_delivery_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="1" class="w-full px-3 py-2 border rounded-lg">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Products Section -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">Products</h3>
                
                <div id="itemsContainer">
                    @if(isset($selectedProduct) && $selectedProduct)
                        <div class="item-row border rounded-lg p-4 mb-3" id="item_row_0">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                                    <div class="relative">
                                        <input type="text" id="product_0" value="{{ $selectedProduct->name }}" 
                                               class="w-full px-3 py-2 border rounded-lg bg-gray-50" readonly>
                                        <input type="hidden" name="items[0][product_id]" id="product_id_0" value="{{ $selectedProduct->id }}">
                                        <div id="product_results_0" class="hidden absolute z-10 bg-white border rounded-lg shadow-lg w-full max-h-48 overflow-y-auto"></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                                    <select name="items[0][supplier_id]" id="supplier_0" class="w-full px-3 py-2 border rounded-lg">
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ ($defaultSupplier && $defaultSupplier->id == $supplier->id) ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                    <input type="number" name="items[0][quantity_requested]" required min="1" value="{{ old('items.0.quantity_requested', 10) }}" 
                                           class="w-full px-3 py-2 border rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost (LKR)</label>
                                    <input type="number" name="items[0][unit_cost]" step="0.01" id="unit_cost_0" 
                                           value="{{ old('items.0.unit_cost', '') }}"
                                           class="w-full px-3 py-2 border rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier SKU</label>
                                    <input type="text" name="items[0][supplier_sku]" id="supplier_sku_0" 
                                           value="{{ old('items.0.supplier_sku', '') }}"
                                           class="w-full px-3 py-2 border rounded-lg">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </div>
                            </div>
                            <div id="stock_info_0" class="text-xs text-gray-500 mt-2">
                                @if($selectedProduct && $selectedProduct->inventory)
                                    Current: {{ $selectedProduct->inventory->quantity_on_hand ?? 0 }} | Reorder Level: {{ $selectedProduct->inventory->reorder_level ?? 5 }}
                                @endif
                            </div>
                        </div>
                    @elseif(old('items'))
                        @foreach(old('items') as $index => $item)
                            @php
                                $product = \App\Models\Product::find($item['product_id'] ?? null);
                            @endphp
                            <div class="item-row border rounded-lg p-4 mb-3" id="item_row_{{ $index }}">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                                        <div class="relative">
                                            <input type="text" id="product_{{ $index }}" value="{{ $product->name ?? '' }}" 
                                                   class="w-full px-3 py-2 border rounded-lg bg-gray-50" readonly>
                                            <input type="hidden" name="items[{{ $index }}][product_id]" id="product_id_{{ $index }}" value="{{ $item['product_id'] ?? '' }}">
                                            <div id="product_results_{{ $index }}" class="hidden absolute z-10 bg-white border rounded-lg shadow-lg w-full max-h-48 overflow-y-auto"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                                        <select name="items[{{ $index }}][supplier_id]" id="supplier_{{ $index }}" class="w-full px-3 py-2 border rounded-lg">
                                            <option value="">Select Supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" {{ (isset($item['supplier_id']) && $item['supplier_id'] == $supplier->id) ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                        <input type="number" name="items[{{ $index }}][quantity_requested]" required min="1" value="{{ $item['quantity_requested'] ?? 10 }}" 
                                               class="w-full px-3 py-2 border rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost (LKR)</label>
                                        <input type="number" name="items[{{ $index }}][unit_cost]" step="0.01" id="unit_cost_{{ $index }}" 
                                               value="{{ $item['unit_cost'] ?? '' }}"
                                               class="w-full px-3 py-2 border rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier SKU</label>
                                        <input type="text" name="items[{{ $index }}][supplier_sku]" id="supplier_sku_{{ $index }}" 
                                               value="{{ $item['supplier_sku'] ?? '' }}"
                                               class="w-full px-3 py-2 border rounded-lg">
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                    </div>
                                </div>
                                <div id="stock_info_{{ $index }}" class="hidden text-xs text-gray-500 mt-2"></div>
                            </div>
                        @endforeach
                    @else
                        <div class="item-row border rounded-lg p-4 mb-3" id="item_row_0">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                                    <div class="relative">
                                        <input type="text" id="product_0" placeholder="Search product..." 
                                               class="w-full px-3 py-2 border rounded-lg" 
                                               oninput="searchProduct(this, '0')">
                                        <input type="hidden" name="items[0][product_id]" id="product_id_0" value="">
                                        <div id="product_results_0" class="hidden absolute z-10 bg-white border rounded-lg shadow-lg w-full max-h-48 overflow-y-auto"></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                                    <select name="items[0][supplier_id]" id="supplier_0" class="w-full px-3 py-2 border rounded-lg">
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                    <input type="number" name="items[0][quantity_requested]" required min="1" value="10" 
                                           class="w-full px-3 py-2 border rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost (LKR)</label>
                                    <input type="number" name="items[0][unit_cost]" step="0.01" id="unit_cost_0" 
                                           class="w-full px-3 py-2 border rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier SKU</label>
                                    <input type="text" name="items[0][supplier_sku]" id="supplier_sku_0" 
                                           class="w-full px-3 py-2 border rounded-lg">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </div>
                            </div>
                            <div id="stock_info_0" class="hidden text-xs text-gray-500 mt-2"></div>
                        </div>
                    @endif
                </div>

                <button type="button" onclick="addItemRow()" class="mt-3 text-blue-600 hover:text-blue-800 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Product
                </button>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-3 pt-4 border-t mt-6">
                <a href="{{ route('admin.restock.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Request</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemIndex = {{ isset($selectedProduct) ? 1 : max(count(old('items', [])), 1) }};

function searchProduct(input, rowId) {
    const searchTerm = input.value;
    const resultsContainer = document.getElementById(`product_results_${rowId}`);
    
    if (searchTerm.length < 2) {
        resultsContainer.classList.add('hidden');
        return;
    }

    fetch(`{{ route('admin.restock.search-products') }}?search=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(products => {
            if (products.length === 0) {
                resultsContainer.innerHTML = '<div class="p-2 text-gray-500 text-sm">No products found</div>';
            } else {
                resultsContainer.innerHTML = products.map(p => `
                    <div class="p-2 hover:bg-gray-50 cursor-pointer border-b" onclick="selectProduct(${p.id}, '${rowId}')">
                        <div class="font-medium text-sm">${p.name}</div>
                        <div class="text-xs text-gray-500">SKU: ${p.sku} | Stock: ${p.stock}</div>
                    </div>
                `).join('');
            }
            resultsContainer.classList.remove('hidden');
        })
        .catch(() => {
            resultsContainer.innerHTML = '<div class="p-2 text-red-500 text-sm">Error searching products</div>';
            resultsContainer.classList.remove('hidden');
        });
}

function selectProduct(productId, rowId) {
    const productInput = document.getElementById(`product_${rowId}`);
    const productIdInput = document.getElementById(`product_id_${rowId}`);
    const resultsContainer = document.getElementById(`product_results_${rowId}`);
    
    productInput.value = 'Loading...';
    
    fetch(`{{ url('admin/restock/get-product-details') }}/${productId}`)
        .then(response => response.json())
        .then(data => {
            productInput.value = data.name;
            productIdInput.value = data.id;
            
            // Auto-fill supplier if empty
            const supplierSelect = document.getElementById(`supplier_${rowId}`);
            if (data.default_supplier_id && supplierSelect) {
                // Check if the option exists
                let optionExists = false;
                for (let i = 0; i < supplierSelect.options.length; i++) {
                    if (supplierSelect.options[i].value == data.default_supplier_id) {
                        optionExists = true;
                        break;
                    }
                }
                if (optionExists) {
                    supplierSelect.value = data.default_supplier_id;
                }
            }
            
            // Auto-fill supplier SKU
            const supplierSkuInput = document.getElementById(`supplier_sku_${rowId}`);
            if (data.supplier_sku && supplierSkuInput) {
                supplierSkuInput.value = data.supplier_sku;
            }
            
            // Auto-fill unit cost
            const unitCostInput = document.getElementById(`unit_cost_${rowId}`);
            if (data.supplier_price && unitCostInput) {
                unitCostInput.value = data.supplier_price;
            }
            
            resultsContainer.classList.add('hidden');
            
            // Update stock info
            const stockInfo = document.getElementById(`stock_info_${rowId}`);
            if (stockInfo) {
                stockInfo.innerHTML = `Current: ${data.stock} | Reorder Level: ${data.reorder_level}`;
                stockInfo.classList.remove('hidden');
            }
        })
        .catch(() => {
            productInput.value = '';
            productIdInput.value = '';
            alert('Error loading product details');
        });
}

function addItemRow() {
    const container = document.getElementById('itemsContainer');
    const row = document.createElement('div');
    row.className = 'item-row border rounded-lg p-4 mb-3';
    row.id = `item_row_${itemIndex}`;
    row.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                <div class="relative">
                    <input type="text" id="product_${itemIndex}" placeholder="Search product..." 
                           class="w-full px-3 py-2 border rounded-lg" 
                           oninput="searchProduct(this, '${itemIndex}')">
                    <input type="hidden" name="items[${itemIndex}][product_id]" id="product_id_${itemIndex}" value="">
                    <div id="product_results_${itemIndex}" class="hidden absolute z-10 bg-white border rounded-lg shadow-lg w-full max-h-48 overflow-y-auto"></div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                <select name="items[${itemIndex}][supplier_id]" id="supplier_${itemIndex}" class="w-full px-3 py-2 border rounded-lg">
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                <input type="number" name="items[${itemIndex}][quantity_requested]" required min="1" value="10" 
                       class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost (LKR)</label>
                <input type="number" name="items[${itemIndex}][unit_cost]" step="0.01" id="unit_cost_${itemIndex}" 
                       class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier SKU</label>
                <input type="text" name="items[${itemIndex}][supplier_sku]" id="supplier_sku_${itemIndex}" 
                       class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div class="flex items-end">
                <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
            </div>
        </div>
        <div id="stock_info_${itemIndex}" class="hidden text-xs text-gray-500 mt-2"></div>
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