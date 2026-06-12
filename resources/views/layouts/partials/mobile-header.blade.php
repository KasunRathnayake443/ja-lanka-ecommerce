<div class="bg-white shadow-sm sticky top-0 z-30 px-4 py-3 flex items-center justify-between">
    <!-- Logo -->
    <a href="{{ route('mobile.home') }}" class="text-xl font-bold">
        <span class="text-red-600">Ja</span><span class="text-gray-800">Lanka</span>
    </a>
    
    <!-- Search Bar (always visible on mobile) -->
    <div class="flex-1 mx-4 relative">
        <input type="text" id="mobileSearchInput" placeholder="Search products..." 
               autocomplete="off"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500">
        <svg class="absolute right-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        
        <!-- Search Results Dropdown -->
        <div id="mobileSearchResults" class="absolute left-0 right-0 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 max-h-96 overflow-y-auto hidden z-50">
            <div class="p-3 text-sm text-gray-500 text-center" id="mobileSearchLoading" style="display: none;">
                <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-red-600 mr-2"></div>
                Searching...
            </div>
            <div id="mobileSearchResultsList"></div>
        </div>
    </div>
    
    <!-- Cart Icon -->
    <a href="{{ route('mobile.cart') }}" class="relative">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        <span id="mobileCartCount" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center hidden">0</span>
    </a>
</div>

<!-- Search JavaScript -->
<script>
// Mobile Search Functionality
const mobileSearchInput = document.getElementById('mobileSearchInput');
const mobileSearchResults = document.getElementById('mobileSearchResults');
const mobileSearchResultsList = document.getElementById('mobileSearchResultsList');
const mobileSearchLoading = document.getElementById('mobileSearchLoading');
let mobileSearchTimeout = null;

if (mobileSearchInput) {
    mobileSearchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        if (mobileSearchTimeout) clearTimeout(mobileSearchTimeout);
        
        if (query.length < 2) {
            mobileSearchResults.classList.add('hidden');
            return;
        }
        
        mobileSearchTimeout = setTimeout(() => {
            performMobileSearch(query);
        }, 300);
    });
    
    // Close results when clicking outside
    document.addEventListener('click', function(event) {
        if (!mobileSearchInput.contains(event.target) && !mobileSearchResults.contains(event.target)) {
            mobileSearchResults.classList.add('hidden');
        }
    });
}

async function performMobileSearch(query) {
    mobileSearchLoading.style.display = 'block';
    mobileSearchResultsList.innerHTML = '';
    mobileSearchResults.classList.remove('hidden');
    
    try {
        const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
        const products = await response.json();
        
        mobileSearchLoading.style.display = 'none';
        
        if (products.length === 0) {
            mobileSearchResultsList.innerHTML = '<div class="p-4 text-center text-gray-500">No products found</div>';
            return;
        }
        
        let html = '';
        products.forEach(product => {
            const imageUrl = product.images && product.images[0] 
                ? `/storage/${product.images[0].image_path}` 
                : '/images/placeholder.jpg';
            
            const hasSale = product.discount_percent > 0;
            const priceHtml = hasSale
                ? `<div>
                      <span class="text-red-600 font-semibold">LKR ${parseFloat(product.current_price).toLocaleString()}</span>
                      <span class="text-gray-400 text-xs line-through ml-1">LKR ${parseFloat(product.regular_price).toLocaleString()}</span>
                   </div>`
                : `<div class="text-gray-800 font-semibold">LKR ${parseFloat(product.regular_price).toLocaleString()}</div>`;
            
            html += `
                <a href="/mobile/product/${product.slug}" class="flex items-center gap-3 p-3 hover:bg-gray-50 border-b border-gray-100 last:border-0 transition" onclick="mobileSearchResults.classList.add('hidden'); mobileSearchInput.value = '';">
                    <img src="${imageUrl}" class="w-12 h-12 object-cover rounded" onerror="this.src='/images/placeholder.jpg'">
                    <div class="flex-1">
                        <div class="font-medium text-gray-800 text-sm">${escapeHtml(product.name)}</div>
                        ${priceHtml}
                    </div>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            `;
        });
        
        mobileSearchResultsList.innerHTML = html;
        
    } catch (error) {
        console.error('Search error:', error);
        mobileSearchLoading.style.display = 'none';
        mobileSearchResultsList.innerHTML = '<div class="p-4 text-center text-red-500">Error loading results</div>';
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>