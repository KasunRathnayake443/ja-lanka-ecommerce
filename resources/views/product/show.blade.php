@extends('layouts.desktop')

@section('title', $product->name)

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-red-600">Home</a> /
        <a href="{{ route('shop') }}" class="hover:text-red-600">Shop</a> /
        <span class="text-gray-800">{{ $product->name }}</span>
    </nav>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Images Gallery -->
        <div>
            <div class="bg-gray-100 rounded-lg overflow-hidden">
                <img id="mainImage" src="{{ asset('storage/' . ($product->images->first()->image_path ?? 'images/placeholder.jpg')) }}" 
                     alt="{{ $product->name }}"
                     class="w-full h-96 object-cover">
            </div>
            
            @if($product->images->count() > 1)
            <div class="flex gap-2 mt-4 overflow-x-auto pb-2">
                @foreach($product->images as $image)
                <div class="w-20 h-20 cursor-pointer border-2 rounded overflow-hidden hover:border-red-600 transition"
                     onclick="document.getElementById('mainImage').src = '{{ asset('storage/' . $image->image_path) }}'">
                    <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover">
                </div>
                @endforeach
            </div>
            @endif
        </div>
        
        <!-- Product Info -->
        <div>
            @if($product->origin)
            <div class="text-sm text-gray-500 mb-2">
                {{ $product->origin->flag_icon ?? '🌏' }} {{ $product->origin->country_name }}
            </div>
            @endif
            
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
            
            @if($product->brand)
            <div class="text-gray-600 mb-4">Brand: {{ $product->brand->name }}</div>
            @endif
            
            <!-- Price -->
            <div class="mb-4">
                @if($product->sale_price && $product->sale_price < $product->regular_price)
                    <span class="text-3xl font-bold text-red-600">LKR {{ number_format($product->sale_price, 2) }}</span>
                    <span class="text-gray-400 line-through ml-3">LKR {{ number_format($product->regular_price, 2) }}</span>
                    <span class="bg-red-100 text-red-600 px-2 py-1 rounded ml-3 text-sm">
                        {{ $product->discount_percent }}% OFF
                    </span>
                @else
                    <span class="text-3xl font-bold text-gray-800">LKR {{ number_format($product->regular_price, 2) }}</span>
                @endif
            </div>
            
            <!-- Stock Status -->
            <div class="mb-4">
                @if($product->stock > 0)
                    <span class="text-green-600 text-sm">✓ In Stock ({{ $product->stock }} available)</span>
                @else
                    <span class="text-red-600 text-sm">✗ Out of Stock</span>
                @endif
            </div>
            
            <!-- Short Description -->
            @if($product->short_description)
            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                <p class="text-gray-600">{{ $product->short_description }}</p>
            </div>
            @endif
            
            <!-- Quantity Selector -->
            <div class="flex items-center gap-4 mb-6">
                <span class="text-gray-700">Quantity:</span>
                <div class="flex border rounded">
                    <button onclick="decrementQuantity()" class="px-3 py-2 hover:bg-gray-100">-</button>
                    <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-16 text-center border-x">
                    <button onclick="incrementQuantity()" class="px-3 py-2 hover:bg-gray-100">+</button>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-4 mb-6">
                <button onclick="addToCart()" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                    Add to Cart
                </button>
                <button onclick="toggleWishlist()" id="wishlistBtn" class="px-6 py-3 border rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 {{ $inWishlist ? 'fill-red-600 text-red-600' : 'fill-none text-gray-600' }}" 
                         fill="{{ $inWishlist ? '#dc2626' : 'none' }}" 
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Product Details Tabs -->
    <div class="mt-12">
        <div class="border-b">
            <div class="flex gap-6">
                <button onclick="showTab('description')" id="tabDescriptionBtn" class="py-3 px-1 font-semibold text-red-600 border-b-2 border-red-600">
                    Description
                </button>
                <button onclick="showTab('specifications')" id="tabSpecsBtn" class="py-3 px-1 font-semibold text-gray-500 hover:text-gray-700">
                    Specifications
                </button>
            </div>
        </div>
        
        <div id="tabDescription" class="py-6">
            <div class="prose max-w-none">
                {!! nl2br(e($product->description ?? 'No description available.')) !!}
            </div>
        </div>
        
        <div id="tabSpecifications" class="py-6 hidden">
            @if($product->attributes->count() > 0)
            <div class="bg-gray-50 rounded-lg overflow-hidden">
                <table class="w-full">
                    <tbody>
                        @foreach($product->attributes as $attr)
                        <tr class="border-b">
                            <td class="px-6 py-3 font-medium text-gray-700 w-1/3">{{ $attr->key }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $attr->value }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-500">No specifications available.</p>
            @endif
        </div>
    </div>
    
    <!-- Related Products -->
    @if($related->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">You May Also Like</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($related as $item)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                <a href="{{ route('product.show', $item->slug) }}">
                    <div class="h-40 overflow-hidden rounded-t-lg">
                        <img src="{{ asset('storage/' . ($item->images->first()->image_path ?? 'images/placeholder.jpg')) }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="p-3">
                        <h3 class="font-semibold text-sm">{{ Str::limit($item->name, 40) }}</h3>
                        <p class="text-red-600 font-bold mt-1">LKR {{ number_format($item->regular_price, 2) }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
let currentWishlistStatus = {{ $inWishlist ? 'true' : 'false' }};

function incrementQuantity() {
    let input = document.getElementById('quantity');
    let max = parseInt(input.getAttribute('max')) || 99;
    if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
    }
}

function decrementQuantity() {
    let input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

async function addToCart() {
    let quantity = document.getElementById('quantity').value;
    
    try {
        const response = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ 
                product_id: {{ $product->id }}, 
                quantity: quantity 
            })
        });
        
        const data = await response.json();
        if (data.success) {
            updateCartCount(data.cart_count);
            showNotification('Added to cart!');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error adding to cart', 'error');
    }
}

async function toggleWishlist() {
    if (!{{ auth()->check() ? 'true' : 'false' }}) {
        showNotification('Please login to add to wishlist', 'error');
        window.location.href = '{{ route("login") }}';
        return;
    }
    
    try {
        const response = await fetch('/api/wishlist/{{ $product->id }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        if (data.success) {
            currentWishlistStatus = !currentWishlistStatus;
            const wishlistBtn = document.getElementById('wishlistBtn').querySelector('svg');
            if (currentWishlistStatus) {
                wishlistBtn.classList.add('fill-red-600', 'text-red-600');
                wishlistBtn.classList.remove('fill-none');
                showNotification('Added to wishlist');
            } else {
                wishlistBtn.classList.remove('fill-red-600', 'text-red-600');
                wishlistBtn.classList.add('fill-none');
                showNotification('Removed from wishlist');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error', 'error');
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
    notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} z-50 transition-opacity`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function showTab(tab) {
    // Hide both tabs
    document.getElementById('tabDescription').classList.add('hidden');
    document.getElementById('tabSpecifications').classList.add('hidden');
    
    // Show selected tab
    document.getElementById(`tab${tab.charAt(0).toUpperCase() + tab.slice(1)}`).classList.remove('hidden');
    
    // Update button styles
    const descBtn = document.getElementById('tabDescriptionBtn');
    const specsBtn = document.getElementById('tabSpecsBtn');
    
    if (tab === 'description') {
        descBtn.classList.add('text-red-600', 'border-red-600');
        descBtn.classList.remove('text-gray-500');
        specsBtn.classList.remove('text-red-600', 'border-red-600');
        specsBtn.classList.add('text-gray-500');
    } else {
        specsBtn.classList.add('text-red-600', 'border-red-600');
        specsBtn.classList.remove('text-gray-500');
        descBtn.classList.remove('text-red-600', 'border-red-600');
        descBtn.classList.add('text-gray-500');
    }
}
</script>
@endsection