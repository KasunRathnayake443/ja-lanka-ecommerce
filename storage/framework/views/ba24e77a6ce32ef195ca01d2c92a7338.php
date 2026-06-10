<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>
<div class="pb-20 bg-gray-50">
    
    <!-- Hero Banner with Ken Burns Effect -->
    <div class="relative h-72 overflow-hidden bg-black" x-data="{ currentSlide: 0, slides: [] }" x-init="
        fetch('/api/banners')
            .then(res => res.json())
            .then(data => { slides = data; if(slides.length) setInterval(() => { currentSlide = (currentSlide + 1) % slides.length }, 5000); })
    ">
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="currentSlide === index" class="absolute inset-0" x-transition:enter="transition-opacity duration-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="absolute inset-0 bg-cover bg-center scale-105 transition-transform duration-[7s] ease-out" 
                     :style="'background-image: url(/storage/' + slide.image + ')'"
                     x-bind:class="{ 'scale-100': true }"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                <div class="absolute bottom-6 left-0 right-0 text-center px-6">
                    <h2 class="text-2xl font-bold text-white mb-2 drop-shadow-lg" x-text="slide.title"></h2>
                    <p class="text-sm text-white/80 mb-4" x-show="slide.subtitle" x-text="slide.subtitle"></p>
                    <a :href="slide.button_link || '/mobile/shop'" class="inline-block bg-white text-black px-6 py-2 rounded-full text-sm font-semibold tracking-wide hover:bg-red-700 hover:text-white transition-colors" x-show="slide.button_text" x-text="slide.button_text"></a>
                </div>
            </div>
        </template>
        
        <!-- Dots -->
        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-10">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="currentSlide = index" class="h-1 rounded-full transition-all duration-300" :class="currentSlide === index ? 'w-6 bg-red-600' : 'w-2 bg-white/50'"></button>
            </template>
        </div>
        
        <!-- Loading -->
        <div x-show="slides.length === 0" class="absolute inset-0 flex items-center justify-center bg-gray-900">
            <div class="animate-spin rounded-full h-8 w-8 border-2 border-white/20 border-t-white"></div>
        </div>
    </div>
    
    <!-- Shop by Category (Flip Cards - Mobile Optimized) -->
    <section class="px-4 py-8 bg-white">
        <div class="text-center mb-6">
            <p class="text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-red-600 mb-1">Browse</p>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Shop by Category</h2>
        </div>
        
        <div class="grid grid-cols-2 gap-3">
            <!-- Food Card -->
            <a href="<?php echo e(route('mobile.shop')); ?>?type=food" class="group relative h-48 rounded-xl overflow-hidden shadow-md">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style="background-image: url('/images/cat-foods.jpg')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                <div class="absolute bottom-3 left-3 right-3">
                    <span class="text-2xl block mb-1">🍜</span>
                    <h3 class="text-white font-bold text-lg">Food</h3>
                    <p class="text-white/70 text-xs">Japanese · Korean · Italian</p>
                </div>
            </a>
            
            <!-- Appliances Card -->
            <a href="<?php echo e(route('mobile.shop')); ?>?type=appliance" class="group relative h-48 rounded-xl overflow-hidden shadow-md">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style="background-image: url('/images/cat-appliances.jpg')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                <div class="absolute bottom-3 left-3 right-3">
                    <span class="text-2xl block mb-1">🔌</span>
                    <h3 class="text-white font-bold text-lg">Appliances</h3>
                    <p class="text-white/70 text-xs">Premium Kitchen Gear</p>
                </div>
            </a>
            
            <!-- Sale Card -->
            <a href="<?php echo e(route('mobile.sale')); ?>" class="group relative h-48 rounded-xl overflow-hidden shadow-md">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style="background-image: url('/images/cat-flash.png')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                <div class="absolute bottom-3 left-3 right-3">
                    <span class="text-2xl block mb-1">🔥</span>
                    <h3 class="text-white font-bold text-lg">Sale</h3>
                    <p class="text-white/70 text-xs">Limited Time Offers</p>
                </div>
            </a>
            
            <!-- New Arrivals Card -->
            <a href="<?php echo e(route('mobile.shop')); ?>?sort=newest" class="group relative h-48 rounded-xl overflow-hidden shadow-md">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style="background-image: url('/images/cat-new.webp')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                <div class="absolute bottom-3 left-3 right-3">
                    <span class="text-2xl block mb-1">✨</span>
                    <h3 class="text-white font-bold text-lg">New</h3>
                    <p class="text-white/70 text-xs">Fresh from the World</p>
                </div>
            </a>
        </div>
    </section>
    
    <!-- Flash Sale Carousel -->
    <section class="px-4 py-8 bg-gray-50">
        <div class="flex justify-between items-center mb-4">
            <div>
                <p class="text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-red-600 mb-0.5">Limited Time</p>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Flash Sale</h2>
            </div>
            <a href="<?php echo e(route('mobile.sale')); ?>" class="text-xs font-medium text-gray-600 hover:text-red-600">View All →</a>
        </div>
        
        <div x-data="{ flashSales: [] }" x-init="
            fetch('/api/flash-sales')
                .then(res => res.json())
                .then(data => { flashSales = data; })
                .catch(err => console.error('Error loading flash sales:', err))
        ">
            <div x-show="flashSales.length === 0" class="flex justify-center py-8">
                <div class="animate-spin rounded-full h-6 w-6 border-2 border-gray-300 border-t-red-600"></div>
            </div>
            
            <div class="overflow-x-auto pb-2 -mx-1 px-1" x-show="flashSales.length > 0">
                <div class="flex gap-3" style="min-width: max-content;">
                    <template x-for="item in flashSales" :key="item.id">
                        <a :href="'/mobile/product/' + item.product.slug" class="w-64 flex-shrink-0 bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
                            <div class="relative h-32 overflow-hidden">
                                <img :src="item.product.images && item.product.images[0] ? '/storage/' + item.product.images[0].image_path : '/images/placeholder.jpg'" class="w-full h-full object-cover">
                                <span class="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full" x-text="'-' + item.product.discount_percent + '%'"></span>
                            </div>
                            <div class="p-3">
                                <h3 class="font-semibold text-sm line-clamp-1" x-text="item.custom_title || item.product.name"></h3>
                                <div class="mt-2">
                                    <span class="text-red-600 font-bold text-sm" x-text="'LKR ' + new Number(item.product.sale_price).toLocaleString()"></span>
                                    <span class="text-gray-400 text-xs line-through ml-1" x-text="'LKR ' + new Number(item.product.regular_price).toLocaleString()"></span>
                                </div>
                                <button @click.prevent="addToCart(item.product.id)" class="mt-2 w-full bg-gray-900 text-white py-1.5 rounded-lg text-xs font-semibold hover:bg-red-700 transition-colors">Add to Cart</button>
                            </div>
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Featured Products -->
    <section class="px-4 py-8 bg-white">
        <div class="flex justify-between items-center mb-4">
            <div>
                <p class="text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-red-600 mb-0.5">Hand-picked</p>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Featured</h2>
            </div>
            <a href="<?php echo e(route('mobile.shop')); ?>" class="text-xs font-medium text-gray-600 hover:text-red-600">See All →</a>
        </div>
        
        <div x-data="{ featured: [] }" x-init="
            fetch('/api/products?page=1&limit=4')
                .then(res => res.json())
                .then(data => { featured = data.data; })
        ">
            <div x-show="featured.length === 0" class="flex justify-center py-8">
                <div class="animate-spin rounded-full h-6 w-6 border-2 border-gray-300 border-t-red-600"></div>
            </div>
            
            <div class="space-y-3" x-show="featured.length > 0">
                <template x-for="product in featured" :key="product.id">
                    <a :href="'/mobile/product/' + product.slug" class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 flex gap-3">
                        <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                            <img :src="product.images && product.images[0] ? '/storage/' + product.images[0].image_path : '/images/placeholder.jpg'" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-1">
                                <h3 class="font-semibold text-sm flex-1 line-clamp-2" x-text="product.name"></h3>
                                <span x-show="product.discount_percent > 0" class="bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded" x-text="'-' + product.discount_percent + '%'"></span>
                            </div>
                            <div class="text-xs text-gray-500 mt-0.5" x-show="product.origin" x-text="(product.origin?.flag_icon || '🌏') + ' ' + (product.origin?.country_name || '')"></div>
                            <div class="mt-2">
                                <template x-if="product.sale_price">
                                    <div>
                                        <span class="text-red-600 font-bold text-sm" x-text="'LKR ' + new Number(product.sale_price).toLocaleString()"></span>
                                        <span class="text-gray-400 text-xs line-through ml-1" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                                    </div>
                                </template>
                                <template x-if="!product.sale_price">
                                    <span class="text-gray-800 font-bold text-sm" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                                </template>
                            </div>
                            <button @click.prevent="addToCart(product.id)" class="mt-2 w-full bg-gray-900 text-white py-1.5 rounded-lg text-xs font-semibold hover:bg-red-700 transition-colors">Add to Cart</button>
                        </div>
                    </a>
                </template>
            </div>
        </div>
    </section>
    
    <!-- New Arrivals -->
    <section class="px-4 py-8 bg-gray-50">
        <div class="flex justify-between items-center mb-4">
            <div>
                <p class="text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-red-600 mb-0.5">Just In</p>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">New Arrivals</h2>
            </div>
            <a href="<?php echo e(route('mobile.shop')); ?>?sort=newest" class="text-xs font-medium text-gray-600 hover:text-red-600">See All →</a>
        </div>
        
        <div x-data="{ newArrivals: [] }" x-init="
            fetch('/api/products?page=1&sort=newest&limit=4')
                .then(res => res.json())
                .then(data => { newArrivals = data.data; })
        ">
            <div x-show="newArrivals.length === 0" class="flex justify-center py-8">
                <div class="animate-spin rounded-full h-6 w-6 border-2 border-gray-300 border-t-red-600"></div>
            </div>
            
            <div class="space-y-3" x-show="newArrivals.length > 0">
                <template x-for="product in newArrivals.slice(0, 4)" :key="product.id">
                    <a :href="'/mobile/product/' + product.slug" class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 flex gap-3">
                        <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                            <img :src="product.images && product.images[0] ? '/storage/' + product.images[0].image_path : '/images/placeholder.jpg'" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-1">
                                <h3 class="font-semibold text-sm flex-1 line-clamp-2" x-text="product.name"></h3>
                                <span class="bg-emerald-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">NEW</span>
                            </div>
                            <div class="text-xs text-gray-500 mt-0.5" x-show="product.origin" x-text="(product.origin?.flag_icon || '🌏') + ' ' + (product.origin?.country_name || '')"></div>
                            <div class="mt-2">
                                <span class="text-gray-800 font-bold text-sm" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                            </div>
                            <button @click.prevent="addToCart(product.id)" class="mt-2 w-full bg-gray-900 text-white py-1.5 rounded-lg text-xs font-semibold hover:bg-red-700 transition-colors">Add to Cart</button>
                        </div>
                    </a>
                </template>
            </div>
        </div>
    </section>
    
    <!-- Newsletter -->
    <section class="px-4 py-8 bg-black">
        <div class="text-center">
            <p class="text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-red-500 mb-1">Stay in the loop</p>
            <h2 class="text-2xl font-bold text-white mb-2">Get 10% Off<br>Your First Order</h2>
            <p class="text-sm text-white/60 mb-6">Subscribe for exclusive deals and new arrivals</p>
            <div class="flex gap-2">
                <input type="email" id="newsletterEmail" placeholder="Your email" class="flex-1 px-4 py-3 rounded-full text-gray-900 bg-white border-0 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                <button onclick="subscribeNewsletter()" class="bg-red-600 text-white px-6 py-3 rounded-full font-semibold text-sm hover:bg-red-700 transition-colors">Subscribe</button>
            </div>
        </div>
    </section>
    
    <!-- Brands -->
    <section class="px-4 py-6 bg-white border-t border-gray-100">
        <p class="text-[0.7rem] font-semibold tracking-[0.14em] uppercase text-red-600 text-center mb-4">Trusted Brands</p>
        <div class="flex flex-wrap justify-center gap-4 items-center opacity-60">
            <span class="text-xs font-bold italic text-gray-400">Zojirushi</span>
            <span class="text-xs font-bold italic text-gray-400">Tiger</span>
            <span class="text-xs font-bold italic text-gray-400">Panasonic</span>
            <span class="text-xs font-bold italic text-gray-400">Nissin</span>
            <span class="text-xs font-bold italic text-gray-400">Meiji</span>
            <span class="text-xs font-bold italic text-gray-400">Barilla</span>
            <span class="text-xs font-bold italic text-gray-400">Lavazza</span>
        </div>
    </section>
    
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
    toast.className = `fixed bottom-20 left-4 right-4 text-center px-4 py-2 rounded-lg text-white z-50 transition-all duration-300 ${type === 'success' ? 'bg-emerald-600' : 'bg-red-600'}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}

function subscribeNewsletter() {
    const email = document.getElementById('newsletterEmail').value;
    if (email && email.includes('@')) {
        alert('Thank you! Your 10% OFF coupon is on the way.');
        document.getElementById('newsletterEmail').value = '';
    } else {
        alert('Please enter a valid email address.');
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\Herd\ja-lanka-ecommerce\resources\views/mobile/home.blade.php ENDPATH**/ ?>