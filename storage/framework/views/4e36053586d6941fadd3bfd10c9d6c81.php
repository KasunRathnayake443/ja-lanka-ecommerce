<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>
<div class="pb-20">
    
    <!-- Hero Banner Slider -->
    <div class="relative" x-data="{ currentSlide: 0, slides: [] }" x-init="
        fetch('/api/banners')
            .then(res => res.json())
            .then(data => { slides = data; if(slides.length) setInterval(() => { currentSlide = (currentSlide + 1) % slides.length }, 5000); })
    ">
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="currentSlide === index" class="relative" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <img :src="'/storage/' + slide.image" class="w-full h-48 object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-center items-center text-white p-4">
                    <h2 class="text-xl font-bold text-center" x-text="slide.title"></h2>
                    <p class="text-sm text-center mt-1" x-text="slide.subtitle" x-show="slide.subtitle"></p>
                    <a :href="slide.button_link || '/mobile/shop'" class="mt-3 bg-red-600 px-4 py-1 rounded-full text-sm" x-show="slide.button_text" x-text="slide.button_text"></a>
                </div>
            </div>
        </template>
        
        <!-- Dots -->
        <div class="absolute bottom-2 left-0 right-0 flex justify-center gap-1">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="currentSlide = index" class="w-2 h-2 rounded-full transition" :class="currentSlide === index ? 'bg-red-600 w-4' : 'bg-white'"></button>
            </template>
        </div>
        
        <!-- Loading state -->
        <div x-show="slides.length === 0" class="bg-gray-200 h-48 flex items-center justify-center">
            <div class="text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600 mx-auto"></div>
                <p class="text-xs text-gray-500 mt-2">Loading banners...</p>
            </div>
        </div>
    </div>
    
    <!-- Quick Action Buttons -->
    <div class="grid grid-cols-4 gap-2 p-4 bg-white border-b">
        <a href="<?php echo e(route('mobile.shop')); ?>?type=food" class="text-center">
            <div class="bg-red-50 rounded-full w-12 h-12 flex items-center justify-center mx-auto">
                <span class="text-2xl">🍜</span>
            </div>
            <span class="text-xs text-gray-700 mt-1 block">Food</span>
        </a>
        <a href="<?php echo e(route('mobile.shop')); ?>?type=appliance" class="text-center">
            <div class="bg-blue-50 rounded-full w-12 h-12 flex items-center justify-center mx-auto">
                <span class="text-2xl">🔌</span>
            </div>
            <span class="text-xs text-gray-700 mt-1 block">Appliances</span>
        </a>
        <a href="<?php echo e(route('mobile.sale')); ?>" class="text-center">
            <div class="bg-orange-50 rounded-full w-12 h-12 flex items-center justify-center mx-auto">
                <span class="text-2xl">🔥</span>
            </div>
            <span class="text-xs text-gray-700 mt-1 block">Sale</span>
        </a>
        <a href="<?php echo e(route('mobile.shop')); ?>?sort=newest" class="text-center">
            <div class="bg-green-50 rounded-full w-12 h-12 flex items-center justify-center mx-auto">
                <span class="text-2xl">✨</span>
            </div>
            <span class="text-xs text-gray-700 mt-1 block">New</span>
        </a>
    </div>
    
    <!-- Flash Sale Banners Section -->
    <div class="mt-4 px-4" x-data="{ flashSales: [] }" x-init="
        fetch('/api/flash-sales')
            .then(res => res.json())
            .then(data => { flashSales = data; })
    ">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-lg font-semibold">🔥 Flash Sale</h3>
            <a href="<?php echo e(route('mobile.sale')); ?>" class="text-xs text-red-600">See All →</a>
        </div>
        
        <div x-show="flashSales.length === 0" class="bg-gray-50 rounded-lg p-8 text-center">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-red-600 mx-auto"></div>
            <p class="text-xs text-gray-500 mt-2">Loading deals...</p>
        </div>
        
        <div class="space-y-3" x-show="flashSales.length > 0">
            <template x-for="banner in flashSales" :key="banner.id">
                <a :href="'/mobile/product/' + banner.product.slug" class="bg-gradient-to-r from-red-50 to-orange-50 rounded-xl p-3 flex gap-3 shadow-sm">
                    <div class="w-20 h-20 bg-white rounded-lg overflow-hidden">
                        <img :src="banner.product.images && banner.product.images[0] ? '/storage/' + banner.product.images[0].image_path : '/images/placeholder.jpg'" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h4 class="font-semibold text-sm" x-text="banner.custom_title || banner.product.name"></h4>
                            <span class="bg-red-600 text-white text-xs px-2 py-0.5 rounded-full">Sale</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1" x-show="banner.custom_subtitle" x-text="banner.custom_subtitle"></p>
                        <div class="mt-2">
                            <span class="text-red-600 font-bold text-sm" x-text="'LKR ' + new Number(banner.product.sale_price).toLocaleString()"></span>
                            <span class="text-gray-400 text-xs line-through ml-2" x-text="'LKR ' + new Number(banner.product.regular_price).toLocaleString()"></span>
                            <span class="ml-2 bg-red-100 text-red-600 text-xs px-1 rounded" x-text="'-' + banner.product.discount_percent + '%'"></span>
                        </div>
                    </div>
                </a>
            </template>
        </div>
    </div>
    
    <!-- Featured Products Section -->
    <div class="mt-6 px-4" x-data="{ featured: [] }" x-init="
        fetch('/api/products?page=1&limit=6')
            .then(res => res.json())
            .then(data => { featured = data.data; })
    ">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-lg font-semibold">✨ Featured for You</h3>
            <a href="<?php echo e(route('mobile.shop')); ?>" class="text-xs text-red-600">See All →</a>
        </div>
        
        <div x-show="featured.length === 0" class="bg-gray-50 rounded-lg p-8 text-center">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-red-600 mx-auto"></div>
            <p class="text-xs text-gray-500 mt-2">Loading products...</p>
        </div>
        
        <div class="space-y-3" x-show="featured.length > 0">
            <template x-for="product in featured" :key="product.id">
                <a :href="'/mobile/product/' + product.slug" class="bg-white rounded-lg shadow p-3 flex gap-3">
                    <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        <img :src="product.images && product.images[0] ? '/storage/' + product.images[0].image_path : '/images/placeholder.jpg'" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-1">
                            <h4 class="font-semibold text-sm flex-1 line-clamp-2" x-text="product.name"></h4>
                            <span x-show="product.discount_percent > 0" class="bg-red-600 text-white text-xs px-2 py-0.5 rounded" x-text="'-' + product.discount_percent + '%'"></span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1" x-show="product.origin" x-text="(product.origin?.flag_icon || '🌏') + ' ' + (product.origin?.country_name || '')"></div>
                        <div class="mt-2">
                            <template x-if="product.sale_price">
                                <div>
                                    <span class="text-red-600 font-bold text-sm" x-text="'LKR ' + new Number(product.sale_price).toLocaleString()"></span>
                                    <span class="text-gray-400 text-xs line-through ml-2" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                                </div>
                            </template>
                            <template x-if="!product.sale_price">
                                <span class="text-gray-800 font-bold text-sm" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                            </template>
                        </div>
                    </div>
                </a>
            </template>
        </div>
    </div>
    
    <!-- New Arrivals Section -->
    <div class="mt-6 px-4 pb-6" x-data="{ newArrivals: [] }" x-init="
        fetch('/api/products?page=1&sort=newest')
            .then(res => res.json())
            .then(data => { newArrivals = data.data; })
    ">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-lg font-semibold">🆕 New Arrivals</h3>
            <a href="<?php echo e(route('mobile.shop')); ?>?sort=newest" class="text-xs text-red-600">See All →</a>
        </div>
        
        <div x-show="newArrivals.length === 0" class="bg-gray-50 rounded-lg p-8 text-center">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-red-600 mx-auto"></div>
            <p class="text-xs text-gray-500 mt-2">Loading new products...</p>
        </div>
        
        <div class="space-y-3" x-show="newArrivals.length > 0">
            <template x-for="product in newArrivals.slice(0, 6)" :key="product.id">
                <a :href="'/mobile/product/' + product.slug" class="bg-white rounded-lg shadow p-3 flex gap-3">
                    <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        <img :src="product.images && product.images[0] ? '/storage/' + product.images[0].image_path : '/images/placeholder.jpg'" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-1">
                            <h4 class="font-semibold text-sm flex-1 line-clamp-2" x-text="product.name"></h4>
                            <span x-show="product.discount_percent > 0" class="bg-red-600 text-white text-xs px-2 py-0.5 rounded" x-text="'-' + product.discount_percent + '%'"></span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1" x-show="product.origin" x-text="(product.origin?.flag_icon || '🌏') + ' ' + (product.origin?.country_name || '')"></div>
                        <div class="mt-2">
                            <template x-if="product.sale_price">
                                <div>
                                    <span class="text-red-600 font-bold text-sm" x-text="'LKR ' + new Number(product.sale_price).toLocaleString()"></span>
                                    <span class="text-gray-400 text-xs line-through ml-2" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                                </div>
                            </template>
                            <template x-if="!product.sale_price">
                                <span class="text-gray-800 font-bold text-sm" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                            </template>
                        </div>
                    </div>
                </a>
            </template>
        </div>
    </div>
    
</div>

<script>
// Add to cart function for mobile
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
            updateCartCount(data.cart_count);
            showToast('Added to cart!');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error adding to cart', 'error');
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
    toast.className = `fixed bottom-20 left-4 right-4 text-center px-4 py-2 rounded-lg text-white ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} z-50 transition-opacity`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/mobile/home.blade.php ENDPATH**/ ?>