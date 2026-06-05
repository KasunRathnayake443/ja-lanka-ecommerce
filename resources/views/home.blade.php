@extends('layouts.desktop')

@section('title', 'Home - Global Flavors Mart')

@section('content')

{{-- ===================== HERO CAROUSEL ===================== --}}
<div class="hero-carousel relative w-full h-screen overflow-hidden bg-black" x-data="{ currentSlide: 0, slides: [], total: 0 }" x-init="
    fetch('/api/banners')
        .then(res => res.json())
        .then(data => {
            slides = data;
            total = data.length;
            if (total > 1) setInterval(() => { currentSlide = (currentSlide + 1) % total }, 5000);
        })
">
    <template x-for="(slide, index) in slides" :key="index">
        <div
            class="hero-slide absolute inset-0 bg-cover bg-center opacity-0 transition-opacity duration-700 pointer-events-none"
            :class="currentSlide === index ? 'opacity-100 pointer-events-auto' : ''"
            :style="'background-image: url(/storage/' + slide.image + ')'"
        >
            <div class="hero-overlay absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
            <div class="hero-content absolute bottom-20 left-16 text-white max-w-xl">
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight mb-3 drop-shadow-lg" x-text="slide.title"></h2>
                <p class="text-base md:text-lg opacity-85 mb-6 leading-relaxed" x-show="slide.subtitle" x-text="slide.subtitle"></p>
                <a x-show="slide.button_text" :href="slide.button_link || '/shop'" x-text="slide.button_text" class="hero-btn inline-block bg-white text-black px-8 py-3 font-bold text-sm tracking-wide uppercase no-underline transition hover:bg-red-700 hover:text-white"></a>
            </div>
        </div>
    </template>
    
    <div class="hero-dots absolute bottom-7 left-16 flex gap-2 items-center">
        <template x-for="(slide, index) in slides" :key="index">
            <button @click="currentSlide = index" class="hero-dot w-1.5 h-1.5 rounded-full bg-white/40 transition-all duration-200" :class="currentSlide === index ? '!bg-white scale-150' : ''"></button>
        </template>
    </div>
    
    <div x-show="slides.length === 0" class="absolute inset-0 flex items-center justify-center">
        <div class="spinner w-9 h-9 border-2 border-white/20 border-t-white rounded-full animate-spin"></div>
    </div>
</div>

{{-- ===================== SHOP BY CATEGORY ===================== --}}
<section class="section-wrap py-16 md:py-20 bg-white">
    <div class="section-head max-w-[1400px] mx-auto px-6 md:px-10 mb-10">
        <p class="section-eyebrow text-[0.72rem] font-semibold tracking-[0.14em] uppercase text-red-700 mb-1.5">Browse</p>
        <h2 class="section-title text-3xl md:text-4xl font-bold text-black tracking-tight">Shop by Category</h2>
    </div>

    <div class="categories-grid max-w-[1400px] mx-auto px-6 md:px-10 grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Food Card -->
        <div class="cat-card relative h-96 md:h-[420px] rounded-xl overflow-hidden cursor-pointer group">
            <div class="cat-card-inner relative w-full h-full transition-transform duration-700 ease-in-out [transform-style:preserve-3d] group-hover:[transform:rotateY(180deg)]">
                <!-- Front -->
                <div class="cat-card-front absolute inset-0 rounded-xl overflow-hidden bg-cover bg-center [backface-visibility:hidden]" style="background-image: url('/images/cat-foods.jpg')">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                    <div class="absolute bottom-7 left-6 text-white">
                        <h3 class="text-xl font-bold mb-1">Food Items</h3>
                        <p class="text-xs opacity-70 tracking-wide uppercase">Japanese · Korean · Italian</p>
                    </div>
                </div>
                <!-- Back -->
                <div class="cat-card-back absolute inset-0 bg-black rounded-xl flex flex-col items-center justify-center gap-3 text-white [transform:rotateY(180deg)] [backface-visibility:hidden]">
                    <span class="text-4xl">🍜</span>
                    <h3 class="text-xl font-bold">Food Items</h3>
                    <a href="{{ route('shop') }}?type=food" class="inline-block mt-1 border border-white/50 px-6 py-2 text-xs font-semibold tracking-wide uppercase rounded transition hover:bg-white hover:text-black">Shop Now →</a>
                </div>
            </div>
        </div>

        <!-- Appliances Card -->
        <div class="cat-card relative h-96 md:h-[420px] rounded-xl overflow-hidden cursor-pointer group">
            <div class="cat-card-inner relative w-full h-full transition-transform duration-700 ease-in-out [transform-style:preserve-3d] group-hover:[transform:rotateY(180deg)]">
                <div class="cat-card-front absolute inset-0 rounded-xl overflow-hidden bg-cover bg-center [backface-visibility:hidden]" style="background-image: url('/images/cat-appliances.jpg')">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                    <div class="absolute bottom-7 left-6 text-white">
                        <h3 class="text-xl font-bold mb-1">Appliances</h3>
                        <p class="text-xs opacity-70 tracking-wide uppercase">Premium Kitchen Gear</p>
                    </div>
                </div>
                <div class="cat-card-back absolute inset-0 bg-black rounded-xl flex flex-col items-center justify-center gap-3 text-white [transform:rotateY(180deg)] [backface-visibility:hidden]">
                    <span class="text-4xl">🔌</span>
                    <h3 class="text-xl font-bold">Appliances</h3>
                    <a href="{{ route('shop') }}?type=appliance" class="inline-block mt-1 border border-white/50 px-6 py-2 text-xs font-semibold tracking-wide uppercase rounded transition hover:bg-white hover:text-black">Shop Now →</a>
                </div>
            </div>
        </div>

        <!-- Sale Card -->
        <div class="cat-card relative h-96 md:h-[420px] rounded-xl overflow-hidden cursor-pointer group">
            <div class="cat-card-inner relative w-full h-full transition-transform duration-700 ease-in-out [transform-style:preserve-3d] group-hover:[transform:rotateY(180deg)]">
                <div class="cat-card-front absolute inset-0 rounded-xl overflow-hidden bg-cover bg-center [backface-visibility:hidden]" style="background-image: url('/images/cat-flash.png')">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                    <div class="absolute bottom-7 left-6 text-white">
                        <h3 class="text-xl font-bold mb-1">Sale</h3>
                        <p class="text-xs opacity-70 tracking-wide uppercase">Limited Time Offers</p>
                    </div>
                </div>
                <div class="cat-card-back absolute inset-0 bg-black rounded-xl flex flex-col items-center justify-center gap-3 text-white [transform:rotateY(180deg)] [backface-visibility:hidden]">
                    <span class="text-4xl">🔥</span>
                    <h3 class="text-xl font-bold">Sale</h3>
                    <a href="{{ route('sale') }}" class="inline-block mt-1 border border-white/50 px-6 py-2 text-xs font-semibold tracking-wide uppercase rounded transition hover:bg-white hover:text-black">Shop Now →</a>
                </div>
            </div>
        </div>

        <!-- New Arrivals Card -->
        <div class="cat-card relative h-96 md:h-[420px] rounded-xl overflow-hidden cursor-pointer group">
            <div class="cat-card-inner relative w-full h-full transition-transform duration-700 ease-in-out [transform-style:preserve-3d] group-hover:[transform:rotateY(180deg)]">
                <div class="cat-card-front absolute inset-0 rounded-xl overflow-hidden bg-cover bg-center [backface-visibility:hidden]" style="background-image: url('/images/cat-new.webp')">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                    <div class="absolute bottom-7 left-6 text-white">
                        <h3 class="text-xl font-bold mb-1">New Arrivals</h3>
                        <p class="text-xs opacity-70 tracking-wide uppercase">Fresh from the World</p>
                    </div>
                </div>
                <div class="cat-card-back absolute inset-0 bg-black rounded-xl flex flex-col items-center justify-center gap-3 text-white [transform:rotateY(180deg)] [backface-visibility:hidden]">
                    <span class="text-4xl">✨</span>
                    <h3 class="text-xl font-bold">New Arrivals</h3>
                    <a href="{{ route('shop') }}?sort=newest" class="inline-block mt-1 border border-white/50 px-6 py-2 text-xs font-semibold tracking-wide uppercase rounded transition hover:bg-white hover:text-black">Shop Now →</a>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ===================== FLASH SALE ===================== --}}
<section class="section-wrap py-16 md:py-20 bg-gray-50">
    <div class="max-w-[1400px] mx-auto px-6 md:px-10">
        <div class="section-head flex flex-wrap items-end justify-between gap-4 mb-10">
            <div>
                <p class="section-eyebrow text-[0.72rem] font-semibold tracking-[0.14em] uppercase text-red-700 mb-1.5">Limited Time</p>
                <h2 class="section-title text-3xl md:text-4xl font-bold text-black tracking-tight">Flash Sale</h2>
            </div>
            <a href="{{ route('sale') }}" class="view-all-link text-sm font-semibold text-black no-underline border-b border-black pb-0.5 whitespace-nowrap transition hover:text-red-700 hover:border-red-700">View all deals →</a>
        </div>

        <div x-data="{ flashSales: [] }" x-init="
            fetch('/api/flash-sales')
                .then(res => res.json())
                .then(data => { flashSales = data; })
                .catch(err => console.error('Error loading flash sales:', err))
        ">
            <div x-show="flashSales.length === 0" class="flex justify-center py-12">
                <div class="spinner--dark w-9 h-9 border-2 border-gray-200 border-t-red-700 rounded-full animate-spin"></div>
            </div>

            <div class="flash-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" x-show="flashSales.length > 0">
                <template x-for="item in flashSales" :key="item.id">
                    <a :href="'/product/' + item.product.slug" class="flash-card flex gap-4 bg-white rounded-xl p-4 shadow-sm border border-gray-100 no-underline text-inherit transition hover:shadow-md hover:-translate-y-0.5">
                        <div class="flash-img-wrap relative w-[90px] h-[90px] flex-shrink-0 rounded overflow-hidden bg-gray-100">
                            <img :src="item.product.images && item.product.images[0] ? '/storage/' + item.product.images[0].image_path : '/images/placeholder.jpg'" class="w-full h-full object-cover" alt="">
                            <span class="flash-badge absolute top-1.5 left-1.5 bg-red-700 text-white text-[0.68rem] font-bold px-2 py-0.5 rounded" x-text="'-' + (item.product.discount_percent || Math.round((item.product.regular_price - item.product.sale_price) / item.product.regular_price * 100)) + '%'"></span>
                        </div>
                        <div class="flash-info flex-1 flex flex-col justify-center">
                            <p class="flash-name text-sm font-semibold text-black mb-1 line-clamp-2" x-text="item.custom_title || item.product.name"></p>
                            <p class="flash-sub text-xs text-gray-500 mb-2" x-show="item.custom_subtitle" x-text="item.custom_subtitle"></p>
                            <div class="flash-price-row flex items-baseline gap-2">
                                <span class="flash-price text-base font-bold text-red-700" x-text="'LKR ' + new Number(item.product.sale_price).toLocaleString()"></span>
                                <span class="flash-was text-xs text-gray-400 line-through" x-text="'LKR ' + new Number(item.product.regular_price).toLocaleString()"></span>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
        </div>
    </div>
</section>


{{-- ===================== FEATURED PRODUCTS ===================== --}}
<section class="section-wrap py-16 md:py-20 bg-white">
    <div class="max-w-[1400px] mx-auto px-6 md:px-10">
        <div class="section-head flex flex-wrap items-end justify-between gap-4 mb-10">
            <div>
                <p class="section-eyebrow text-[0.72rem] font-semibold tracking-[0.14em] uppercase text-red-700 mb-1.5">Hand-picked</p>
                <h2 class="section-title text-3xl md:text-4xl font-bold text-black tracking-tight">Featured Products</h2>
            </div>
            <a href="{{ route('shop') }}" class="view-all-link text-sm font-semibold text-black no-underline border-b border-black pb-0.5 whitespace-nowrap transition hover:text-red-700 hover:border-red-700">Browse all →</a>
        </div>

        <div x-data="{ featured: [] }" x-init="fetch('/api/products?page=1&limit=8').then(r=>r.json()).then(d=>{ featured=d.data; })">
            <div x-show="featured.length === 0" class="flex justify-center py-12">
                <div class="spinner--dark w-9 h-9 border-2 border-gray-200 border-t-red-700 rounded-full animate-spin"></div>
            </div>

            <div class="products-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5" x-show="featured.length > 0">
                <template x-for="product in featured" :key="product.id">
                    <div class="product-card bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 transition hover:shadow-md hover:-translate-y-1 flex flex-col">
                        <a :href="'/product/' + product.slug" class="block flex-1">
                            <div class="product-img-wrap relative h-48 overflow-hidden bg-gray-100">
                                <img :src="product.images && product.images[0] ? '/storage/' + product.images[0].image_path : '/images/placeholder.jpg'" class="product-img w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="">
                                <div x-show="product.discount_percent > 0" class="product-badge absolute top-2.5 right-2.5 bg-red-700 text-white text-[0.68rem] font-bold px-2 py-0.5 rounded" x-text="'-' + product.discount_percent + '%'"></div>
                            </div>
                            <div class="product-info p-4">
                                <p class="product-origin text-[0.72rem] text-gray-400 uppercase tracking-wide mb-1" x-show="product.origin" x-text="(product.origin?.flag_icon || '🌏') + ' ' + (product.origin?.country_name || '')"></p>
                                <h3 class="product-name text-sm font-semibold text-black mb-2 line-clamp-2" x-text="product.name"></h3>
                                <div class="product-price-row">
                                    <template x-if="product.sale_price">
                                        <div class="product-prices flex items-baseline gap-2 flex-wrap">
                                            <span class="product-price--sale text-base font-bold text-red-700" x-text="'LKR ' + new Number(product.sale_price).toLocaleString()"></span>
                                            <span class="product-price--was text-xs text-gray-400 line-through" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                                        </div>
                                    </template>
                                    <template x-if="!product.sale_price">
                                        <span class="product-price--reg text-base font-bold text-black" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                                    </template>
                                </div>
                            </div>
                        </a>
                        <button @click="addToCart(product.id)" class="add-to-cart-btn mx-4 mb-4 py-2 bg-black text-white text-xs font-semibold tracking-wide uppercase rounded transition hover:bg-red-700">Add to Cart</button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</section>

{{-- ===================== NEW ARRIVALS ===================== --}}
<section class="section-wrap py-16 md:py-20 bg-gray-50">
    <div class="max-w-[1400px] mx-auto px-6 md:px-10">
        <div class="section-head flex flex-wrap items-end justify-between gap-4 mb-10">
            <div>
                <p class="section-eyebrow text-[0.72rem] font-semibold tracking-[0.14em] uppercase text-red-700 mb-1.5">Just In</p>
                <h2 class="section-title text-3xl md:text-4xl font-bold text-black tracking-tight">New Arrivals</h2>
            </div>
            <a href="{{ route('shop') }}?sort=newest" class="view-all-link text-sm font-semibold text-black no-underline border-b border-black pb-0.5 whitespace-nowrap transition hover:text-red-700 hover:border-red-700">See all →</a>
        </div>

        <div x-data="{ newArrivals: [] }" x-init="fetch('/api/products?page=1&sort=newest').then(r=>r.json()).then(d=>{ newArrivals=d.data; })">
            <div x-show="newArrivals.length === 0" class="flex justify-center py-12">
                <div class="spinner--dark w-9 h-9 border-2 border-gray-200 border-t-red-700 rounded-full animate-spin"></div>
            </div>

            <div class="products-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5" x-show="newArrivals.length > 0">
                <template x-for="product in newArrivals.slice(0,8)" :key="product.id">
                    <div class="product-card bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 transition hover:shadow-md hover:-translate-y-1 flex flex-col">
                        <a :href="'/product/' + product.slug" class="block flex-1">
                            <div class="product-img-wrap relative h-48 overflow-hidden bg-gray-100">
                                <img :src="product.images && product.images[0] ? '/storage/' + product.images[0].image_path : '/images/placeholder.jpg'" class="product-img w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="">
                                <div class="product-badge product-badge--new absolute top-2.5 left-2.5 bg-emerald-700 text-white text-[0.68rem] font-bold px-2 py-0.5 rounded">NEW</div>
                                <div x-show="product.discount_percent > 0" class="product-badge product-badge--sale absolute top-2.5 right-2.5 bg-red-700 text-white text-[0.68rem] font-bold px-2 py-0.5 rounded" x-text="'-' + product.discount_percent + '%'"></div>
                            </div>
                            <div class="product-info p-4">
                                <p class="product-origin text-[0.72rem] text-gray-400 uppercase tracking-wide mb-1" x-show="product.origin" x-text="(product.origin?.flag_icon || '🌏') + ' ' + (product.origin?.country_name || '')"></p>
                                <h3 class="product-name text-sm font-semibold text-black mb-2 line-clamp-2" x-text="product.name"></h3>
                                <div class="product-price-row">
                                    <template x-if="product.sale_price">
                                        <div class="product-prices flex items-baseline gap-2 flex-wrap">
                                            <span class="product-price--sale text-base font-bold text-red-700" x-text="'LKR ' + new Number(product.sale_price).toLocaleString()"></span>
                                            <span class="product-price--was text-xs text-gray-400 line-through" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                                        </div>
                                    </template>
                                    <template x-if="!product.sale_price">
                                        <span class="product-price--reg text-base font-bold text-black" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                                    </template>
                                </div>
                            </div>
                        </a>
                        <button @click="addToCart(product.id)" class="add-to-cart-btn mx-4 mb-4 py-2 bg-black text-white text-xs font-semibold tracking-wide uppercase rounded transition hover:bg-red-700">Add to Cart</button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</section>

{{-- ===================== NEWSLETTER ===================== --}}
<section class="newsletter-section py-16 md:py-20 bg-black">
    <div class="newsletter-inner max-w-3xl mx-auto px-6 flex flex-col md:flex-row items-center gap-10">
        <div class="newsletter-text flex-1 text-center md:text-left text-white">
            <p class="section-eyebrow section-eyebrow--light text-[0.72rem] font-semibold tracking-[0.14em] uppercase text-white/65 mb-1.5">Stay in the loop</p>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight mb-4">Get 10% Off<br>Your First Order</h2>
            <p class="newsletter-sub text-sm text-white/60 leading-relaxed">Subscribe for exclusive deals and new arrivals from around the world.</p>
        </div>
        <div class="newsletter-form flex-1 w-full">
            <div class="flex flex-col gap-3">
                <input type="email" id="newsletterEmail" placeholder="Your email address" class="newsletter-input w-full px-5 py-3 border border-white/15 bg-white/5 text-white text-sm rounded outline-none transition focus:border-white/40">
                <button onclick="subscribeNewsletter()" class="newsletter-btn w-full py-3 bg-red-700 text-white text-sm font-bold tracking-wide uppercase rounded transition hover:bg-red-800">Subscribe</button>
            </div>
        </div>
    </div>
</section>

{{-- ===================== BRANDS ===================== --}}
<section class="brands-section py-14 bg-white border-t border-gray-100">
    <p class="section-eyebrow text-center text-[0.72rem] font-semibold tracking-[0.14em] uppercase text-red-700 mb-7">Trusted Brands</p>
    <div class="brands-strip flex flex-wrap justify-center gap-8 md:gap-14 items-center">
        <span class="text-lg md:text-xl font-bold italic tracking-wide text-gray-300 transition hover:text-black cursor-default">Zojirushi</span>
        <span class="text-lg md:text-xl font-bold italic tracking-wide text-gray-300 transition hover:text-black cursor-default">Tiger</span>
        <span class="text-lg md:text-xl font-bold italic tracking-wide text-gray-300 transition hover:text-black cursor-default">Panasonic</span>
        <span class="text-lg md:text-xl font-bold italic tracking-wide text-gray-300 transition hover:text-black cursor-default">Nissin</span>
        <span class="text-lg md:text-xl font-bold italic tracking-wide text-gray-300 transition hover:text-black cursor-default">Meiji</span>
        <span class="text-lg md:text-xl font-bold italic tracking-wide text-gray-300 transition hover:text-black cursor-default">Barilla</span>
        <span class="text-lg md:text-xl font-bold italic tracking-wide text-gray-300 transition hover:text-black cursor-default">Lavazza</span>
    </div>
</section>

{{-- ===================== SCRIPTS ===================== --}}
<script>
function addToCart(productId) {
    fetch('/api/cart/add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { updateCartCount(data.cart_count); showNotification('Added to cart!'); }
    });
}

function updateCartCount(count) {
    const el = document.getElementById('cartCount');
    if (el) { el.textContent = count; el.classList.toggle('hidden', count < 1); }
}

function showNotification(msg, type = 'success') {
    const n = document.createElement('div');
    n.className = `notif fixed bottom-6 right-6 px-5 py-3 rounded text-sm font-semibold text-white z-[9999] transition-opacity shadow-lg ${type === 'success' ? 'bg-emerald-700' : 'bg-red-700'}`;
    n.textContent = msg;
    document.body.appendChild(n);
    setTimeout(() => { n.style.opacity = '0'; setTimeout(() => n.remove(), 300); }, 2800);
}

function subscribeNewsletter() {
    const email = document.getElementById('newsletterEmail').value;
    if (email) {
        alert('Thank you! Your 10% OFF coupon is on the way.');
        document.getElementById('newsletterEmail').value = '';
    } else {
        alert('Please enter your email address.');
    }
}
</script>

<style>
/* Custom utilities not available in Tailwind */
.perspective-1200 {
    perspective: 1200px;
}

.backface-hidden {
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Group hover fix for Tailwind */
.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}
</style>

@endsection