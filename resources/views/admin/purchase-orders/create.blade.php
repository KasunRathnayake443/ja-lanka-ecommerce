@extends('admin.layouts.app')

@section('page_title', 'Create Purchase Order')

@section('content')
@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Create Purchase Order</h2>
            <p class="text-sm text-gray-500">Create a purchase order for suppliers</p>
        </div>
        <a href="{{ route('admin.purchase-orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
            Back to POs
        </a>
    </div>

    <form action="{{ route('admin.purchase-orders.store') }}" method="POST" id="poForm">
        @csrf
        <div class="p-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <select name="supplier_id" id="supplier_id" required class="w-full px-3 py-2 border rounded-lg @error('supplier_id') border-red-500 @enderror">
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ (isset($restockRequest) && $restockRequest->supplier_id == $supplier->id) || old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                    <input type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required 
                           class="w-full px-3 py-2 border rounded-lg @error('order_date') border-red-500 @enderror">
                    @error('order_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery</label>
                    <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date', date('Y-m-d', strtotime('+7 days'))) }}" 
                           class="w-full px-3 py-2 border rounded-lg @error('expected_delivery_date') border-red-500 @enderror">
                    @error('expected_delivery_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tax Amount (LKR)</label>
                    <input type="number" name="tax_amount" step="0.01" value="{{ old('tax_amount', 0) }}" 
                           class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Amount (LKR)</label>
                    <input type="number" name="shipping_amount" step="0.01" value="{{ old('shipping_amount', 0) }}" 
                           class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="1" class="w-full px-3 py-2 border rounded-lg">{{ old('notes') }}</textarea>
                </div>
            </div>

            @if(isset($restockRequest))
                <input type="hidden" name="restock_request_id" value="{{ $restockRequest->id }}">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-blue-700">
                        <strong>Creating PO from Restock Request:</strong> {{ $restockRequest->request_number }}
                        (Supplier: {{ $restockRequest->supplier->name }})
                    </p>
                </div>
            @endif

            <!-- Products Section -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">Products</h3>
                
                <div id="itemsContainer">
                    @if(isset($selectedProducts) && count($selectedProducts) > 0)
                        @foreach($selectedProducts as $index => $product)
                            <div class="item-row border rounded-lg p-4 mb-3" id="item_row_{{ $index }}">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                                        <div class="relative">
                                            <input type="text" id="product_{{ $index }}" value="{{ $product['product_name'] ?? $product['name'] ?? '' }}" 
                                                   class="w-full px-3 py-2 border rounded-lg bg-gray-50" readonly>
                                            <input type="hidden" name="items[{{ $index }}][product_id]" id="product_id_{{ $index }}" value="{{ $product['product_id'] ?? $product['id'] ?? '' }}">
                                            <div id="product_results_{{ $index }}" class="hidden absolute z-10 bg-white border rounded-lg shadow-lg w-full max-h-48 overflow-y-auto"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                        <input type="number" name="items[{{ $index }}][quantity_ordered]" required min="1" 
                                               value="{{ $product['quantity_ordered'] ?? 10 }}" 
                                               class="w-full px-3 py-2 border rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost (LKR) *</label>
                                        <input type="number" name="items[{{ $index }}][unit_cost]" required step="0.01" id="unit_cost_{{ $index }}" 
                                               value="{{ $product['unit_cost'] ?? '' }}"
                                               class="w-full px-3 py-2 border rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier SKU</label>
                                        <input type="text" name="items[{{ $index }}][supplier_sku]" 
                                               value="{{ $product['supplier_sku'] ?? '' }}"
                                               class="w-full px-3 py-2 border rounded-lg">
                                    </div>
                                    <div class="md:col-span-4 flex justify-end">
                                        <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @elseif(old('items'))
                        @foreach(old('items') as $index => $item)
                            <div class="item-row border rounded-lg p-4 mb-3" id="item_row_{{ $index }}">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                                        <div class="relative">
                                            <input type="text" id="product_{{ $index }}" value="{{ $item['product_name'] ?? '' }}" 
                                                   class="w-full px-3 py-2 border rounded-lg bg-gray-50" readonly>
                                            <input type="hidden" name="items[{{ $index }}][product_id]" id="product_id_{{ $index }}" value="{{ $item['product_id'] ?? '' }}">
                                            <div id="product_results_{{ $index }}" class="hidden absolute z-10 bg-white border rounded-lg shadow-lg w-full max-h-48 overflow-y-auto"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                        <input type="number" name="items[{{ $index }}][quantity_ordered]" required min="1" 
                                               value="{{ $item['quantity_ordered'] ?? 10 }}" 
                                               class="w-full px-3 py-2 border rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost (LKR) *</label>
                                        <input type="number" name="items[{{ $index }}][unit_cost]" required step="0.01" id="unit_cost_{{ $index }}" 
                                               value="{{ $item['unit_cost'] ?? '' }}"
                                               class="w-full px-3 py-2 border rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier SKU</label>
                                        <input type="text" name="items[{{ $index }}][supplier_sku]" 
                                               value="{{ $item['supplier_sku'] ?? '' }}"
                                               class="w-full px-3 py-2 border rounded-lg">
                                    </div>
                                    <div class="md:col-span-4 flex justify-end">
                                        <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="item-row border rounded-lg p-4 mb-3" id="item_row_0">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
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
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                    <input type="number" name="items[0][quantity_ordered]" required min="1" value="10" 
                                           class="w-full px-3 py-2 border rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost (LKR) *</label>
                                    <input type="number" name="items[0][unit_cost]" required step="0.01" id="unit_cost_0" 
                                           class="w-full px-3 py-2 border rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier SKU</label>
                                    <input type="text" name="items[0][supplier_sku]" 
                                           class="w-full px-3 py-2 border rounded-lg">
                                </div>
                                <div class="md:col-span-4 flex justify-end">
                                    <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </div>
                            </div>
                        </div>
                    @endif
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
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Create Purchase Order</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemIndex = {{ isset($selectedProducts) ? count($selectedProducts) : max(count(old('items', [])), 1) }};

function searchProduct(input, rowId) {
    const searchTerm = input.value;
    const resultsContainer = document.getElementById(`product_results_${rowId}`);
    
    if (searchTerm.length < 2) {
        resultsContainer.classList.add('hidden');
        return;
    }

    // Use the full URL directly instead of route helper
    fetch(`{{ url('admin/restock/search-products') }}?search=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(products => {
            if (products.length === 0) {
                resultsContainer.innerHTML = '<div class="p-2 text-gray-500 text-sm">No products found</div>';
            } else {
                resultsContainer.innerHTML = products.map(p => `
                    <div class="p-2 hover:bg-gray-50 cursor-pointer border-b" onclick="selectProduct(${p.id}, '${rowId}')">
                        <div class="font-medium text-sm">${p.name}</div>
                        <div class="text-xs text-gray-500">SKU: ${p.sku}</div>
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
    
    // Use the full URL directly instead of route helper
    fetch(`{{ url('admin/restock/get-product-details') }}/${productId}`)
        .then(response => response.json())
        .then(data => {
            productInput.value = data.name;
            productIdInput.value = data.id;
            
            // Auto-fill unit cost from product
            const unitCostInput = document.getElementById(`unit_cost_${rowId}`);
            if (!unitCostInput.value) {
                unitCostInput.value = data.current_price || 0;
            }
            
            resultsContainer.classList.add('hidden');
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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                <input type="number" name="items[${itemIndex}][quantity_ordered]" required min="1" value="10" 
                       class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost (LKR) *</label>
                <input type="number" name="items[${itemIndex}][unit_cost]" required step="0.01" id="unit_cost_${itemIndex}" 
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