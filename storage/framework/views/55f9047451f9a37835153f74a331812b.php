

<?php $__env->startSection('title', 'Wishlist'); ?>

<?php $__env->startSection('content'); ?>
<div class="pb-24">
    <h1 class="text-xl font-bold p-4 border-b bg-white sticky top-0 z-10">My Wishlist</h1>
    
    <div id="wishlistItems" class="px-4 space-y-4">
        <div class="text-center py-8 text-gray-500">Loading...</div>
    </div>
    
    <div id="emptyWishlist" class="text-center py-12 hidden px-4">
        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
        </svg>
        <p class="text-gray-500">Your wishlist is empty</p>
        <a href="<?php echo e(route('mobile.shop')); ?>" class="inline-block mt-4 text-red-600 hover:underline">Start Shopping</a>
    </div>
</div>

<script>
async function loadMobileWishlist() {
    // Check if user is logged in
    const isLoggedIn = <?php echo e(auth()->check() ? 'true' : 'false'); ?>;
    
    if (!isLoggedIn) {
        document.getElementById('wishlistItems').innerHTML = `
            <div class="text-center py-12">
                <p class="text-gray-500 mb-4">Please login to view your wishlist</p>
                <a href="<?php echo e(route('login')); ?>" class="inline-block bg-red-600 text-white px-6 py-2 rounded-lg">Login</a>
            </div>
        `;
        return;
    }
    
    try {
        const response = await fetch('/api/wishlist');
        const data = await response.json();
        
        const wishlistDiv = document.getElementById('wishlistItems');
        const emptyDiv = document.getElementById('emptyWishlist');
        
        if (data.length === 0) {
            wishlistDiv.classList.add('hidden');
            emptyDiv.classList.remove('hidden');
            return;
        }
        
        wishlistDiv.classList.remove('hidden');
        emptyDiv.classList.add('hidden');
        
        let itemsHtml = '';
        data.forEach(item => {
            const product = item.product;
            const imageUrl = product.images && product.images[0] 
                ? `/storage/${product.images[0].image_path}` 
                : '/images/placeholder.jpg';
            
            itemsHtml += `
                <div class="bg-white rounded-lg shadow p-3 flex gap-3" id="mobile-wishlist-item-${product.id}">
                    <a href="/mobile/product/${product.slug}" class="w-20 h-20 flex-shrink-0">
                        <img src="${imageUrl}" class="w-full h-full object-cover rounded">
                    </a>
                    <div class="flex-1">
                        <a href="/mobile/product/${product.slug}">
                            <h3 class="font-semibold text-sm">${escapeHtml(product.name)}</h3>
                        </a>
                        <p class="text-red-600 font-bold mt-1">LKR ${parseFloat(product.regular_price).toLocaleString()}</p>
                        <div class="flex gap-2 mt-2">
                            <button onclick="addToCartFromWishlist(${product.id})" class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                                Add to Cart
                            </button>
                            <button onclick="removeFromWishlist(${product.id})" class="text-red-500 text-xs hover:underline">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        wishlistDiv.innerHTML = itemsHtml;
        
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('wishlistItems').innerHTML = '<div class="text-center py-8 text-red-500">Error loading wishlist</div>';
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

async function addToCartFromWishlist(productId) {
    try {
        const response = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        });
        
        const data = await response.json();
        if (data.success) {
            updateCartCount(data.cart_count);
            showToast('Added to cart!');
            await removeFromWishlist(productId);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error adding to cart', 'error');
    }
}

async function removeFromWishlist(productId) {
    try {
        const response = await fetch(`/api/wishlist/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        if (data.success) {
            document.getElementById(`mobile-wishlist-item-${productId}`)?.remove();
            showToast('Removed from wishlist');
            
            // Check if wishlist is empty
            const remainingItems = document.querySelectorAll('#wishlistItems > div').length;
            if (remainingItems === 0) {
                loadMobileWishlist();
            }
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

loadMobileWishlist();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/mobile/wishlist.blade.php ENDPATH**/ ?>