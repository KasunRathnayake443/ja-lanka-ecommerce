<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ja Lanka - @yield('title', 'Global Flavors')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    
    <!-- Desktop Header -->
    @include('layouts.partials.desktop-header')
    
    <!-- Main Content - Add top padding for all pages except homepage -->
    <main class="min-h-screen {{ request()->routeIs('home') ? '' : 'pt-20 md:pt-24' }}">
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('layouts.partials.footer')
    
    <script>
        // Search toggle function
        function toggleSearch() {
            const searchBar = document.getElementById('desktopSearchBar');
            if (searchBar) {
                searchBar.classList.toggle('hidden');
                if (!searchBar.classList.contains('hidden')) {
                    document.getElementById('desktopSearchInput')?.focus();
                }
            }
        }
        
        // Close search when clicking outside
        document.addEventListener('click', function(event) {
            const searchBar = document.getElementById('desktopSearchBar');
            const searchBtn = document.getElementById('desktopSearchBtn');
            if (searchBar && !searchBar.classList.contains('hidden')) {
                if (!searchBar.contains(event.target) && !searchBtn?.contains(event.target)) {
                    searchBar.classList.add('hidden');
                }
            }
        });
    </script>
    
    <!-- Cart Modal -->
    @include('layouts.partials.cart-modal')

    <script>
    // Cart functions
    async function loadCart() {
        try {
            const response = await fetch('/api/cart');
            const data = await response.json();
            
            const cartContent = document.getElementById('cartContent');
            const cartFooter = document.getElementById('cartFooter');
            
            // Filter out items with null product (deleted products)
            const validItems = data.items.filter(item => item.product !== null);
            
            if (validItems.length === 0) {
                cartContent.innerHTML = `
                    <div class="text-center py-12">
                        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <p class="text-gray-500">Your cart is empty</p>
                        <button onclick="closeCartModal()" class="mt-4 text-red-600 hover:underline">Continue Shopping</button>
                    </div>
                `;
                cartFooter.classList.add('hidden');
                return;
            }
            
            cartFooter.classList.remove('hidden');
            
            let itemsHtml = '';
            let validTotal = 0;
            
            validItems.forEach(item => {
                if (!item.product) return;
                
                const imageUrl = item.product.images && item.product.images[0] 
                    ? `/storage/${item.product.images[0].image_path}` 
                    : '/images/placeholder.jpg';
                
                validTotal += item.quantity * parseFloat(item.price);
                
                itemsHtml += `
                    <div class="flex gap-3 border-b pb-4 mb-4" id="cart-item-${item.id}">
                        <img src="${imageUrl}" class="w-20 h-20 object-cover rounded">
                        <div class="flex-1">
                            <h3 class="font-semibold">${escapeHtml(item.product.name)}</h3>
                            <p class="text-gray-600 text-sm">LKR ${parseFloat(item.price).toLocaleString()}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <button onclick="updateCartQuantity(${item.id}, ${item.quantity - 1})" class="w-8 h-8 border rounded hover:bg-gray-100">-</button>
                                <span class="w-8 text-center">${item.quantity}</span>
                                <button onclick="updateCartQuantity(${item.id}, ${item.quantity + 1})" class="w-8 h-8 border rounded hover:bg-gray-100">+</button>
                                <button onclick="removeFromCart(${item.id})" class="ml-auto text-red-500 text-sm hover:underline">Remove</button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            cartContent.innerHTML = itemsHtml;
            
            document.getElementById('cartSubtotal').innerText = `LKR ${validTotal.toLocaleString()}`;
            document.getElementById('cartTotal').innerText = `LKR ${validTotal.toLocaleString()}`;
            
            // Update cart count in header
            const validCount = validItems.reduce((sum, item) => sum + item.quantity, 0);
            updateCartCount(validCount);
            
        } catch (error) {
            console.error('Error loading cart:', error);
            document.getElementById('cartContent').innerHTML = '<div class="text-center py-8 text-red-500">Error loading cart</div>';
        }
    }

    // Add escapeHtml function if not exists
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function openCartModal() {
        document.getElementById('cartModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        loadCart();
    }

    function closeCartModal(event) {
        if (!event || event.target === document.getElementById('cartModal')) {
            document.getElementById('cartModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    async function updateCartQuantity(itemId, newQuantity) {
        if (newQuantity < 1) {
            removeFromCart(itemId);
            return;
        }
        
        try {
            const response = await fetch(`/api/cart/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ quantity: newQuantity })
            });
            
            const data = await response.json();
            if (data.success) {
                updateCartCount(data.cart_count);
                loadCart();
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error updating cart', 'error');
        }
    }

    async function removeFromCart(itemId) {
        try {
            const response = await fetch(`/api/cart/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            if (data.success) {
                updateCartCount(data.cart_count);
                loadCart();
                showNotification('Item removed from cart');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error removing item', 'error');
        }
    }

    function proceedToCheckout() {
        window.location.href = '/checkout';
    }
    </script>

    <!-- Wishlist Modal -->
    @include('layouts.partials.wishlist-modal')

    <script>
    // Wishlist functions
    async function loadWishlist() {
        try {
            const response = await fetch('/api/wishlist');
            const data = await response.json();
            
            const wishlistContent = document.getElementById('wishlistContent');
            
            if (data.length === 0) {
                wishlistContent.innerHTML = `
                    <div class="text-center py-12">
                        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <p class="text-gray-500">Your wishlist is empty</p>
                        <a href="{{ route('shop') }}" class="inline-block mt-4 text-red-600 hover:underline">Start Shopping</a>
                    </div>
                `;
                return;
            }
            
            let itemsHtml = '';
            data.forEach(item => {
                const product = item.product;
                const imageUrl = product.images && product.images[0] 
                    ? `/storage/${product.images[0].image_path}` 
                    : '/images/placeholder.jpg';
                
                itemsHtml += `
                    <div class="flex gap-3 border-b pb-4 mb-4" id="wishlist-item-${product.id}">
                        <a href="/product/${product.slug}" class="w-20 h-20 flex-shrink-0">
                            <img src="${imageUrl}" class="w-full h-full object-cover rounded">
                        </a>
                        <div class="flex-1">
                            <a href="/product/${product.slug}">
                                <h3 class="font-semibold hover:text-red-600">${product.name}</h3>
                            </a>
                            <p class="text-red-600 font-bold mt-1">LKR ${parseFloat(product.regular_price).toLocaleString()}</p>
                            <div class="flex gap-2 mt-2">
                                <button onclick="addToCartFromWishlist(${product.id})" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                    Add to Cart
                                </button>
                                <button onclick="removeFromWishlist(${product.id})" class="text-red-500 text-sm hover:underline">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            wishlistContent.innerHTML = itemsHtml;
            
        } catch (error) {
            console.error('Error loading wishlist:', error);
            document.getElementById('wishlistContent').innerHTML = '<div class="text-center py-8 text-red-500">Error loading wishlist</div>';
        }
    }

    function openWishlistModal() {
        if (!{{ auth()->check() ? 'true' : 'false' }}) {
            showNotification('Please login to view wishlist', 'error');
            window.location.href = '{{ route("login") }}';
            return;
        }
        document.getElementById('wishlistModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        loadWishlist();
    }

    function closeWishlistModal(event) {
        if (!event || event.target === document.getElementById('wishlistModal')) {
            document.getElementById('wishlistModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    async function addToCartFromWishlist(productId) {
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
                showNotification('Added to cart!');
                // Remove from wishlist
                await removeFromWishlist(productId);
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error adding to cart', 'error');
        }
    }

    async function removeFromWishlist(productId) {
        try {
            const response = await fetch(`/api/wishlist/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            if (data.success) {
                document.getElementById(`wishlist-item-${productId}`)?.remove();
                showNotification('Removed from wishlist');
                
                // Check if wishlist is empty
                const remainingItems = document.querySelectorAll('#wishlistContent > div:not(.text-center)').length;
                if (remainingItems === 0) {
                    loadWishlist(); // Reload to show empty state
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error removing item', 'error');
        }
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
    </script>
</body>
</html>