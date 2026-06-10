<?php $__env->startSection('title', 'Cart'); ?>

<?php $__env->startSection('content'); ?>
<div class="pb-24">
    <h1 class="text-xl font-bold p-4 border-b bg-white sticky top-0 z-10">My Cart</h1>
    
    <div id="cartItems" class="px-4 space-y-4">
        <div class="text-center py-8 text-gray-500">Loading...</div>
    </div>
    
    <div id="emptyCart" class="text-center py-12 hidden px-4">
        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        <p class="text-gray-500">Your cart is empty</p>
        <a href="<?php echo e(route('mobile.shop')); ?>" class="inline-block mt-4 text-red-600 hover:underline">Start Shopping</a>
    </div>
    
    <!-- Cart Summary - Normal block element that scrolls with content -->
    <div id="cartSummary" class="bg-white border-t shadow-lg p-4 mt-4 hidden">
        <div class="flex justify-between mb-3">
            <span class="text-gray-600">Subtotal:</span>
            <span id="mobileSubtotal" class="font-semibold">LKR 0</span>
        </div>
        <div class="flex justify-between mb-3">
            <span class="text-gray-600">Shipping:</span>
            <span id="mobileShipping" class="font-semibold">Calculated at checkout</span>
        </div>
        <div class="flex justify-between border-t pt-3 mb-4">
            <span class="text-lg font-bold">Total:</span>
            <span id="mobileTotal" class="text-lg font-bold text-red-600">LKR 0</span>
        </div>
        <button onclick="proceedToCheckout()" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">
            Proceed to Checkout
        </button>
    </div>
</div>

<script>
async function loadMobileCart() {
    try {
        const response = await fetch('/api/cart');
        const data = await response.json();
        
        const cartItemsDiv = document.getElementById('cartItems');
        const emptyCartDiv = document.getElementById('emptyCart');
        const cartSummary = document.getElementById('cartSummary');
        
        // Filter out items with null product (deleted products)
        const validItems = data.items.filter(item => item.product !== null);
        
        if (validItems.length === 0) {
            cartItemsDiv.classList.add('hidden');
            emptyCartDiv.classList.remove('hidden');
            cartSummary.classList.add('hidden');
            return;
        }
        
        cartItemsDiv.classList.remove('hidden');
        emptyCartDiv.classList.add('hidden');
        cartSummary.classList.remove('hidden');
        
        let itemsHtml = '';
        let validTotal = 0;
        
        validItems.forEach(item => {
            // Skip if product is null (safety check)
            if (!item.product) return;
            
            const imageUrl = item.product.images && item.product.images[0] 
                ? `/storage/${item.product.images[0].image_path}` 
                : '/images/placeholder.jpg';
            
            // Calculate total for valid items only
            validTotal += item.quantity * parseFloat(item.price);
            
            itemsHtml += `
                <div class="bg-white rounded-lg shadow p-3 flex gap-3" id="mobile-cart-item-${item.id}">
                    <img src="${imageUrl}" class="w-20 h-20 object-cover rounded">
                    <div class="flex-1">
                        <h3 class="font-semibold text-sm">${escapeHtml(item.product.name)}</h3>
                        <p class="text-red-600 font-bold mt-1">LKR ${parseFloat(item.price).toLocaleString()}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <button onclick="updateMobileCartQuantity(${item.id}, ${item.quantity - 1})" class="w-8 h-8 border rounded hover:bg-gray-100">-</button>
                            <span class="w-8 text-center">${item.quantity}</span>
                            <button onclick="updateMobileCartQuantity(${item.id}, ${item.quantity + 1})" class="w-8 h-8 border rounded hover:bg-gray-100">+</button>
                            <button onclick="removeFromMobileCart(${item.id})" class="ml-auto text-red-500 text-sm hover:underline">Remove</button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        cartItemsDiv.innerHTML = itemsHtml;
        
        // Update totals with valid items only
        document.getElementById('mobileSubtotal').innerText = `LKR ${validTotal.toLocaleString()}`;
        document.getElementById('mobileTotal').innerText = `LKR ${validTotal.toLocaleString()}`;
        
        // Update cart count with valid items only
        const validCount = validItems.reduce((sum, item) => sum + item.quantity, 0);
        updateCartCount(validCount);
        
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('cartItems').innerHTML = '<div class="text-center py-8 text-red-500">Error loading cart</div>';
    }
}

// Helper function to escape HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

async function updateMobileCartQuantity(itemId, newQuantity) {
    if (newQuantity < 1) {
        removeFromMobileCart(itemId);
        return;
    }
    
    try {
        const response = await fetch(`/api/cart/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity: newQuantity })
        });
        
        const data = await response.json();
        if (data.success) {
            updateCartCount(data.cart_count);
            loadMobileCart();
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error updating cart', 'error');
    }
}

async function removeFromMobileCart(itemId) {
    try {
        const response = await fetch(`/api/cart/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        if (data.success) {
            updateCartCount(data.cart_count);
            loadMobileCart();
            showToast('Item removed from cart');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error removing item', 'error');
    }
}

function updateCartCount(count) {
    const cartCount = document.getElementById('mobileCartCount');
    if (cartCount) {
        if (count > 0) {
            cartCount.textContent = count;
            cartCount.classList.remove('hidden');
        } else {
            cartCount.classList.add('hidden');
        }
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-20 left-4 right-4 text-center px-4 py-2 rounded-lg text-white ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} z-50`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}

function proceedToCheckout() {
    window.location.href = '/mobile/checkout';
}

loadMobileCart();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\Herd\ja-lanka-ecommerce\resources\views/mobile/cart.blade.php ENDPATH**/ ?>