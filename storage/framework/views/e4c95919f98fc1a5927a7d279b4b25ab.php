<?php $__env->startSection('title', 'Home - Global Flavors Mart'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Banner Slider -->
<div class="relative" x-data="{ currentSlide: 0, slides: [] }" x-init="
    fetch('/api/banners')
        .then(res => res.json())
        .then(data => { slides = data; if(slides.length) setInterval(() => { currentSlide = (currentSlide + 1) % slides.length }, 5000); })
">
    <template x-for="(slide, index) in slides" :key="index">
        <div x-show="currentSlide === index" class="relative" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <img :src="'/storage/' + slide.image" class="w-full h-[500px] object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-center items-center text-white">
                <div class="text-center max-w-2xl px-6">
                    <h2 class="text-4xl md:text-5xl font-bold mb-4" x-text="slide.title"></h2>
                    <p class="text-lg md:text-xl mb-6" x-text="slide.subtitle" x-show="slide.subtitle"></p>
                    <a :href="slide.button_link || '/shop'" class="inline-block bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg font-semibold transition" x-show="slide.button_text" x-text="slide.button_text"></a>
                </div>
            </div>
        </div>
    </template>
    
    <!-- Dots -->
    <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
        <template x-for="(slide, index) in slides" :key="index">
            <button @click="currentSlide = index" class="w-3 h-3 rounded-full transition" :class="currentSlide === index ? 'bg-red-600 w-6' : 'bg-white'"></button>
        </template>
    </div>
    
    <!-- Loading state -->
    <div x-show="slides.length === 0" class="bg-gray-200 h-[500px] flex items-center justify-center">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600 mx-auto"></div>
            <p class="text-gray-500 mt-4">Loading banners...</p>
        </div>
    </div>
</div>

<!-- Category Showcase -->
<div class="container mx-auto px-6 py-16">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Shop by Category</h2>
        <p class="text-gray-500">Explore our curated collection from around the world</p>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <a href="<?php echo e(route('shop')); ?>?type=food" class="group">
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-8 text-center text-white transform transition hover:scale-105">
                <div class="text-6xl mb-4">🍜</div>
                <h3 class="text-xl font-bold">Food Items</h3>
                <p class="text-sm mt-2 opacity-90">Japanese, Korean, Italian & more</p>
            </div>
        </a>
        
        <a href="<?php echo e(route('shop')); ?>?type=appliance" class="group">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-8 text-center text-white transform transition hover:scale-105">
                <div class="text-6xl mb-4">🔌</div>
                <h3 class="text-xl font-bold">Appliances</h3>
                <p class="text-sm mt-2 opacity-90">Premium kitchen appliances</p>
            </div>
        </a>
        
        <a href="<?php echo e(route('mobile.sale')); ?>" class="group">
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-8 text-center text-white transform transition hover:scale-105">
                <div class="text-6xl mb-4">🔥</div>
                <h3 class="text-xl font-bold">Sale</h3>
                <p class="text-sm mt-2 opacity-90">Limited time offers</p>
            </div>
        </a>
        
        <a href="<?php echo e(route('shop')); ?>?sort=newest" class="group">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-8 text-center text-white transform transition hover:scale-105">
                <div class="text-6xl mb-4">✨</div>
                <h3 class="text-xl font-bold">New Arrivals</h3>
                <p class="text-sm mt-2 opacity-90">Fresh from around the world</p>
            </div>
        </a>
    </div>
</div>

<!-- Flash Sale Section -->
<div class="bg-gradient-to-r from-red-50 to-orange-50 py-16">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">🔥 Flash Sale</h2>
            <p class="text-gray-500">Limited time offers - Grab them before they're gone!</p>
        </div>
        
        <div x-data="{ flashSales: [] }" x-init="
            fetch('/api/flash-sales')
                .then(res => res.json())
                .then(data => { flashSales = data; })
        ">
            <div x-show="flashSales.length === 0" class="text-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600 mx-auto"></div>
                <p class="text-gray-500 mt-4">Loading deals...</p>
            </div>
            
            <div x-show="flashSales.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="banner in flashSales" :key="banner.id">
                    <a :href="'/mobile/sale'" class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition hover:scale-105 hover:shadow-xl">
                        <div class="relative">
                            <div class="absolute top-4 left-4">
                                <span class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full">🔥 FLASH SALE</span>
                            </div>
                            <div class="p-6">
                                <div class="flex gap-4">
                                    <div class="w-24 h-24 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                                        <img :src="banner.product.images && banner.product.images[0] ? '/storage/' + banner.product.images[0].image_path : '/images/placeholder.jpg'" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-bold text-lg" x-text="banner.custom_title || banner.product.name"></h3>
                                        <p class="text-sm text-gray-500 mt-1" x-show="banner.custom_subtitle" x-text="banner.custom_subtitle"></p>
                                        <div class="mt-3">
                                            <span class="text-2xl font-bold text-red-600" x-text="'LKR ' + new Number(banner.product.sale_price).toLocaleString()"></span>
                                            <span class="text-gray-400 line-through ml-2" x-text="'LKR ' + new Number(banner.product.regular_price).toLocaleString()"></span>
                                            <span class="ml-2 bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full" x-text="'-' + banner.product.discount_percent + '%'"></span>
                                        </div>
                                        <div class="mt-3 flex items-center gap-2 text-xs text-gray-500">
                                            <span>⏱️ Limited time offer</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
            
            <div class="text-center mt-8" x-show="flashSales.length > 0">
                <a href="<?php echo e(route('mobile.sale')); ?>" class="inline-block bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                    View All Sale Items →
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="container mx-auto px-6 py-16">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">✨ Featured Products</h2>
        <p class="text-gray-500">Hand-picked favorites just for you</p>
    </div>
    
    <div x-data="{ featured: [] }" x-init="
        fetch('/api/products?page=1&limit=8')
            .then(res => res.json())
            .then(data => { featured = data.data; })
    ">
        <div x-show="featured.length === 0" class="text-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600 mx-auto"></div>
            <p class="text-gray-500 mt-4">Loading products...</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" x-show="featured.length > 0">
            <template x-for="product in featured" :key="product.id">
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                    <a :href="'/product/' + product.slug">
                        <div class="relative h-48 overflow-hidden">
                            <img :src="product.images && product.images[0] ? '/storage/' + product.images[0].image_path : '/images/placeholder.jpg'" 
                                 class="w-full h-full object-cover hover:scale-105 transition duration-300">
                            <div x-show="product.discount_percent > 0" class="absolute top-2 right-2">
                                <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded" x-text="'-' + product.discount_percent + '%'"></span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="text-sm text-gray-500 mb-1" x-show="product.origin" x-text="(product.origin?.flag_icon || '🌏') + ' ' + (product.origin?.country_name || '')"></div>
                            <h3 class="font-semibold text-gray-800 mb-1 line-clamp-2" x-text="product.name"></h3>
                            <div class="mt-2">
                                <template x-if="product.sale_price">
                                    <div>
                                        <span class="text-red-600 font-bold text-lg" x-text="'LKR ' + new Number(product.sale_price).toLocaleString()"></span>
                                        <span class="text-gray-400 text-sm line-through ml-2" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                                    </div>
                                </template>
                                <template x-if="!product.sale_price">
                                    <span class="text-gray-800 font-bold text-lg" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                                </template>
                            </div>
                            <button @click.prevent="addToCart(product.id)" class="mt-3 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-semibold transition">
                                Add to Cart
                            </button>
                        </div>
                    </a>
                </div>
            </template>
        </div>
    </div>
</div>

<!-- New Arrivals Section -->
<div class="bg-gray-50 py-16">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">🆕 New Arrivals</h2>
            <p class="text-gray-500">Fresh products just added to our collection</p>
        </div>
        
        <div x-data="{ newArrivals: [] }" x-init="
            fetch('/api/products?page=1&sort=newest')
                .then(res => res.json())
                .then(data => { newArrivals = data.data; })
        ">
            <div x-show="newArrivals.length === 0" class="text-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600 mx-auto"></div>
                <p class="text-gray-500 mt-4">Loading products...</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" x-show="newArrivals.length > 0">
                <template x-for="product in newArrivals.slice(0, 8)" :key="product.id">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                        <a :href="'/product/' + product.slug">
                            <div class="relative h-48 overflow-hidden">
                                <img :src="product.images && product.images[0] ? '/storage/' + product.images[0].image_path : '/images/placeholder.jpg'" 
                                     class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                <div class="absolute top-2 left-2">
                                    <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">NEW</span>
                                </div>
                                <div x-show="product.discount_percent > 0" class="absolute top-2 right-2">
                                    <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded" x-text="'-' + product.discount_percent + '%'"></span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="text-sm text-gray-500 mb-1" x-show="product.origin" x-text="(product.origin?.flag_icon || '🌏') + ' ' + (product.origin?.country_name || '')"></div>
                                <h3 class="font-semibold text-gray-800 mb-1 line-clamp-2" x-text="product.name"></h3>
                                <div class="mt-2">
                                    <template x-if="product.sale_price">
                                        <div>
                                            <span class="text-red-600 font-bold text-lg" x-text="'LKR ' + new Number(product.sale_price).toLocaleString()"></span>
                                            <span class="text-gray-400 text-sm line-through ml-2" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                                        </div>
                                    </template>
                                    <template x-if="!product.sale_price">
                                        <span class="text-gray-800 font-bold text-lg" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                                    </template>
                                </div>
                                <button @click.prevent="addToCart(product.id)" class="mt-3 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-semibold transition">
                                    Add to Cart
                                </button>
                            </div>
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<!-- Newsletter Section -->
<div class="container mx-auto px-6 py-16">
    <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-2xl p-8 text-center text-white">
        <h2 class="text-2xl font-bold mb-2">Get 10% OFF Your First Order</h2>
        <p class="mb-6 opacity-90">Subscribe to our newsletter and receive exclusive offers</p>
        <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
            <input type="email" id="newsletterEmail" placeholder="Your email address" class="flex-1 px-4 py-3 rounded-lg text-gray-800 focus:outline-none">
            <button onclick="subscribeNewsletter()" class="bg-white text-red-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Subscribe
            </button>
        </div>
    </div>
</div>

<!-- Brands Section -->
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-6">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Our Premium Brands</h2>
            <p class="text-gray-500">We bring you products from the world's finest brands</p>
        </div>
        
        <div class="flex flex-wrap justify-center gap-8 items-center opacity-60">
            <div class="text-2xl font-bold text-gray-400">Zojirushi</div>
            <div class="text-2xl font-bold text-gray-400">Tiger</div>
            <div class="text-2xl font-bold text-gray-400">Panasonic</div>
            <div class="text-2xl font-bold text-gray-400">Nissin</div>
            <div class="text-2xl font-bold text-gray-400">Meiji</div>
            <div class="text-2xl font-bold text-gray-400">Barilla</div>
            <div class="text-2xl font-bold text-gray-400">Lavazza</div>
        </div>
    </div>
</div>

<script>
function addToCart(productId) {
    fetch('/api/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.cart_count);
            showNotification('Added to cart!');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showNotification('Error adding to cart', 'error');
    });
}

function updateCartCount(count) {
    const cartCount = document.getElementById('cartCount');
    if (cartCount) {
        if (count > 0) {
            cartCount.textContent = count;
            cartCount.classList.remove('hidden');
        } else {
            cartCount.classList.add('hidden');
        }
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white z-50 transition-opacity ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function subscribeNewsletter() {
    const email = document.getElementById('newsletterEmail').value;
    if (email) {
        alert('Thank you for subscribing! You will receive 10% OFF coupon via email.');
        document.getElementById('newsletterEmail').value = '';
    } else {
        alert('Please enter your email address');
    }
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.desktop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/home.blade.php ENDPATH**/ ?>