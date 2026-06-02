@extends('layouts.desktop')

@section('title', 'Shop - All Products')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex gap-8">
        <!-- Sidebar Filters -->
        <div class="w-1/4">
            <div class="bg-white rounded-lg shadow p-5 sticky top-24">
                <h3 class="text-lg font-semibold mb-4 pb-2 border-b">Filters</h3>
                
                <!-- Type Filter -->
                <div class="mb-5">
                    <h4 class="font-medium mb-2">Product Type</h4>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="type" value="" checked class="mr-2 filter-radio">
                            <span class="text-sm">All Products</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="type" value="food" class="mr-2 filter-radio">
                            <span class="text-sm">🍜 Food Items</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="type" value="appliance" class="mr-2 filter-radio">
                            <span class="text-sm">🔌 Appliances</span>
                        </label>
                    </div>
                </div>
                
                <!-- Categories Filter -->
                <div class="mb-5">
                    <h4 class="font-medium mb-2">Categories</h4>
                    <div id="categories-filter" class="space-y-2 max-h-40 overflow-y-auto">
                        <div class="text-sm text-gray-500">Loading...</div>
                    </div>
                </div>
                
                <!-- Brands Filter -->
                <div class="mb-5">
                    <h4 class="font-medium mb-2">Brands</h4>
                    <div id="brands-filter" class="space-y-2 max-h-40 overflow-y-auto">
                        <div class="text-sm text-gray-500">Loading...</div>
                    </div>
                </div>
                
                <!-- Origins Filter -->
                <div class="mb-5">
                    <h4 class="font-medium mb-2">Country of Origin</h4>
                    <div id="origins-filter" class="space-y-2 max-h-40 overflow-y-auto">
                        <div class="text-sm text-gray-500">Loading...</div>
                    </div>
                </div>
                
                <!-- Price Range -->
                <div class="mb-5">
                    <h4 class="font-medium mb-2">Price Range</h4>
                    <div class="flex gap-2">
                        <input type="number" id="minPrice" placeholder="Min" class="w-1/2 px-3 py-1 border rounded text-sm">
                        <input type="number" id="maxPrice" placeholder="Max" class="w-1/2 px-3 py-1 border rounded text-sm">
                    </div>
                </div>
                
                <!-- Sort By -->
                <div class="mb-5">
                    <h4 class="font-medium mb-2">Sort By</h4>
                    <select id="sortBy" class="w-full px-3 py-2 border rounded text-sm">
                        <option value="newest">Newest First</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="oldest">Oldest First</option>
                    </select>
                </div>
                
                <button id="resetFilters" class="w-full bg-gray-200 text-gray-700 py-2 rounded text-sm hover:bg-gray-300 transition">
                    Reset All Filters
                </button>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="w-3/4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">All Products</h2>
                <p id="productsCount" class="text-gray-600">Loading...</p>
            </div>
            
            <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="col-span-3 text-center py-12 text-gray-500" id="loadingMessage">Loading products...</div>
            </div>
            
            <!-- Loading indicator -->
            <div id="loadingIndicator" class="text-center py-8 hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
                <p class="text-gray-500 mt-2">Loading more products...</p>
            </div>
            
            <!-- No more products message -->
            <div id="noMoreProducts" class="text-center py-8 text-gray-500 hidden">
                No more products to load
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let isLoading = false;
let hasMore = true;
let currentFilters = {
    type: '',
    categories: [],
    brands: [],
    origins: [],
    min_price: '',
    max_price: '',
    sort: 'newest'
};

// Load filters on page load
async function loadFilters() {
    try {
        const response = await fetch('/api/products/filters');
        const data = await response.json();
        
        const categoriesHtml = data.categories.map(cat => `
            <label class="flex items-center">
                <input type="checkbox" value="${cat.id}" class="mr-2 category-filter">
                <span class="text-sm">${cat.name}</span>
            </label>
        `).join('');
        document.getElementById('categories-filter').innerHTML = categoriesHtml || '<div class="text-sm text-gray-500">No categories</div>';
        
        const brandsHtml = data.brands.map(brand => `
            <label class="flex items-center">
                <input type="checkbox" value="${brand.id}" class="mr-2 brand-filter">
                <span class="text-sm">${brand.name}</span>
            </label>
        `).join('');
        document.getElementById('brands-filter').innerHTML = brandsHtml || '<div class="text-sm text-gray-500">No brands</div>';
        
        const originsHtml = data.origins.map(origin => `
            <label class="flex items-center">
                <input type="checkbox" value="${origin.id}" class="mr-2 origin-filter">
                <span class="text-sm">${origin.flag_icon || '🌏'} ${origin.country_name}</span>
            </label>
        `).join('');
        document.getElementById('origins-filter').innerHTML = originsHtml || '<div class="text-sm text-gray-500">No origins</div>';
        
        attachFilterListeners();
    } catch (error) {
        console.error('Error loading filters:', error);
    }
}

function attachFilterListeners() {
    document.querySelectorAll('.filter-radio').forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.checked) {
                currentFilters.type = radio.value;
                resetAndLoad();
            }
        });
    });
    
    document.querySelectorAll('.category-filter').forEach(cb => {
        cb.addEventListener('change', () => {
            currentFilters.categories = Array.from(document.querySelectorAll('.category-filter:checked')).map(c => c.value);
            resetAndLoad();
        });
    });
    
    document.querySelectorAll('.brand-filter').forEach(cb => {
        cb.addEventListener('change', () => {
            currentFilters.brands = Array.from(document.querySelectorAll('.brand-filter:checked')).map(c => c.value);
            resetAndLoad();
        });
    });
    
    document.querySelectorAll('.origin-filter').forEach(cb => {
        cb.addEventListener('change', () => {
            currentFilters.origins = Array.from(document.querySelectorAll('.origin-filter:checked')).map(c => c.value);
            resetAndLoad();
        });
    });
    
    document.getElementById('minPrice').addEventListener('input', function() {
        currentFilters.min_price = this.value;
        resetAndLoad();
    });
    
    document.getElementById('maxPrice').addEventListener('input', function() {
        currentFilters.max_price = this.value;
        resetAndLoad();
    });
    
    document.getElementById('sortBy').addEventListener('change', function() {
        currentFilters.sort = this.value;
        resetAndLoad();
    });
    
    document.getElementById('resetFilters').addEventListener('click', resetAllFilters);
}

function resetAllFilters() {
    document.querySelectorAll('.filter-radio').forEach(radio => {
        radio.checked = radio.value === '';
    });
    document.querySelectorAll('.category-filter, .brand-filter, .origin-filter').forEach(cb => {
        cb.checked = false;
    });
    document.getElementById('minPrice').value = '';
    document.getElementById('maxPrice').value = '';
    document.getElementById('sortBy').value = 'newest';
    currentFilters = { type: '', categories: [], brands: [], origins: [], min_price: '', max_price: '', sort: 'newest' };
    resetAndLoad();
}

function resetAndLoad() {
    currentPage = 1;
    hasMore = true;
    document.getElementById('productsGrid').innerHTML = '';
    document.getElementById('noMoreProducts').classList.add('hidden');
    loadProducts();
}

async function loadProducts() {
    if (isLoading || !hasMore) return;
    
    isLoading = true;
    document.getElementById('loadingIndicator').classList.remove('hidden');

    // Capture page number BEFORE incrementing
    const pageToLoad = currentPage;

    try {
        // Build params manually to handle arrays properly
        const params = new URLSearchParams();
        params.append('page', pageToLoad);
        params.append('sort', currentFilters.sort);
        if (currentFilters.type) params.append('type', currentFilters.type);
        if (currentFilters.min_price) params.append('min_price', currentFilters.min_price);
        if (currentFilters.max_price) params.append('max_price', currentFilters.max_price);
        currentFilters.categories.forEach(id => params.append('categories[]', id));
        currentFilters.brands.forEach(id => params.append('brands[]', id));
        currentFilters.origins.forEach(id => params.append('origins[]', id));

        const response = await fetch(`/api/products?${params}`);
        const data = await response.json();

        // Clear grid on first page load
        if (pageToLoad === 1) {
            document.getElementById('productsGrid').innerHTML = '';
        }

        renderProducts(data.data, pageToLoad);

        hasMore = data.current_page < data.last_page;
        currentPage++;

        document.getElementById('productsCount').innerText = `Showing ${data.from || 0}-${data.to || 0} of ${data.total} products`;

        if (!hasMore) {
            document.getElementById('noMoreProducts').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error loading products:', error);
        document.getElementById('productsGrid').innerHTML = '<div class="col-span-3 text-center py-12 text-red-500">Failed to load products. Please refresh.</div>';
    } finally {
        isLoading = false;
        document.getElementById('loadingIndicator').classList.add('hidden');
    }
}

function renderProducts(products, pageToLoad) {
    const grid = document.getElementById('productsGrid');

    if (products.length === 0 && pageToLoad === 1) {
        grid.innerHTML = '<div class="col-span-3 text-center py-12 text-gray-500">No products found</div>';
        return;
    }

    products.forEach(product => {
        let imageUrl = '/images/placeholder.jpg';
        if (product.images && product.images.length > 0 && product.images[0].image_path) {
            imageUrl = `/storage/${product.images[0].image_path}`;
        }

        // Calculate sale information
        const hasSale = product.sale_price && parseFloat(product.sale_price) < parseFloat(product.regular_price);
        const saleBadge = hasSale ? `<span class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded">-${product.discount_percent}%</span>` : '';
        
        // For products with sale but no discount_percent calculated
        const discountPercent = product.discount_percent > 0 ? product.discount_percent : 
            (hasSale ? Math.round(((product.regular_price - product.sale_price) / product.regular_price) * 100) : 0);
        const finalSaleBadge = discountPercent > 0 ? `<span class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded">-${discountPercent}%</span>` : '';

        const originHtml = product.origin
            ? `<div class="text-xs text-gray-500 mb-1">${product.origin.flag_icon || '🌏'} ${product.origin.country_name}</div>`
            : '';

        const priceHtml = hasSale
            ? `<div>
                <span class="text-red-600 font-bold">LKR ${parseFloat(product.sale_price).toLocaleString()}</span>
                <span class="text-gray-400 text-sm line-through ml-2">LKR ${parseFloat(product.regular_price).toLocaleString()}</span>
               </div>`
            : `<div><span class="text-gray-800 font-bold">LKR ${parseFloat(product.regular_price).toLocaleString()}</span></div>`;

        const card = `
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition group">
                <a href="/product/${product.slug}">
                    <div class="relative overflow-hidden rounded-t-lg h-48">
                        <img src="${imageUrl}" alt="${product.name}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" onerror="this.src='/images/placeholder.jpg'">
                        ${finalSaleBadge}
                    </div>
                    <div class="p-4">
                        ${originHtml}
                        <h3 class="font-semibold text-gray-800 mb-1 line-clamp-2">${escapeHtml(product.name)}</h3>
                        ${priceHtml}
                        <button onclick="event.preventDefault(); addToCart(${product.id})" class="mt-3 w-full bg-red-600 text-white py-2 rounded text-sm hover:bg-red-700 transition">
                            Add to Cart
                        </button>
                    </div>
                </a>
            </div>
        `;
        
        grid.innerHTML += card;
    });
}

// Add escapeHtml function at the top of your script
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

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
            showNotification('Product added to cart!');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification('Error adding to cart', 'error');
    }
}

function updateCartCount(count) {
    const cartCountElement = document.getElementById('cartCount');
    if (cartCountElement) {
        if (count > 0) {
            cartCountElement.textContent = count;
            cartCountElement.classList.remove('hidden');
        } else {
            cartCountElement.classList.add('hidden');
        }
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} z-50 transition-opacity`;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Infinite scroll
window.addEventListener('scroll', () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
        loadProducts();
    }
});

// Initialize
loadFilters();
loadProducts();
</script>
@endsection