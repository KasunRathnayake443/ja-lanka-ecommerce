@extends('admin.layouts.app')

@section('page_title', 'Create Manual Order')

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


</div>
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

            {{-- Customer Information --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Customer Information</h3>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type</label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="user_type" value="existing" class="user-type-radio" checked>
                            <span class="ml-2">Existing User</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="user_type" value="guest" class="user-type-radio">
                            <span class="ml-2">Guest User</span>
                        </label>
                    </div>
                </div>

                {{-- Existing User --}}
                <div id="existing-user-section" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                    <select name="user_id" id="user_id" class="w-full md:w-1/2 px-3 py-2 border rounded-lg">
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                        @endforeach
                    </select>
                    <div id="user-loading" class="hidden mt-2 text-sm text-blue-600">Loading user details...</div>
                    <div id="user-error" class="hidden mt-2 text-sm text-red-600"></div>
                </div>

                {{-- Guest User --}}
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

                {{-- Shipping Address --}}
                <div class="mt-6">
                    <h4 class="font-medium mb-3">Shipping Address</h4>

                    <div id="address-selection" class="hidden mb-4 p-3 bg-blue-50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Saved Address</label>
                        <select id="saved_addresses" class="w-full md:w-2/3 px-3 py-2 border rounded-lg bg-white">
                            <option value="">-- Select a saved address --</option>
                        </select>
                        <input type="hidden" name="address_id" id="address_id">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="full_name" id="full_name" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number *</label>
                            <input type="text" name="mobile" id="mobile" class="w-full px-3 py-2 border rounded-lg">
                        </div>
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">District *</label>
                            <input type="text" name="district" id="district" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                            <input type="text" name="province" id="province" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                            <input type="text" name="postal_code" id="postal_code" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Instructions</label>
                            <textarea name="delivery_instructions" id="delivery_instructions" rows="2" class="w-full px-3 py-2 border rounded-lg"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Products --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Products</h3>

                <div class="mb-4" style="position: relative;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
                    <div class="flex gap-2">
                        <input type="text" id="product-search"
                               placeholder="Type product name or SKU..."
                               class="flex-1 px-3 py-2 border rounded-lg"
                               autocomplete="off">
                        <button type="button" id="search-product-btn"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Search</button>
                    </div>
                    <div id="search-loading" class="hidden mt-1 text-sm text-blue-600">Searching...</div>
                    <div id="search-error" class="hidden mt-1 text-sm text-red-600"></div>
                    <div id="search-results"
                         class="hidden"
                         style="position: absolute; top: 100%; left: 0; right: 0; z-index: 50;
                                background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem;
                                box-shadow: 0 10px 15px rgba(0,0,0,0.1); max-height: 300px; overflow-y: auto;">
                    </div>
                </div>

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
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    No products added yet. Search and add products above.
                                </td>
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
                                    <select name="shipping_method" id="shipping_method" class="w-full px-2 py-1 border rounded">
                                        <option value="standard">Standard (LKR 350)</option>
                                        <option value="express">Express (LKR 650)</option>
                                        <option value="pickup">Pickup (Free)</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-right font-medium">Coupon Code:</td>
                                <td class="px-4 py-2 text-right">
                                    <div class="flex gap-2">
                                        <input type="text" id="coupon_code" placeholder="Enter coupon code"
                                               class="flex-1 px-2 py-1 border rounded text-sm">
                                        <button type="button" id="apply-coupon"
                                                class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm">Apply</button>
                                        <button type="button" id="remove-coupon"
                                                class="hidden bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded text-sm">Remove</button>
                                    </div>
                                    <div id="coupon-feedback" class="text-xs mt-1 hidden"></div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-right font-medium">Manual Discount (LKR):</td>
                                <td class="px-4 py-2 text-right">
                                    <input type="number" name="manual_discount" id="manual_discount"
                                           value="0" step="0.01" min="0"
                                           class="w-32 px-2 py-1 border rounded text-right">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-right font-medium">Coupon Discount:</td>
                                <td class="px-4 py-2 text-right font-medium text-green-600" id="coupon_discount_display">LKR 0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-right font-medium">Tax (5%):</td>
                                <td class="px-4 py-2 text-right font-medium" id="tax_amount">LKR 0.00</td>
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

            {{-- Order Details --}}
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                        <select name="payment_method" class="w-full px-3 py-2 border rounded-lg">
                            <option value="card">Credit/Debit Card</option>
                            <option value="cod">Cash on Delivery</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-3 py-2 border rounded-lg"
                                  placeholder="Any special notes about this order..."></textarea>
                    </div>
                </div>
            </div>

            <input type="hidden" name="items" id="items-input">
            <input type="hidden" name="coupon_code" id="applied_coupon" value="">
            <input type="hidden" name="shipping_amount" id="shipping_amount_hidden" value="350">

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.orders.index') }}"
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</a>
                <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Create Order</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function() {
    let cart = [];
    let appliedCouponData = null;

    // All URLs built with url() — never route() with empty/dynamic params
    const searchUrl         = '{{ url("admin/orders/search-products") }}';
    const getUserUrl        = '{{ url("admin/orders/get-user-details") }}';
    const validateCouponUrl = '{{ url("admin/orders/validate-coupon") }}';
    const csrfToken         = '{{ csrf_token() }}';

    // ── HELPERS ───────────────────────────────────────────────────────────────
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        const d = document.createElement('div');
        d.textContent = String(text);
        return d.innerHTML;
    }
    function showEl(id)       { document.getElementById(id)?.classList.remove('hidden'); }
    function hideEl(id)       { document.getElementById(id)?.classList.add('hidden'); }
    function setVal(id, val)  { const el = document.getElementById(id); if (el) el.value = val ?? ''; }
    function setHtml(id, html){ const el = document.getElementById(id); if (el) el.innerHTML = html; }

    async function fetchJson(url, options = {}) {
        const res  = await fetch(url, options);
        const text = await res.text();
        try {
            return JSON.parse(text);
        } catch {
            console.error('Non-JSON from', url, ':', text.substring(0, 500));
            throw new Error('Server returned non-JSON. See console.');
        }
    }

    // ── CUSTOMER TYPE ─────────────────────────────────────────────────────────
    document.querySelectorAll('.user-type-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'existing') {
                showEl('existing-user-section');
                hideEl('guest-user-section');
            } else {
                hideEl('existing-user-section');
                showEl('guest-user-section');
                hideEl('address-selection');
                clearAddressFields();
            }
        });
    });

    // ── USER SELECT ───────────────────────────────────────────────────────────
    document.getElementById('user_id').addEventListener('change', async function () {
        const userId = this.value;
        hideEl('user-error');

        if (!userId) {
            hideEl('address-selection');
            clearAddressFields();
            return;
        }

        showEl('user-loading');

        try {
            const data = await fetchJson(`${getUserUrl}/${userId}`);
            hideEl('user-loading');

            if (data.default_address) {
                setVal('full_name', data.default_address.full_name || data.name);
                setVal('mobile',    data.default_address.mobile    || data.mobile);
                fillAddressFields(data.default_address);
            } else {
                setVal('full_name', data.name   || '');
                setVal('mobile',    data.mobile || '');
            }

            const addresses = data.addresses || [];
            if (addresses.length > 0) {
                const sel = document.getElementById('saved_addresses');
                sel.innerHTML =
                    '<option value="">-- Select a saved address --</option>' +
                    addresses.map(a =>
                        `<option value="${a.id}" data-address='${JSON.stringify(a).replace(/'/g, "&#39;")}'>
                            ${escapeHtml(a.label)} — ${escapeHtml(a.full_address)}${a.is_default ? ' ✓' : ''}
                        </option>`
                    ).join('');
                showEl('address-selection');
            } else {
                hideEl('address-selection');
            }

        } catch (err) {
            hideEl('user-loading');
            const el = document.getElementById('user-error');
            if (el) { el.textContent = 'Error: ' + err.message; showEl('user-error'); }
            console.error('User fetch error:', err);
        }
    });

    // ── SAVED ADDRESS DROPDOWN ────────────────────────────────────────────────
    document.getElementById('saved_addresses').addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (opt && opt.dataset.address) {
            try {
                const a = JSON.parse(opt.dataset.address);
                setVal('full_name',  a.full_name);
                setVal('mobile',     a.mobile);
                setVal('address_id', a.id);
                fillAddressFields(a);
            } catch (e) { console.error('Address parse error:', e); }
        } else {
            setVal('address_id', '');
        }
    });

    function fillAddressFields(a) {
        setVal('address_line1',         a.address_line1);
        setVal('address_line2',         a.address_line2);
        setVal('city',                  a.city);
        setVal('district',              a.district);
        setVal('province',              a.province);
        setVal('postal_code',           a.postal_code);
        setVal('delivery_instructions', a.delivery_instructions);
    }

    function clearAddressFields() {
        ['full_name','mobile','address_line1','address_line2',
         'city','district','province','postal_code','delivery_instructions']
            .forEach(id => setVal(id, ''));
    }

    // ── PRODUCT SEARCH ────────────────────────────────────────────────────────
    let searchTimeout;
    const searchInput   = document.getElementById('product-search');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        const term = this.value.trim();
        if (term.length >= 2) {
            searchTimeout = setTimeout(() => doSearch(term), 400);
        } else {
            searchResults.classList.add('hidden');
        }
    });

    document.getElementById('search-product-btn').addEventListener('click', function () {
        const term = searchInput.value.trim();
        if (term.length >= 2) doSearch(term);
        else alert('Please enter at least 2 characters');
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });

    async function doSearch(term) {
        showEl('search-loading');
        searchResults.classList.add('hidden');
        hideEl('search-error');

        try {
            const products = await fetchJson(`${searchUrl}?search=${encodeURIComponent(term)}`);
            hideEl('search-loading');

            if (!products || products.length === 0) {
                searchResults.innerHTML = '<div class="p-4 text-center text-gray-500">No products found</div>';
            } else {
                searchResults.innerHTML = products.map(p => {
                    const safeName = p.name.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
                    const safeSku  = p.sku.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
                    return `
                    <div class="p-3 border-b hover:bg-gray-50 flex justify-between items-center gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-sm">${escapeHtml(p.name)}</div>
                            <div class="text-xs text-gray-500">SKU: ${escapeHtml(p.sku)} | Stock: ${p.stock}</div>
                            <div class="text-xs font-medium text-gray-800">
                                LKR ${Number(p.current_price).toFixed(2)}
                                ${p.has_sale ? `<span class="text-green-600 ml-1">(-${p.discount_percent}% sale)</span>` : ''}
                            </div>
                        </div>
                        <div class="flex gap-2 items-center flex-shrink-0">
                            <input type="number" id="qty-${p.id}" value="1" min="1" max="${p.stock}"
                                   class="w-14 px-1 py-1 border rounded text-center text-sm">
                            <button type="button"
                                    onclick="window.addToCart(${p.id}, '${safeName}', '${safeSku}', ${p.current_price}, ${p.stock})"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 whitespace-nowrap">
                                Add
                            </button>
                        </div>
                    </div>`;
                }).join('');
            }

            searchResults.classList.remove('hidden');

        } catch (err) {
            hideEl('search-loading');
            const el = document.getElementById('search-error');
            if (el) { el.textContent = 'Search failed: ' + err.message; showEl('search-error'); }
            console.error('Search error:', err);
        }
    }

    // ── CART ──────────────────────────────────────────────────────────────────
    window.addToCart = function (id, name, sku, price, stock) {
        const qty = parseInt(document.getElementById(`qty-${id}`)?.value || 1);
        if (qty < 1)     { alert('Quantity must be at least 1'); return; }
        if (qty > stock) { alert(`Only ${stock} items in stock`); return; }

        const existing = cart.find(i => i.product_id === id);
        if (existing) {
            const newQty = existing.quantity + qty;
            if (newQty > stock) { alert(`Total would exceed stock (${stock})`); return; }
            existing.quantity    = newQty;
            existing.total_price = newQty * existing.unit_price;
        } else {
            cart.push({ product_id: id, name, sku, unit_price: price, quantity: qty, total_price: price * qty });
        }

        searchResults.classList.add('hidden');
        searchInput.value = '';
        resetCoupon();
        updateCartDisplay();
    };

    window.updateQty = function (idx, newQty) {
        if (newQty < 1) { removeFromCart(idx); return; }
        cart[idx].quantity    = newQty;
        cart[idx].total_price = newQty * cart[idx].unit_price;
        updateCartDisplay();
    };

    window.removeFromCart = function (idx) {
        cart.splice(idx, 1);
        updateCartDisplay();
    };

    function updateCartDisplay() {
        const tbody  = document.getElementById('cart-items');
        const footer = document.getElementById('cart-footer');

        if (cart.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">
                No products added yet. Search and add products above.</td></tr>`;
            footer.classList.add('hidden');
            return;
        }

        footer.classList.remove('hidden');
        tbody.innerHTML = cart.map((item, idx) => `
            <tr class="border-b">
                <td class="px-4 py-2 text-sm">${escapeHtml(item.name)}</td>
                <td class="px-4 py-2 text-xs text-gray-500">${escapeHtml(item.sku)}</td>
                <td class="px-4 py-2 text-center text-sm">LKR ${item.unit_price.toFixed(2)}</td>
                <td class="px-4 py-2 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <button type="button" onclick="window.updateQty(${idx}, ${item.quantity - 1})"
                                class="w-6 h-6 bg-gray-200 rounded hover:bg-gray-300 text-sm font-bold">−</button>
                        <span class="w-10 text-center text-sm">${item.quantity}</span>
                        <button type="button" onclick="window.updateQty(${idx}, ${item.quantity + 1})"
                                class="w-6 h-6 bg-gray-200 rounded hover:bg-gray-300 text-sm font-bold">+</button>
                    </div>
                </td>
                <td class="px-4 py-2 text-right text-sm font-medium">LKR ${item.total_price.toFixed(2)}</td>
                <td class="px-4 py-2 text-center">
                    <button type="button" onclick="window.removeFromCart(${idx})"
                            class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                </td>
            </tr>
        `).join('');

        calculateTotals();
    }

    // ── TOTALS ────────────────────────────────────────────────────────────────
    function calculateTotals() {
        const subtotal       = cart.reduce((s, i) => s + i.total_price, 0);
        const method         = document.getElementById('shipping_method').value;
        const shipping       = method === 'express' ? 650 : method === 'standard' ? 350 : 0;
        const couponDiscount = appliedCouponData ? appliedCouponData.discount_amount : 0;
        const manualDiscount = parseFloat(document.getElementById('manual_discount').value) || 0;
        const afterDiscount  = Math.max(0, subtotal - couponDiscount - manualDiscount);
        const tax            = afterDiscount * 0.05;
        const grandTotal     = afterDiscount + shipping + tax;

        setHtml('subtotal',    `LKR ${subtotal.toFixed(2)}`);
        setHtml('tax_amount',  `LKR ${tax.toFixed(2)}`);
        setHtml('grand_total', `LKR ${grandTotal.toFixed(2)}`);

        const shippingEl = document.getElementById('shipping_amount_hidden');
        if (shippingEl) shippingEl.value = shipping;
    }

    document.getElementById('shipping_method').addEventListener('change', calculateTotals);
    document.getElementById('manual_discount').addEventListener('input',  calculateTotals);

    // ── COUPON ────────────────────────────────────────────────────────────────
    function resetCoupon() {
        if (!appliedCouponData) return;
        appliedCouponData = null;
        setVal('applied_coupon', '');
        document.getElementById('coupon_code').value     = '';
        document.getElementById('coupon_code').disabled  = false;
        document.getElementById('apply-coupon').classList.remove('hidden');
        document.getElementById('remove-coupon').classList.add('hidden');
        setHtml('coupon_discount_display', 'LKR 0.00');
        hideEl('coupon-feedback');
        calculateTotals();
    }

    document.getElementById('apply-coupon').addEventListener('click', async function () {
        const code     = document.getElementById('coupon_code').value.trim();
        const feedback = document.getElementById('coupon-feedback');
        if (!code) { alert('Please enter a coupon code'); return; }

        const subtotal = cart.reduce((s, i) => s + i.total_price, 0);
        if (subtotal === 0) { alert('Add products before applying a coupon'); return; }

        try {
            const data = await fetchJson(validateCouponUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ code, subtotal }),
            });

            if (data.valid) {
                appliedCouponData = data;
                setVal('applied_coupon', code);
                document.getElementById('coupon_code').disabled = true;
                document.getElementById('apply-coupon').classList.add('hidden');
                document.getElementById('remove-coupon').classList.remove('hidden');
                setHtml('coupon_discount_display', `LKR ${data.discount_amount.toFixed(2)}`);
                feedback.textContent = `Coupon applied! Saved LKR ${data.discount_amount.toFixed(2)}`;
                feedback.className   = 'text-xs mt-1 text-green-600';
                calculateTotals();
            } else {
                feedback.textContent = data.message || 'Invalid coupon code';
                feedback.className   = 'text-xs mt-1 text-red-600';
            }
            showEl('coupon-feedback');
            setTimeout(() => hideEl('coupon-feedback'), 4000);

        } catch (err) {
            console.error('Coupon error:', err);
            feedback.textContent = 'Error validating coupon. See console.';
            feedback.className   = 'text-xs mt-1 text-red-600';
            showEl('coupon-feedback');
        }
    });

    document.getElementById('remove-coupon').addEventListener('click', resetCoupon);

    // ── SUBMIT ────────────────────────────────────────────────────────────────
    document.getElementById('orderForm').addEventListener('submit', function (e) {
        if (cart.length === 0) {
            e.preventDefault();
            alert('Please add at least one product');
            return;
        }
        document.getElementById('items-input').value = JSON.stringify(
            cart.map(i => ({ product_id: i.product_id, quantity: i.quantity, unit_price: i.unit_price }))
        );
    });

})();
</script>
@endpush
@endsection