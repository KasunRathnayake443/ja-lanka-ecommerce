<?php $__env->startSection('title', 'Sale Items'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 pb-24">
    <!-- Header with Back Button -->
    <div class="flex items-center gap-4 mb-6">
        <a href="<?php echo e(route('mobile.home')); ?>" class="text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-bold">🔥 Sale Items</h1>
    </div>
    
    <!-- Sale Products List -->
    <div x-data="{ products: [] }" x-init="
        fetch('/api/products?page=1')
            .then(res => res.json())
            .then(data => { products = data.data.filter(p => p.sale_price); })
    ">
        <div x-show="products.length === 0" class="text-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600 mx-auto"></div>
            <p class="text-gray-500 mt-2">Loading sale items...</p>
        </div>
        
        <div class="space-y-3" x-show="products.length > 0">
            <template x-for="product in products" :key="product.id">
                <a :href="'/mobile/product/' + product.slug" class="bg-white rounded-lg shadow p-3 flex gap-3">
                    <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        <img :src="product.images && product.images[0] ? '/storage/' + product.images[0].image_path : '/images/placeholder.jpg'" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h3 class="font-semibold text-sm flex-1" x-text="product.name"></h3>
                            <span class="bg-red-600 text-white text-xs px-2 py-0.5 rounded" x-text="'-' + product.discount_percent + '%'"></span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1" x-show="product.origin" x-text="(product.origin?.flag_icon || '🌏') + ' ' + (product.origin?.country_name || '')"></div>
                        <div class="mt-2">
                            <span class="text-red-600 font-bold" x-text="'LKR ' + new Number(product.sale_price).toLocaleString()"></span>
                            <span class="text-gray-400 text-sm line-through ml-2" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                        </div>
                        <button @click.prevent="addToCart(product.id)" class="mt-2 w-full bg-red-600 text-white py-1.5 rounded text-sm">
                            Add to Cart
                        </button>
                    </div>
                </a>
            </template>
        </div>
    </div>
</div>

<script>
async function addToCart(productId) {
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
            showToast('Added to cart!');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error adding to cart', 'error');
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-20 left-4 right-4 text-center px-4 py-2 rounded-lg text-white ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} z-50`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\Herd\ja-lanka-ecommerce\resources\views/mobile/sale.blade.php ENDPATH**/ ?>