@extends('layouts.desktop')

@section('title', 'Home - Global Flavors Mart')

@section('content')

{{-- ===================== HERO CAROUSEL ===================== --}}
<div class="hero-carousel" x-data="{ currentSlide: 0, slides: [], total: 0 }" x-init="
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
            class="hero-slide"
            :class="currentSlide === index ? 'active' : ''"
            :style="'background-image: url(/storage/' + slide.image + ')'"
        >
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h2 x-text="slide.title"></h2>
                <p x-show="slide.subtitle" x-text="slide.subtitle"></p>
                <a x-show="slide.button_text" :href="slide.button_link || '/shop'" x-text="slide.button_text" class="hero-btn"></a>
            </div>
        </div>
    </template>
    <div class="hero-dots">
        <template x-for="(slide, index) in slides" :key="index">
            <button @click="currentSlide = index" class="hero-dot" :class="currentSlide === index ? 'active' : ''"></button>
        </template>
    </div>
    <div x-show="slides.length === 0" class="hero-loading">
        <div class="spinner"></div>
    </div>
</div>

{{-- ===================== SHOP BY CATEGORY ===================== --}}
<section class="section-wrap">
    <div class="section-head">
        <p class="section-eyebrow">Browse</p>
        <h2 class="section-title">Shop by Category</h2>
    </div>

    <div class="categories-grid">
        <a href="{{ route('shop') }}?type=food" class="cat-card">
            <div class="cat-card-inner">
                <div class="cat-card-front" style="background-image: url('/images/cat-foods.jpg')">
                    <div class="cat-front-overlay"></div>
                    <div class="cat-label">
                        <h3>Food Items</h3>
                        <p>Japanese · Korean · Italian</p>
                    </div>
                </div>
                <div class="cat-card-back">
                    <span class="cat-icon-back">🍜</span>
                    <h3>Food Items</h3>
                    <span class="shop-now-btn">Shop Now →</span>
                </div>
            </div>
        </a>

        <a href="{{ route('shop') }}?type=appliance" class="cat-card">
            <div class="cat-card-inner">
                <div class="cat-card-front" style="background-image: url('/images/cat-appliances.jpg')">
                    <div class="cat-front-overlay"></div>
                    <div class="cat-label">
                        <h3>Appliances</h3>
                        <p>Premium Kitchen Gear</p>
                    </div>
                </div>
                <div class="cat-card-back">
                    <span class="cat-icon-back">🔌</span>
                    <h3>Appliances</h3>
                    <span class="shop-now-btn">Shop Now →</span>
                </div>
            </div>
        </a>

        <a href="{{ route('mobile.sale') }}" class="cat-card">
            <div class="cat-card-inner">
                <div class="cat-card-front" style="background-image: url('/images/cat-flash.png')">
                    <div class="cat-front-overlay"></div>
                    <div class="cat-label">
                        <h3>Sale</h3>
                        <p>Limited Time Offers</p>
                    </div>
                </div>
                <div class="cat-card-back">
                    <span class="cat-icon-back">🔥</span>
                    <h3>Sale</h3>
                    <span class="shop-now-btn">Shop Now →</span>
                </div>
            </div>
        </a>

        <a href="{{ route('shop') }}?sort=newest" class="cat-card">
            <div class="cat-card-inner">
                <div class="cat-card-front" style="background-image: url('/images/cat-new.webp')">
                    <div class="cat-front-overlay"></div>
                    <div class="cat-label">
                        <h3>New Arrivals</h3>
                        <p>Fresh from the World</p>
                    </div>
                </div>
                <div class="cat-card-back">
                    <span class="cat-icon-back">✨</span>
                    <h3>New Arrivals</h3>
                    <span class="shop-now-btn">Shop Now →</span>
                </div>
            </div>
        </a>
    </div>
</section>

{{-- ===================== FLASH SALE ===================== --}}
<section class="section-wrap section-alt"
    x-data="{ flashSales: [] }"
    x-init="fetch('/api/flash-sales').then(r=>r.json()).then(d=>{ flashSales=d; })"
>
    <div class="section-head section-head--inline">
        <div>
            <p class="section-eyebrow">Limited Time</p>
            <h2 class="section-title">Flash Sale</h2>
        </div>
        <a href="{{ route('mobile.sale') }}" class="view-all-link">View all deals →</a>
    </div>

    <div x-show="flashSales.length === 0" class="loading-row">
        <div class="spinner spinner--dark"></div>
    </div>

    <div class="flash-grid" x-show="flashSales.length > 0">
        <template x-for="item in flashSales" :key="item.id">
            <a :href="'/mobile/sale'" class="flash-card">
                <div class="flash-img-wrap">
                    <img :src="item.product.images && item.product.images[0] ? '/storage/' + item.product.images[0].image_path : '/images/placeholder.jpg'"
                         class="flash-img" alt="">
                    <span class="flash-badge" x-text="'-' + item.product.discount_percent + '%'"></span>
                </div>
                <div class="flash-info">
                    <p class="flash-name" x-text="item.custom_title || item.product.name"></p>
                    <p class="flash-sub" x-show="item.custom_subtitle" x-text="item.custom_subtitle"></p>
                    <div class="flash-price-row">
                        <span class="flash-price" x-text="'LKR ' + new Number(item.product.sale_price).toLocaleString()"></span>
                        <span class="flash-was" x-text="'LKR ' + new Number(item.product.regular_price).toLocaleString()"></span>
                    </div>
                </div>
            </a>
        </template>
    </div>
</section>

{{-- ===================== FEATURED PRODUCTS ===================== --}}
<section class="section-wrap"
    x-data="{ featured: [] }"
    x-init="fetch('/api/products?page=1&limit=8').then(r=>r.json()).then(d=>{ featured=d.data; })"
>
    <div class="section-head section-head--inline">
        <div>
            <p class="section-eyebrow">Hand-picked</p>
            <h2 class="section-title">Featured Products</h2>
        </div>
        <a href="{{ route('shop') }}" class="view-all-link">Browse all →</a>
    </div>

    <div x-show="featured.length === 0" class="loading-row">
        <div class="spinner spinner--dark"></div>
    </div>

    <div class="products-grid" x-show="featured.length > 0">
        <template x-for="product in featured" :key="product.id">
            <div class="product-card">
                <a :href="'/product/' + product.slug">
                    <div class="product-img-wrap">
                        <img :src="product.images && product.images[0] ? '/storage/' + product.images[0].image_path : '/images/placeholder.jpg'"
                             class="product-img" alt="">
                        <div x-show="product.discount_percent > 0" class="product-badge" x-text="'-' + product.discount_percent + '%'"></div>
                    </div>
                    <div class="product-info">
                        <p class="product-origin" x-show="product.origin" x-text="(product.origin?.flag_icon || '🌏') + ' ' + (product.origin?.country_name || '')"></p>
                        <h3 class="product-name" x-text="product.name"></h3>
                        <div class="product-price-row">
                            <template x-if="product.sale_price">
                                <div class="product-prices">
                                    <span class="product-price--sale" x-text="'LKR ' + new Number(product.sale_price).toLocaleString()"></span>
                                    <span class="product-price--was" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                                </div>
                            </template>
                            <template x-if="!product.sale_price">
                                <span class="product-price--reg" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                            </template>
                        </div>
                    </div>
                </a>
                <button @click="addToCart(product.id)" class="add-to-cart-btn">Add to Cart</button>
            </div>
        </template>
    </div>
</section>

{{-- ===================== NEW ARRIVALS ===================== --}}
<section class="section-wrap section-alt"
    x-data="{ newArrivals: [] }"
    x-init="fetch('/api/products?page=1&sort=newest').then(r=>r.json()).then(d=>{ newArrivals=d.data; })"
>
    <div class="section-head section-head--inline">
        <div>
            <p class="section-eyebrow">Just In</p>
            <h2 class="section-title">New Arrivals</h2>
        </div>
        <a href="{{ route('shop') }}?sort=newest" class="view-all-link">See all →</a>
    </div>

    <div x-show="newArrivals.length === 0" class="loading-row">
        <div class="spinner spinner--dark"></div>
    </div>

    <div class="products-grid" x-show="newArrivals.length > 0">
        <template x-for="product in newArrivals.slice(0,8)" :key="product.id">
            <div class="product-card">
                <a :href="'/product/' + product.slug">
                    <div class="product-img-wrap">
                        <img :src="product.images && product.images[0] ? '/storage/' + product.images[0].image_path : '/images/placeholder.jpg'"
                             class="product-img" alt="">
                        <div class="product-badge product-badge--new">NEW</div>
                        <div x-show="product.discount_percent > 0" class="product-badge product-badge--sale" x-text="'-' + product.discount_percent + '%'"></div>
                    </div>
                    <div class="product-info">
                        <p class="product-origin" x-show="product.origin" x-text="(product.origin?.flag_icon || '🌏') + ' ' + (product.origin?.country_name || '')"></p>
                        <h3 class="product-name" x-text="product.name"></h3>
                        <div class="product-price-row">
                            <template x-if="product.sale_price">
                                <div class="product-prices">
                                    <span class="product-price--sale" x-text="'LKR ' + new Number(product.sale_price).toLocaleString()"></span>
                                    <span class="product-price--was" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                                </div>
                            </template>
                            <template x-if="!product.sale_price">
                                <span class="product-price--reg" x-text="'LKR ' + new Number(product.regular_price).toLocaleString()"></span>
                            </template>
                        </div>
                    </div>
                </a>
                <button @click="addToCart(product.id)" class="add-to-cart-btn">Add to Cart</button>
            </div>
        </template>
    </div>
</section>

{{-- ===================== NEWSLETTER ===================== --}}
<section class="newsletter-section">
    <div class="newsletter-inner">
        <div class="newsletter-text">
            <p class="section-eyebrow section-eyebrow--light">Stay in the loop</p>
            <h2>Get 10% Off<br>Your First Order</h2>
            <p class="newsletter-sub">Subscribe for exclusive deals and new arrivals from around the world.</p>
        </div>
        <div class="newsletter-form">
            <input type="email" id="newsletterEmail" placeholder="Your email address" class="newsletter-input">
            <button onclick="subscribeNewsletter()" class="newsletter-btn">Subscribe</button>
        </div>
    </div>
</section>

{{-- ===================== BRANDS ===================== --}}
<section class="brands-section">
    <p class="section-eyebrow" style="text-align:center; margin-bottom: 28px;">Trusted Brands</p>
    <div class="brands-strip">
        <span>Zojirushi</span>
        <span>Tiger</span>
        <span>Panasonic</span>
        <span>Nissin</span>
        <span>Meiji</span>
        <span>Barilla</span>
        <span>Lavazza</span>
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
    n.className = `notif notif--${type}`;
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

{{-- ===================== STYLES ===================== --}}
<style>
/* ============================================================
   TOKENS
   ============================================================ */
:root {
    --c-black:   #111111;
    --c-white:   #ffffff;
    --c-gray-50: #f9f9f7;
    --c-gray-100:#f0efed;
    --c-gray-300:#d0cfcd;
    --c-gray-500:#8a8986;
    --c-accent:  #c0392b;
    --c-accent-h:#a93226;
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 16px;
    --shadow-sm: 0 1px 4px rgba(0,0,0,0.07);
    --shadow-md: 0 4px 20px rgba(0,0,0,0.09);
    --shadow-lg: 0 8px 36px rgba(0,0,0,0.13);
}

/* ============================================================
   SHARED SECTION LAYOUT
   ============================================================ */
.section-wrap {
    padding: 72px 60px;
    background: var(--c-white);
}

.section-alt {
    background: var(--c-gray-50);
}

.section-head {
    margin-bottom: 40px;
}

.section-head--inline {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
}

.section-eyebrow {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--c-accent);
    margin: 0 0 6px;
}

.section-eyebrow--light {
    color: rgba(255,255,255,0.65);
}

.section-title {
    font-size: clamp(1.5rem, 2.5vw, 2.1rem);
    font-weight: 700;
    color: var(--c-black);
    letter-spacing: -0.025em;
    margin: 0;
    line-height: 1.1;
}

.view-all-link {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--c-black);
    text-decoration: none;
    letter-spacing: 0.02em;
    border-bottom: 1px solid var(--c-black);
    padding-bottom: 1px;
    white-space: nowrap;
    margin-bottom: 4px;
    transition: color 0.2s, border-color 0.2s;
}

.view-all-link:hover {
    color: var(--c-accent);
    border-color: var(--c-accent);
}

.loading-row {
    display: flex;
    justify-content: center;
    padding: 48px 0;
}

/* ============================================================
   HERO
   ============================================================ */
.hero-carousel {
    position: relative;
    width: 100%;
    height: 100vh;
    overflow: hidden;
    background: #111;
}

.hero-slide {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    opacity: 0;
    transition: opacity 0.8s ease;
    pointer-events: none;
}

.hero-slide.active { opacity: 1; pointer-events: auto; }

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.72) 0%, rgba(0,0,0,0.18) 55%, transparent 100%);
}

.hero-content {
    position: absolute;
    bottom: 80px;
    left: 60px;
    color: #fff;
    max-width: 520px;
}

.hero-content h2 {
    font-size: clamp(2rem, 4vw, 3.5rem);
    font-weight: 800;
    line-height: 1.15;
    letter-spacing: -0.02em;
    margin: 0 0 12px;
    text-shadow: 0 2px 12px rgba(0,0,0,0.3);
}

.hero-content p {
    font-size: 1.05rem;
    opacity: 0.85;
    margin: 0 0 24px;
    line-height: 1.55;
}

.hero-btn {
    display: inline-block;
    background: #fff;
    color: #111;
    padding: 13px 30px;
    font-weight: 700;
    font-size: 0.85rem;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
}

.hero-btn:hover { background: var(--c-accent); color: #fff; }

.hero-dots {
    position: absolute;
    bottom: 28px;
    left: 60px;
    display: flex;
    gap: 8px;
    align-items: center;
}

.hero-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: rgba(255,255,255,0.4);
    border: none; cursor: pointer; padding: 0;
    transition: background 0.2s, transform 0.2s;
}

.hero-dot.active { background: #fff; transform: scale(1.5); }

.hero-loading {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ============================================================
   SPINNERS
   ============================================================ */
.spinner {
    width: 36px; height: 36px;
    border: 3px solid rgba(255,255,255,0.2);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

.spinner--dark {
    border-color: rgba(0,0,0,0.1);
    border-top-color: var(--c-accent);
}

@keyframes spin { to { transform: rotate(360deg); } }

/* ============================================================
   CATEGORIES
   ============================================================ */
.categories-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

.cat-card {
    display: block;
    height: 420px;
    perspective: 1200px;
    text-decoration: none;
    border-radius: var(--radius-md);
    overflow: hidden;
}

.cat-card-inner {
    position: relative;
    width: 100%; height: 100%;
    transform-style: preserve-3d;
    transition: transform 0.65s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: var(--radius-md);
}

.cat-card:hover .cat-card-inner { transform: rotateY(180deg); }

.cat-card-front,
.cat-card-back {
    position: absolute;
    inset: 0;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    border-radius: var(--radius-md);
    overflow: hidden;
}

.cat-card-front {
    background-size: cover;
    background-position: center;
}

.cat-front-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.62) 0%, rgba(0,0,0,0.08) 60%, transparent 100%);
    border-radius: var(--radius-md);
}

.cat-label {
    position: absolute;
    bottom: 28px;
    left: 24px;
    color: #fff;
}

.cat-label h3 {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0 0 4px;
    letter-spacing: -0.01em;
}

.cat-label p {
    font-size: 0.75rem;
    opacity: 0.72;
    margin: 0;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.cat-card-back {
    background: var(--c-black);
    transform: rotateY(180deg);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 14px;
    color: #fff;
}

.cat-icon-back { font-size: 2.6rem; }

.cat-card-back h3 {
    font-size: 1.3rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    margin: 0;
}

.shop-now-btn {
    display: inline-block;
    margin-top: 4px;
    border: 1.5px solid rgba(255,255,255,0.5);
    padding: 10px 26px;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    transition: border-color 0.2s, background 0.2s, color 0.2s;
    border-radius: var(--radius-sm);
}

.cat-card-back:hover .shop-now-btn {
    background: #fff;
    color: var(--c-black);
    border-color: #fff;
}

/* ============================================================
   FLASH SALE
   ============================================================ */
.flash-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.flash-card {
    display: flex;
    gap: 16px;
    background: var(--c-white);
    border-radius: var(--radius-md);
    padding: 16px;
    text-decoration: none;
    color: inherit;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--c-gray-100);
    transition: box-shadow 0.2s, transform 0.2s;
}

.flash-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.flash-img-wrap {
    position: relative;
    width: 90px;
    height: 90px;
    flex-shrink: 0;
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: var(--c-gray-100);
}

.flash-img { width: 100%; height: 100%; object-fit: cover; }

.flash-badge {
    position: absolute;
    top: 6px; left: 6px;
    background: var(--c-accent);
    color: #fff;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 4px;
}

.flash-info { flex: 1; display: flex; flex-direction: column; justify-content: center; }

.flash-name {
    font-size: 0.92rem;
    font-weight: 600;
    color: var(--c-black);
    margin: 0 0 4px;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.flash-sub {
    font-size: 0.75rem;
    color: var(--c-gray-500);
    margin: 0 0 8px;
}

.flash-price-row { display: flex; align-items: baseline; gap: 8px; }

.flash-price {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--c-accent);
}

.flash-was {
    font-size: 0.78rem;
    color: var(--c-gray-500);
    text-decoration: line-through;
}

/* ============================================================
   PRODUCT CARDS
   ============================================================ */
.products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.product-card {
    background: var(--c-white);
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--c-gray-100);
    transition: box-shadow 0.2s, transform 0.2s;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-3px);
}

.product-card a { text-decoration: none; color: inherit; flex: 1; display: flex; flex-direction: column; }

.product-img-wrap {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: var(--c-gray-100);
}

.product-img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.product-card:hover .product-img { transform: scale(1.04); }

.product-badge {
    position: absolute;
    top: 10px; right: 10px;
    background: var(--c-accent);
    color: #fff;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 4px;
}

.product-badge--new {
    left: 10px; right: auto;
    background: #2d7a4a;
}

.product-badge--sale {
    right: 10px;
}

.product-info {
    padding: 14px 16px 8px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-origin {
    font-size: 0.72rem;
    color: var(--c-gray-500);
    margin: 0 0 5px;
}

.product-name {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--c-black);
    margin: 0 0 10px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.product-prices { display: flex; align-items: baseline; gap: 8px; flex-wrap: wrap; }

.product-price--sale {
    font-size: 1rem;
    font-weight: 700;
    color: var(--c-accent);
}

.product-price--was {
    font-size: 0.78rem;
    color: var(--c-gray-500);
    text-decoration: line-through;
}

.product-price--reg {
    font-size: 1rem;
    font-weight: 700;
    color: var(--c-black);
}

.add-to-cart-btn {
    margin: 8px 16px 16px;
    padding: 10px 0;
    background: var(--c-black);
    color: #fff;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.04em;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: background 0.2s;
}

.add-to-cart-btn:hover { background: var(--c-accent); }

/* ============================================================
   NEWSLETTER
   ============================================================ */
.newsletter-section {
    background: var(--c-black);
    padding: 80px 60px;
}

.newsletter-inner {
    max-width: 900px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 80px;
}

.newsletter-text { flex: 1; color: #fff; }

.newsletter-text h2 {
    font-size: clamp(1.8rem, 3vw, 2.8rem);
    font-weight: 800;
    letter-spacing: -0.03em;
    line-height: 1.1;
    margin: 8px 0 16px;
    color: #fff;
}

.newsletter-sub {
    font-size: 0.92rem;
    color: rgba(255,255,255,0.6);
    line-height: 1.6;
    margin: 0;
}

.newsletter-form {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.newsletter-input {
    padding: 14px 18px;
    border: 1px solid rgba(255,255,255,0.15);
    background: rgba(255,255,255,0.06);
    color: #fff;
    font-size: 0.9rem;
    border-radius: var(--radius-sm);
    outline: none;
    transition: border-color 0.2s;
}

.newsletter-input::placeholder { color: rgba(255,255,255,0.35); }
.newsletter-input:focus { border-color: rgba(255,255,255,0.4); }

.newsletter-btn {
    padding: 14px 0;
    background: var(--c-accent);
    color: #fff;
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: background 0.2s;
}

.newsletter-btn:hover { background: var(--c-accent-h); }

/* ============================================================
   BRANDS
   ============================================================ */
.brands-section {
    background: var(--c-white);
    padding: 56px 60px;
    border-top: 1px solid var(--c-gray-100);
}

.brands-strip {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 40px 56px;
    align-items: center;
}

.brands-strip span {
    font-size: 1.1rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    color: var(--c-gray-300);
    text-transform: uppercase;
    font-style: italic;
    transition: color 0.2s;
}

.brands-strip span:hover { color: var(--c-black); }

/* ============================================================
   NOTIFICATION TOAST
   ============================================================ */
.notif {
    position: fixed;
    bottom: 24px;
    right: 24px;
    padding: 12px 22px;
    border-radius: var(--radius-sm);
    font-size: 0.85rem;
    font-weight: 600;
    color: #fff;
    z-index: 9999;
    transition: opacity 0.3s;
    box-shadow: var(--shadow-lg);
}

.notif--success { background: #2d7a4a; }
.notif--error   { background: var(--c-accent); }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 1100px) {
    .flash-grid { grid-template-columns: repeat(2, 1fr); }
    .products-grid { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 900px) {
    .section-wrap { padding: 52px 24px; }
    .categories-grid { grid-template-columns: repeat(2, 1fr); }
    .cat-card { height: 300px; }
    .products-grid { grid-template-columns: repeat(2, 1fr); }
    .flash-grid { grid-template-columns: 1fr; }
    .newsletter-inner { flex-direction: column; gap: 40px; }
    .hero-content { left: 24px; bottom: 60px; }
    .hero-dots { left: 24px; }
    .newsletter-section { padding: 56px 24px; }
    .brands-section { padding: 40px 24px; }
}

@media (max-width: 540px) {
    .hero-carousel { height: 75vh; }
    .categories-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .cat-card { height: 240px; }
    .products-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .product-img-wrap { height: 160px; }
}
</style>

@endsection