

<?php $__env->startSection('title', 'Shop'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4">
    <!-- Type Filter Buttons (Food/Appliance) -->
    <div class="flex gap-3 mb-4">
        <button onclick="filterByType('')" id="filterAllBtn" class="flex-1 py-2 rounded-full bg-red-600 text-white font-medium transition">
            All
        </button>
        <button onclick="filterByType('food')" id="filterFoodBtn" class="flex-1 py-2 rounded-full bg-gray-200 text-gray-700 font-medium transition">
            🍜 Food
        </button>
        <button onclick="filterByType('appliance')" id="filterApplianceBtn" class="flex-1 py-2 rounded-full bg-gray-200 text-gray-700 font-medium transition">
            🔌 Appliances
        </button>
    </div>
    
    <!-- Filter Bar (Sort + Filter Button) -->
    <div class="flex justify-between items-center mb-4">
        <select id="sortSelect" class="px-3 py-2 border rounded-lg text-sm bg-white">
            <option value="newest">Newest First</option>
            <option value="price_low">Price: Low to High</option>
            <option value="price_high">Price: High to Low</option>
            <option value="oldest">Oldest First</option>
        </select>
        
        <button onclick="openFilterModal()" class="flex items-center gap-2 px-4 py-2 border rounded-lg text-sm bg-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
            </svg>
            Filter
        </button>
    </div>
    
    <!-- Products Grid -->
    <div id="productsGrid" class="space-y-3">
        <div class="text-center py-12 text-gray-500" id="loadingMessage">Loading products...</div>
    </div>
    
    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="text-center py-4 hidden">
        <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-red-600"></div>
        <p class="text-xs text-gray-500 mt-1">Loading...</p>
    </div>
    
    <!-- No More Products -->
    <div id="noMoreProducts" class="text-center py-8 text-gray-500 text-sm hidden">
        No more products
    </div>
</div>

<!-- Filter Modal (Bottom Sheet) -->
<div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" onclick="closeFilterModal(event)">
    <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-2xl max-h-[80vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold">Filters</h3>
            <button onclick="closeFilterModal()" class="text-gray-500 text-xl">&times;</button>
        </div>
        
        <div class="p-4 space-y-6">
            <!-- Categories -->
            <div>
                <h4 class="font-medium mb-2">Categories</h4>
                <div id="mobileCategories" class="flex flex-wrap gap-2">
                    <div class="text-sm text-gray-500">Loading...</div>
                </div>
            </div>
            
            <!-- Brands -->
            <div>
                <h4 class="font-medium mb-2">Brands</h4>
                <div id="mobileBrands" class="flex flex-wrap gap-2">
                    <div class="text-sm text-gray-500">Loading...</div>
                </div>
            </div>
            
            <!-- Origins -->
            <div>
                <h4 class="font-medium mb-2">Country of Origin</h4>
                <div id="mobileOrigins" class="flex flex-wrap gap-2">
                    <div class="text-sm text-gray-500">Loading...</div>
                </div>
            </div>
            
            <!-- Price Range -->
            <div>
                <h4 class="font-medium mb-2">Price Range</h4>
                <div class="flex gap-3">
                    <input type="number" id="mobileMinPrice" placeholder="Min" class="flex-1 px-3 py-2 border rounded-lg text-sm">
                    <input type="number" id="mobileMaxPrice" placeholder="Max" class="flex-1 px-3 py-2 border rounded-lg text-sm">
                </div>
            </div>
        </div>
        
        <div class="sticky bottom-0 bg-white p-4 border-t flex gap-3">
            <button onclick="resetMobileFilters()" class="flex-1 py-3 border rounded-lg text-gray-700">Reset</button>
            <button onclick="applyMobileFilters()" class="flex-1 py-3 bg-red-600 text-white rounded-lg">Apply Filters</button>
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

    document.getElementById('filterAllBtn').className = 'flex-1 py-2 rounded-full font-medium transition ' + (type === '' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700');
    document.getElementById('filterFoodBtn').className = 'flex-1 py-2 rounded-full font-medium transition ' + (type === 'food' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700');
    document.getElementById('filterApplianceBtn').className = 'flex-1 py-2 rounded-full font-medium transition ' + (type === 'appliance' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700');

    document.getElementById('productsGrid').innerHTML = '';
    document.getElementById('noMoreProducts').classList.add('hidden');
    loadProducts();
}

// Load products
async function loadProducts() {
    if (isLoading || !hasMore) return;

    isLoading = true;
    document.getElementById('loadingIndicator').classList.remove('hidden');

    // Capture page BEFORE incrementing
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
        grid.innerHTML = '<div class="text-center py-8 text-gray-500">No products found</div>';
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
            ? `<span class="bg-red-600 text-white text-xs px-2 py-0.5 rounded">-${discountPercent}%</span>`
            : '';

        const priceHtml = hasSale
            ? `<div class="flex items-center gap-2">
                <span class="text-red-600 font-bold">LKR ${parseFloat(product.sale_price).toLocaleString()}</span>
                <span class="text-gray-400 text-sm line-through">LKR ${parseFloat(product.regular_price).toLocaleString()}</span>
               </div>`
            : `<div><span class="text-gray-800 font-bold">LKR ${parseFloat(product.regular_price).toLocaleString()}</span></div>`;

        const originHtml = product.origin
            ? `<div class="text-xs text-gray-500 mt-1">${product.origin.flag_icon || '🌏'} ${escapeHtml(product.origin.country_name)}</div>`
            : '';

        const card = `
            <div class="bg-white rounded-lg shadow p-3 flex gap-3">
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
                    <button onclick="addToCart(${product.id})" class="mt-2 w-full bg-red-600 text-white py-1.5 rounded text-sm hover:bg-red-700 transition">
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
// Add escapeHtml function at the top of your script
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
    toast.className = `fixed bottom-20 left-4 right-4 text-center px-4 py-2 rounded-lg text-white ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} z-50`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}

// Filter modal
async function openFilterModal() {
    await loadFilterOptions();
    document.getElementById('filterModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeFilterModal(event) {
    if (!event || event.target === document.getElementById('filterModal')) {
        document.getElementById('filterModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
}

async function loadFilterOptions() {
    try {
        const response = await fetch('/api/products/filters');
        const data = await response.json();

        const categoriesHtml = data.categories.map(cat => `
            <button onclick="toggleFilter('categories', '${cat.id}', this)"
                    class="px-3 py-1 rounded-full text-sm border ${currentFilters.categories.includes(String(cat.id)) ? 'bg-red-600 text-white border-red-600' : 'bg-gray-100 text-gray-700 border-gray-200'}">
                ${cat.name}
            </button>
        `).join('');
        document.getElementById('mobileCategories').innerHTML = categoriesHtml || '<div class="text-gray-500 text-sm">No categories</div>';

        const brandsHtml = data.brands.map(brand => `
            <button onclick="toggleFilter('brands', '${brand.id}', this)"
                    class="px-3 py-1 rounded-full text-sm border ${currentFilters.brands.includes(String(brand.id)) ? 'bg-red-600 text-white border-red-600' : 'bg-gray-100 text-gray-700 border-gray-200'}">
                ${brand.name}
            </button>
        `).join('');
        document.getElementById('mobileBrands').innerHTML = brandsHtml || '<div class="text-gray-500 text-sm">No brands</div>';

        const originsHtml = data.origins.map(origin => `
            <button onclick="toggleFilter('origins', '${origin.id}', this)"
                    class="px-3 py-1 rounded-full text-sm border ${currentFilters.origins.includes(String(origin.id)) ? 'bg-red-600 text-white border-red-600' : 'bg-gray-100 text-gray-700 border-gray-200'}">
                ${origin.flag_icon || '🌏'} ${origin.country_name}
            </button>
        `).join('');
        document.getElementById('mobileOrigins').innerHTML = originsHtml || '<div class="text-gray-500 text-sm">No origins</div>';

        document.getElementById('mobileMinPrice').value = currentFilters.min_price;
        document.getElementById('mobileMaxPrice').value = currentFilters.max_price;

    } catch (error) {
        console.error('Error loading filters:', error);
    }
}

// Toggle filter chip - pass button element directly, no more broken data reference
function toggleFilter(type, id, btn) {
    const index = currentFilters[type].indexOf(String(id));
    if (index > -1) {
        currentFilters[type].splice(index, 1);
        btn.className = 'px-3 py-1 rounded-full text-sm border bg-gray-100 text-gray-700 border-gray-200';
    } else {
        currentFilters[type].push(String(id));
        btn.className = 'px-3 py-1 rounded-full text-sm border bg-red-600 text-white border-red-600';
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
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 300) {
        loadProducts();
    }
});

// Initialize
loadProducts();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/mobile/shop.blade.php ENDPATH**/ ?>