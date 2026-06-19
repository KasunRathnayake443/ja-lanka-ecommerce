@extends('admin.layouts.app')

@section('page_title', 'Create Manual Order')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Create Manual Order</h2>
            <p class="text-sm text-gray-500">Create a new order manually for customers</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
            Back to Orders
        </a>
    </div>

    <form action="{{ route('admin.orders.store-manual') }}" method="POST" id="orderForm">
        @csrf
        <div class="p-6">
            <!-- Customer Information Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Customer Information</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type</label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="user_type" value="existing" class="form-radio user-type-radio" checked>
                            <span class="ml-2">Existing User</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="user_type" value="guest" class="form-radio user-type-radio">
                            <span class="ml-2">Guest User</span>
                        </label>
                    </div>
                </div>

                <!-- Existing User Selection -->
                <div id="existing-user-section" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                    <select name="user_id" id="user_id" class="w-full md:w-1/2 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Guest User Section -->
                <div id="guest-user-section" class="hidden mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="guest_name" class="w-full px-3 py-2 border rounded-lg" placeholder="Enter customer name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="guest_email" class="w-full px-3 py-2 border rounded-lg" placeholder="customer@example.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number *</label>
                            <input type="text" name="guest_mobile" class="w-full px-3 py-2 border rounded-lg" placeholder="+94 XX XXX XXXX">
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="mt-4">
                    <h4 class="font-medium mb-3">Shipping Address</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address Line 1 *</label>
                            <input type="text" name="address_line1" id="address_line1" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address Line 2</label>
                            <input type="text" name="address_line2" id="address_line2" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                            <input type="text" name="city" id="city" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                            <input type="text" name="state" id="state" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                            <input type="text" name="postal_code" id="postal_code" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                            <input type="text" name="country" id="country" class="w-full px-3 py-2 border rounded-lg" value="Sri Lanka">
                        </div>
                    </div>
                    <button type="button" id="fillAddressBtn" class="hidden mt-2 text-sm text-blue-600 hover:underline">Use saved address</button>
                </div>
            </div>

            <!-- Products Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Products</h3>
                
                <!-- Product Search -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
                    <div class="flex gap-2">
                        <input type="text" id="product-search" placeholder="Search by product name or SKU..." class="flex-1 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="button" id="search-product-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Search</button>
                    </div>
                    <div id="search-results" class="hidden mt-2 border rounded-lg max-h-64 overflow-y-auto"></div>
                </div>

                <!-- Cart Table -->
                <div class="overflow-x-auto mt-4">
                    <table class="w-full border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium">Product</th>
                                <th class="px-4 py-2 text-left text-sm font-medium">SKU</th>
                                <th class="px-4 py-2 text-center text-sm font-medium">Unit Price (LKR)</th>
                                <th class="px-4 py-2 text-center text-sm font-medium">Quantity</th>
                                <th class="px-4 py-2 text-right text-sm font-medium">Total (LKR)</th>
                                <th class="px-4 py-2 text-center text-sm font-medium">Action</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            <tr id="empty-cart-row">
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">No products added yet. Search and add products above.</td>
                            </tr>
                        </tbody>
                        <tfoot id="cart-footer" class="hidden bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-right font-medium">Subtotal:</td>
                                <td class="px-4 py-2 text-right font-medium" id="subtotal">LKR 0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-right font-medium">Shipping:</td>
                                <td class="px-4 py-2 text-right">
                                    <input type="number" name="shipping_amount" id="shipping_amount" value="0" step="0.01" class="w-32 px-2 py-1 border rounded text-right">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-right font-medium">Tax (8%):</td>
                                <td class="px-4 py-2 text-right font-medium" id="tax_amount">LKR 0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-right font-medium">Discount:</td>
                                <td class="px-4 py-2 text-right">
                                    <input type="number" name="discount_amount" id="discount_amount" value="0" step="0.01" class="w-32 px-2 py-1 border rounded text-right">
                                </td>
                                <td></td>
                            </tr>
                            <tr class="border-t-2">
                                <td colspan="4" class="px-4 py-2 text-right font-bold text-lg">Grand Total:</td>
                                <td class="px-4 py-2 text-right font-bold text-lg text-blue-600" id="grand_total">LKR 0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Order Details Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Order Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Status *</label>
                        <select name="order_status" class="w-full px-3 py-2 border rounded-lg">
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status *</label>
                        <select name="payment_status" class="w-full px-3 py-2 border rounded-lg">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Method</label>
                        <select name="shipping_method" class="w-full px-3 py-2 border rounded-lg">
                            <option value="standard">Standard Shipping</option>
                            <option value="express">Express Shipping</option>
                            <option value="pickup">Store Pickup</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                        <select name="payment_method" class="w-full px-3 py-2 border rounded-lg">
                            <option value="manual">Manual Entry</option>
                            <option value="cash">Cash on Delivery</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="card">Credit/Debit Card</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-3 py-2 border rounded-lg" placeholder="Any special notes about this order..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Hidden inputs for items -->
            <input type="hidden" name="items" id="items-input">

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Create Order</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let cart = [];
let productsData = {};

// Toggle between existing and guest user
document.querySelectorAll('.user-type-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        if (this.value === 'existing') {
            document.getElementById('existing-user-section').classList.remove('hidden');
            document.getElementById('guest-user-section').classList.add('hidden');
            document.getElementById('fillAddressBtn').classList.remove('hidden');
        } else {
            document.getElementById('existing-user-section').classList.add('hidden');
            document.getElementById('guest-user-section').classList.remove('hidden');
            document.getElementById('fillAddressBtn').classList.add('hidden');
            // Clear address fields
            document.getElementById('address_line1').value = '';
            document.getElementById('address_line2').value = '';
            document.getElementById('city').value = '';
            document.getElementById('state').value = '';
            document.getElementById('postal_code').value = '';
        }
    });
});

// Load user details when user is selected
document.getElementById('user_id').addEventListener('change', function() {
    const userId = this.value;
    if (userId) {
        fetch(`{{ route('admin.orders.get-user-details', '') }}/${userId}`)
            .then(response => response.json())
            .then(data => {
                // Fill customer info fields (for reference)
                if (data.default_address) {
                    document.getElementById('address_line1').value = data.default_address.address_line1 || '';
                    document.getElementById('address_line2').value = data.default_address.address_line2 || '';
                    document.getElementById('city').value = data.default_address.city || '';
                    document.getElementById('state').value = data.default_address.state || '';
                    document.getElementById('postal_code').value = data.default_address.postal_code || '';
                    document.getElementById('country').value = data.default_address.country || 'Sri Lanka';
                }
                
                // Store addresses for fill button
                window.userAddresses = data.addresses;
            });
    }
});

// Fill address button
document.getElementById('fillAddressBtn').addEventListener('click', function() {
    if (window.userAddresses && window.userAddresses.length > 0) {
        const addr = window.userAddresses[0];
        document.getElementById('address_line1').value = addr.address_line1 || '';
        document.getElementById('address_line2').value = addr.address_line2 || '';
        document.getElementById('city').value = addr.city || '';
        document.getElementById('state').value = addr.state || '';
        document.getElementById('postal_code').value = addr.postal_code || '';
        document.getElementById('country').value = addr.country || 'Sri Lanka';
    }
});

// Product search
document.getElementById('search-product-btn').addEventListener('click', function() {
    const searchTerm = document.getElementById('product-search').value;
    if (searchTerm.length < 2) {
        alert('Please enter at least 2 characters to search');
        return;
    }
    
    fetch(`{{ route('admin.orders.search-products') }}?search=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(products => {
            const resultsDiv = document.getElementById('search-results');
            if (products.length === 0) {
                resultsDiv.innerHTML = '<div class="p-4 text-center text-gray-500">No products found</div>';
            } else {
                resultsDiv.innerHTML = products.map(product => `
                    <div class="p-3 border-b hover:bg-gray-50 flex justify-between items-center">
                        <div class="flex-1">
                            <div class="font-medium">${product.name}</div>
                            <div class="text-sm text-gray-500">SKU: ${product.sku} | Stock: ${product.stock}</div>
                            <div class="text-sm">Price: LKR ${product.current_price} ${product.has_sale ? `<span class="text-green-600">(-${product.discount_percent}%)</span>` : ''}</div>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="number" id="qty-${product.id}" value="1" min="1" max="${product.stock}" class="w-20 px-2 py-1 border rounded text-center">
                            <button onclick="addToCart(${product.id}, '${product.name}', '${product.sku}', ${product.current_price}, ${product.stock})" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Add</button>
                        </div>
                    </div>
                `).join('');
                resultsDiv.classList.remove('hidden');
            }
        });
});

// Add to cart function
window.addToCart = function(productId, name, sku, price, stock) {
    const qtyInput = document.getElementById(`qty-${productId}`);
    const quantity = parseInt(qtyInput ? qtyInput.value : 1);
    
    if (quantity < 1) {
        alert('Quantity must be at least 1');
        return;
    }
    
    if (quantity > stock) {
        alert(`Only ${stock} items available in stock`);
        return;
    }
    
    const existingItem = cart.find(item => item.product_id === productId);
    if (existingItem) {
        const newQty = existingItem.quantity + quantity;
        if (newQty > stock) {
            alert(`Cannot add ${quantity}. Total would exceed stock (${stock})`);
            return;
        }
        existingItem.quantity = newQty;
        existingItem.total_price = existingItem.quantity * existingItem.unit_price;
    } else {
        cart.push({
            product_id: productId,
            name: name,
            sku: sku,
            unit_price: price,
            quantity: quantity,
            total_price: price * quantity
        });
    }
    
    updateCartDisplay();
    document.getElementById('search-results').classList.add('hidden');
    document.getElementById('product-search').value = '';
};

// Update cart display
function updateCartDisplay() {
    const tbody = document.getElementById('cart-items');
    const footer = document.getElementById('cart-footer');
    
    if (cart.length === 0) {
        tbody.innerHTML = '<tr id="empty-cart-row"><td colspan="6" class="px-4 py-8 text-center text-gray-500">No products added yet. Search and add products above.</td></tr>';
        footer.classList.add('hidden');
        return;
    }
    
    footer.classList.remove('hidden');
    tbody.innerHTML = cart.map((item, index) => `
        <tr>
            <td class="px-4 py-2">${item.name}</td>
            <td class="px-4 py-2 text-sm text-gray-500">${item.sku}</td>
            <td class="px-4 py-2 text-center">LKR ${item.unit_price.toFixed(2)}</td>
            <td class="px-4 py-2 text-center">
                <div class="flex items-center justify-center gap-2">
                    <button type="button" onclick="updateQuantity(${index}, ${item.quantity - 1})" class="w-6 h-6 bg-gray-200 rounded hover:bg-gray-300">-</button>
                    <span class="w-12 text-center">${item.quantity}</span>
                    <button type="button" onclick="updateQuantity(${index}, ${item.quantity + 1})" class="w-6 h-6 bg-gray-200 rounded hover:bg-gray-300">+</button>
                </div>
            </td>
            <td class="px-4 py-2 text-right font-medium">LKR ${item.total_price.toFixed(2)}</td>
            <td class="px-4 py-2 text-center">
                <button type="button" onclick="removeFromCart(${index})" class="text-red-600 hover:text-red-800">Remove</button>
            </td>
        </tr>
    `).join('');
    
    calculateTotals();
}

// Update quantity
window.updateQuantity = function(index, newQuantity) {
    if (newQuantity < 1) {
        removeFromCart(index);
        return;
    }
    
    // Check stock (you may want to fetch current stock from server)
    if (newQuantity > 999) { // Placeholder max
        alert('Quantity exceeds available stock');
        return;
    }
    
    cart[index].quantity = newQuantity;
    cart[index].total_price = cart[index].quantity * cart[index].unit_price;
    updateCartDisplay();
};

// Remove from cart
window.removeFromCart = function(index) {
    cart.splice(index, 1);
    updateCartDisplay();
};

// Calculate totals
function calculateTotals() {
    const subtotal = cart.reduce((sum, item) => sum + item.total_price, 0);
    const shipping = parseFloat(document.getElementById('shipping_amount').value) || 0;
    const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
    const tax = subtotal * 0.08; // 8% tax
    const grandTotal = subtotal + shipping + tax - discount;
    
    document.getElementById('subtotal').innerHTML = `LKR ${subtotal.toFixed(2)}`;
    document.getElementById('tax_amount').innerHTML = `LKR ${tax.toFixed(2)}`;
    document.getElementById('grand_total').innerHTML = `LKR ${grandTotal.toFixed(2)}`;
    
    // Update tax input if needed
    if (!document.getElementById('tax_amount_input')) {
        const taxInput = document.createElement('input');
        taxInput.type = 'hidden';
        taxInput.name = 'tax_amount';
        taxInput.id = 'tax_amount_input';
        taxInput.value = tax.toFixed(2);
        document.getElementById('orderForm').appendChild(taxInput);
    } else {
        document.getElementById('tax_amount_input').value = tax.toFixed(2);
    }
}

// Listen for shipping and discount changes
document.getElementById('shipping_amount').addEventListener('input', calculateTotals);
document.getElementById('discount_amount').addEventListener('input', calculateTotals);

// Before submit, pack cart items into hidden input
document.getElementById('orderForm').addEventListener('submit', function(e) {
    if (cart.length === 0) {
        e.preventDefault();
        alert('Please add at least one product to the order');
        return false;
    }
    
    const itemsData = cart.map(item => ({
        product_id: item.product_id,
        quantity: item.quantity,
        unit_price: item.unit_price
    }));
    
    document.getElementById('items-input').value = JSON.stringify(itemsData);
});
</script>
@endpush

@push('styles')
<style>
    .form-radio {
        margin-right: 0.5rem;
    }
</style>
@endpush
@endsection