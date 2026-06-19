@extends('admin.layouts.app')

@section('page_title', 'Create Purchase Order')

@section('content')
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
                            @include('admin.purchase-orders.partials._item_row', [
                                'index' => $index,
                                'productData' => $product,
                                'selected' => true
                            ])
                        @endforeach
                    @elseif(old('items'))
                        @foreach(old('items') as $index => $item)
                            @include('admin.purchase-orders.partials._item_row', [
                                'index' => $index,
                                'productData' => $item,
                                'selected' => false
                            ])
                        @endforeach
                    @else
                        @include('admin.purchase-orders.partials._item_row', [
                            'index' => 0,
                            'productData' => null,
                            'selected' => false
                        ])
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

    fetch(`{{ route('admin.restock.search-products') }}?search=${encodeURIComponent(searchTerm)}`)
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
    const row = document.getElementById(`item_row_${rowId}`);
    const productInput = document.getElementById(`product_${rowId}`);
    const productIdInput = document.getElementById(`product_id_${rowId}`);
    const resultsContainer = document.getElementById(`product_results_${rowId}`);
    
    productInput.value = 'Loading...';
    
    fetch(`{{ route('admin.restock.get-product-details', '') }}/${productId}`)
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