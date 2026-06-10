<?php $__env->startSection('title', 'Shop'); ?>

<?php $__env->startSection('content'); ?>
<div class="pb-24 bg-gray-50">
    
    <!-- Hero Banner -->
    <div class="relative h-40 overflow-hidden bg-black">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?php echo e(asset('images/shop-banner.jpg')); ?>')"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/30"></div>
        <div class="absolute inset-0 flex flex-col justify-center px-6">
            <h1 class="text-3xl font-bold text-white font-['Playfair_Display']">The Collection</h1>
            <p class="text-white/70 text-sm mt-1">Discover our curated selection</p>
        </div>
    </div>
    
    <div class="px-4 py-4">
        <!-- Type Filter Buttons -->
        <div class="flex gap-2 mb-4 bg-white p-1 rounded-full shadow-sm">
            <button onclick="filterByType('')" id="filterAllBtn" class="flex-1 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-red-600 text-white shadow-sm">
                All
            </button>
            <button onclick="filterByType('food')" id="filterFoodBtn" class="flex-1 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-gray-100 text-gray-700">
                🍜 Food
            </button>
            <button onclick="filterByType('appliance')" id="filterApplianceBtn" class="flex-1 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-gray-100 text-gray-700">
                🔌 Appliances
            </button>
        </div>
        
        <!-- Filter Bar -->
        <div class="flex justify-between items-center gap-3 mb-4">
            <select id="sortSelect" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:border-red-500">
                <option value="newest">✨ Newest First</option>
                <option value="price_low">💰 Price: Low to High</option>
                <option value="price_high">💰 Price: High to Low</option>
                <option value="oldest">📅 Oldest First</option>
            </select>
            
            <button onclick="openFilterModal()" class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-sm bg-white hover:border-red-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
                <span>Filter</span>
            </button>
        </div>
        
        <!-- Products Count -->
        <div id="productsCount" class="text-xs text-gray-400 mb-3"></div>
        
        <!-- Products Grid -->
        <div id="productsGrid" class="space-y-3">
            <div class="flex justify-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-2 border-gray-300 border-t-red-600"></div>
            </div>
        </div>
        
        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="text-center py-4 hidden">
            <div class="inline-flex items-center gap-2">
                <div class="animate-spin rounded-full h-5 w-5 border-2 border-gray-300 border-t-red-600"></div>
                <p class="text-xs text-gray-400">Loading more...</p>
            </div>
        </div>
        
        <!-- No More Products -->
        <div id="noMoreProducts" class="text-center py-8 text-gray-400 text-sm hidden">
            You've seen it all
        </div>
    </div>
</div>

<!-- Filter Modal (Bottom Sheet) -->
<div id="filterModal" class="fixed inset-0 bg-black/50 z-50 hidden items-end" onclick="closeFilterModal(event)">
    <div class="bg-white rounded-t-2xl w-full max-h-[85vh] overflow-y-auto animate-slide-up" onclick="event.stopPropagation()">
        <div class="sticky top-0 bg-white px-5 py-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold">Filters</h3>
            <button onclick="closeFilterModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        
        <div class="p-5 space-y-6">
            <!-- Categories -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-3">Categories</h4>
                <div id="mobileCategories" class="flex flex-wrap gap-2">
                    <div class="text-sm text-gray-400">Loading...</div>
                </div>
            </div>
            
            <!-- Brands -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-3">Brands</h4>
                <div id="mobileBrands" class="flex flex-wrap gap-2">
                    <div class="text-sm text-gray-400">Loading...</div>
                </div>
            </div>
            
            <!-- Origins -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-3">Country of Origin</h4>
                <div id="mobileOrigins" class="flex flex-wrap gap-2">
                    <div class="text-sm text-gray-400">Loading...</div>
                </div>
            </div>
            
            <!-- Price Range -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-3">Price Range (LKR)</h4>
                <div class="flex gap-3">
                    <div class="flex-1">
                        <label class="text-xs text-gray-500 mb-1 block">Min</label>
                        <input type="number" id="mobileMinPrice" placeholder="0" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-red-500">
                    </div>
                    <span class="text-gray-400 self-end mb-2">—</span>
                    <div class="flex-1">
                        <label class="text-xs text-gray-500 mb-1 block">Max</label>
                        <input type="number" id="mobileMaxPrice" placeholder="Any" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-red-500">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="sticky bottom-0 bg-white p-4 border-t flex gap-3">
            <button onclick="resetMobileFilters()" class="flex-1 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition-colors">Reset</button>
            <button onclick="applyMobileFilters()" class="flex-1 py-3 bg-red-600 text-white rounded-xl font-medium hover:bg-red-700 transition-colors">Apply Filters</button>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let isLoading = false;
let hasMore = true;
let currentType = '';
let currentFilters = {
    categories: [],
    brands: [],
    origins: [],
    min_price: '',
    max_price: '',
    sort: 'newest'
};

// Filter by type
function filterByType(type) {
    currentType = type;
    currentPage = 1;
    hasMore = true;
    
    const btnClass = (isActive) => isActive 
        ? 'flex-1 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-red-600 text-white shadow-sm'
        : 'flex-1 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-gray-100 text-gray-700';
    
    document.getElementById('filterAllBtn').className = btnClass(type === '');
    document.getElementById('filterFoodBtn').className = btnClass(type === 'food');
    document.getElementById('filterApplianceBtn').className = btnClass(type === 'appliance');
    
    document.getElementById('productsGrid').innerHTML = '';
    document.getElementById('noMoreProducts').classList.add('hidden');
    loadProducts();
}

// Load products
async function loadProducts() {
    if (isLoading || !hasMore) return;
    
    isLoading = true;
    document.getElementById('loadingIndicator').classList.remove('hidden');
    
    const pageToLoad = currentPage;
    
    try {
        const params = new URLSearchParams();
        params.append('page', pageToLoad);
        params.append('sort', currentFilters.sort);
        if (currentType) params.append('type', currentType);
        if (currentFilters.min_price) params.append('min_price', currentFilters.min_price);
        if (currentFilters.max_price) params.append('max_price', currentFilters.max_price);
        currentFilters.categories.forEach(id => params.append('categories[]', id));
        currentFilters.brands.forEach(id => params.append('brands[]', id));
        currentFilters.origins.forEach(id => params.append('origins[]', id));
        
        const response = await fetch(`/api/products?${params}`);
        const data = await response.json();
        
        if (pageToLoad === 1) {
            document.getElementById('productsGrid').innerHTML = '';
        }
        
        renderProducts(data.data, pageToLoad);
        
        const from = data.from || 0;
        const to = data.to || 0;
        const total = data.total || 0;
        document.getElementById('productsCount').innerHTML = total ? `Showing ${from}–${to} of ${total} products` : '';
        
        hasMore = data.current_page < data.last_page;
        currentPage++;
        
        if (!hasMore) {
            document.getElementById('noMoreProducts').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('productsGrid').innerHTML = '<div class="text-center py-8 text-red-500">Failed to load products. Please refresh.</div>';
    } finally {
        isLoading = false;
        document.getElementById('loadingIndicator').classList.add('hidden');
    }
}

// Render products
function renderProducts(products, pageToLoad) {
    const grid = document.getElementById('productsGrid');
    
    if (products.length === 0 && pageToLoad === 1) {
        grid.innerHTML = '<div class="text-center py-12 text-gray-400">No products found</div>';
        return;
    }
    
    if (pageToLoad === 1) {
        grid.innerHTML = '';
    }
    
    products.forEach(product => {
        let imageUrl = '/images/placeholder.jpg';
        if (product.images && product.images.length > 0 && product.images[0].image_path) {
            imageUrl = `/storage/${product.images[0].image_path}`;
        }
        
        const hasSale = product.sale_price && parseFloat(product.sale_price) < parseFloat(product.regular_price);
        const discountPercent = product.discount_percent > 0 ? product.discount_percent : 
            (hasSale ? Math.round(((product.regular_price - product.sale_price) / product.regular_price) * 100) : 0);
        
        const discountBadge = discountPercent > 0
            ? `<span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">-${discountPercent}%</span>`
            : '';
        
        const priceHtml = hasSale
            ? `<div class="flex items-center gap-2">
                <span class="text-red-600 font-bold">LKR ${parseFloat(product.sale_price).toLocaleString()}</span>
                <span class="text-gray-400 text-xs line-through">LKR ${parseFloat(product.regular_price).toLocaleString()}</span>
               </div>`
            : `<div><span class="text-gray-800 font-bold">LKR ${parseFloat(product.regular_price).toLocaleString()}</span></div>`;
        
        const originHtml = product.origin
            ? `<div class="text-xs text-gray-500 mt-1">${product.origin.flag_icon || '🌏'} ${escapeHtml(product.origin.country_name)}</div>`
            : '';
        
        const card = `
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 flex gap-3 transition-all hover:shadow-md">
                <a href="/mobile/product/${product.slug}" class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                    <img src="${imageUrl}" class="w-full h-full object-cover" onerror="this.src='/images/placeholder.jpg'">
                </a>
                <div class="flex-1 min-w-0">
                    <a href="/mobile/product/${product.slug}">
                        <div class="flex justify-between items-start gap-1">
                            <h3 class="font-semibold text-sm flex-1 line-clamp-2">${escapeHtml(product.name)}</h3>
                            ${discountBadge}
                        </div>
                        ${originHtml}
                        <div class="mt-2">${priceHtml}</div>
                    </a>
                    <button onclick="addToCart(${product.id})" class="mt-2 w-full bg-gray-900 hover:bg-red-600 text-white py-2 rounded-lg text-xs font-semibold transition-colors duration-200">
                        Add to Cart
                    </button>
                </div>
            </div>
        `;
        
        grid.innerHTML += card;
    });
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Add to cart
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
    toast.className = `fixed bottom-24 left-4 right-4 text-center px-4 py-2.5 rounded-xl text-white z-50 transition-all duration-300 ${type === 'success' ? 'bg-emerald-600' : 'bg-red-600'}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}

// Filter modal
async function openFilterModal() {
    await loadFilterOptions();
    document.getElementById('filterModal').classList.remove('hidden');
    document.getElementById('filterModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeFilterModal(event) {
    if (!event || event.target === document.getElementById('filterModal') || event.target.closest('.bg-white/50')) {
        document.getElementById('filterModal').classList.add('hidden');
        document.getElementById('filterModal').classList.remove('flex');
        document.body.style.overflow = '';
    }
}

async function loadFilterOptions() {
    try {
        const response = await fetch('/api/products/filters');
        const data = await response.json();
        
        const categoriesHtml = data.categories.map(cat => `
            <button onclick="toggleFilter('categories', '${cat.id}', this)"
                    class="px-3 py-1.5 rounded-full text-sm border transition-all duration-200 ${currentFilters.categories.includes(String(cat.id)) ? 'bg-red-600 text-white border-red-600' : 'bg-gray-100 text-gray-700 border-gray-200 hover:border-red-300'}">
                ${cat.name}
            </button>
        `).join('');
        document.getElementById('mobileCategories').innerHTML = categoriesHtml || '<div class="text-gray-400 text-sm">No categories</div>';
        
        const brandsHtml = data.brands.map(brand => `
            <button onclick="toggleFilter('brands', '${brand.id}', this)"
                    class="px-3 py-1.5 rounded-full text-sm border transition-all duration-200 ${currentFilters.brands.includes(String(brand.id)) ? 'bg-red-600 text-white border-red-600' : 'bg-gray-100 text-gray-700 border-gray-200 hover:border-red-300'}">
                ${brand.name}
            </button>
        `).join('');
        document.getElementById('mobileBrands').innerHTML = brandsHtml || '<div class="text-gray-400 text-sm">No brands</div>';
        
        const originsHtml = data.origins.map(origin => `
            <button onclick="toggleFilter('origins', '${origin.id}', this)"
                    class="px-3 py-1.5 rounded-full text-sm border transition-all duration-200 ${currentFilters.origins.includes(String(origin.id)) ? 'bg-red-600 text-white border-red-600' : 'bg-gray-100 text-gray-700 border-gray-200 hover:border-red-300'}">
                ${origin.flag_icon || '🌏'} ${origin.country_name}
            </button>
        `).join('');
        document.getElementById('mobileOrigins').innerHTML = originsHtml || '<div class="text-gray-400 text-sm">No origins</div>';
        
        document.getElementById('mobileMinPrice').value = currentFilters.min_price;
        document.getElementById('mobileMaxPrice').value = currentFilters.max_price;
        
    } catch (error) {
        console.error('Error loading filters:', error);
    }
}

function toggleFilter(type, id, btn) {
    const index = currentFilters[type].indexOf(String(id));
    if (index > -1) {
        currentFilters[type].splice(index, 1);
        btn.className = 'px-3 py-1.5 rounded-full text-sm border transition-all duration-200 bg-gray-100 text-gray-700 border-gray-200 hover:border-red-300';
    } else {
        currentFilters[type].push(String(id));
        btn.className = 'px-3 py-1.5 rounded-full text-sm border transition-all duration-200 bg-red-600 text-white border-red-600';
    }
}

function applyMobileFilters() {
    currentFilters.min_price = document.getElementById('mobileMinPrice').value;
    currentFilters.max_price = document.getElementById('mobileMaxPrice').value;
    currentPage = 1;
    hasMore = true;
    document.getElementById('productsGrid').innerHTML = '';
    document.getElementById('noMoreProducts').classList.add('hidden');
    loadProducts();
    closeFilterModal();
}

function resetMobileFilters() {
    currentFilters = {
        categories: [],
        brands: [],
        origins: [],
        min_price: '',
        max_price: '',
        sort: currentFilters.sort
    };
    document.getElementById('mobileMinPrice').value = '';
    document.getElementById('mobileMaxPrice').value = '';
    loadFilterOptions();
}

document.getElementById('sortSelect').addEventListener('change', function() {
    currentFilters.sort = this.value;
    currentPage = 1;
    hasMore = true;
    document.getElementById('productsGrid').innerHTML = '';
    document.getElementById('noMoreProducts').classList.add('hidden');
    loadProducts();
});

// Infinite scroll
window.addEventListener('scroll', () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 400) {
        loadProducts();
    }
});

// Initialize
loadProducts();
</script>

<style>
@keyframes slide-up {
    from {
        transform: translateY(100%);
    }
    to {
        transform: translateY(0);
    }
}
.animate-slide-up {
    animation: slide-up 0.3s ease-out;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\Herd\ja-lanka-ecommerce\resources\views/mobile/shop.blade.php ENDPATH**/ ?>