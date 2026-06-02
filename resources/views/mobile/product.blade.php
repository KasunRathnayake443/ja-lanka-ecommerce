@extends('layouts.mobile')

@section('title', $product->name)

@section('content')
<div class="pb-20">
    <!-- Product Images -->
    <div class="relative">
        <div class="bg-white">
            <img id="mobileMainImage" src="{{ asset('storage/' . ($product->images->first()->image_path ?? 'images/placeholder.jpg')) }}" 
                 class="w-full h-80 object-cover">
        </div>
        
        @if($product->images->count() > 1)
        <div class="absolute bottom-4 left-0 right-0">
            <div class="flex justify-center gap-2">
                @foreach($product->images as $index => $image)
                <div class="w-12 h-12 cursor-pointer border-2 rounded overflow-hidden {{ $index == 0 ? 'border-red-600' : 'border-white' }}"
                     onclick="document.getElementById('mobileMainImage').src = '{{ asset('storage/' . $image->image_path) }}';
                              document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('border-red-600'));
                              this.classList.add('border-red-600')">
                    <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover">
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    
    <div class="p-4">
        <!-- Origin & Brand -->
        <div class="flex justify-between items-start mb-2">
            @if($product->origin)
            <div class="text-sm text-gray-500">
                {{ $product->origin->flag_icon ?? '🌏' }} {{ $product->origin->country_name }}
            </div>
            @endif
            @if($product->brand)
            <div class="text-sm text-gray-500">Brand: {{ $product->brand->name }}</div>
            @endif
        </div>
        
        <!-- Product Name -->
        <h1 class="text-xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
        
       <!-- Price -->
        <div class="mb-3">
            @if($product->sale_price && $product->sale_price < $product->regular_price)
                <span class="text-2xl font-bold text-red-600">LKR {{ number_format($product->sale_price, 2) }}</span>
                <span class="text-gray-400 line-through ml-2">LKR {{ number_format($product->regular_price, 2) }}</span>
                <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded ml-2 text-xs">
                    -{{ $product->discount_percent }}%
                </span>
            @else
                <span class="text-2xl font-bold text-gray-800">LKR {{ number_format($product->regular_price, 2) }}</span>
            @endif
        </div>
        
        <!-- Stock -->
        <div class="mb-4">
            @if($product->stock > 0)
                <span class="text-green-600 text-sm">✓ In Stock ({{ $product->stock }} available)</span>
            @else
                <span class="text-red-600 text-sm">✗ Out of Stock</span>
            @endif
        </div>
        
        <!-- Quantity Selector -->
        <div class="flex items-center justify-between mb-6">
            <span class="text-gray-700">Quantity:</span>
            <div class="flex border rounded">
                <button onclick="decrementQuantity()" class="px-4 py-2 hover:bg-gray-100">-</button>
                <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-16 text-center border-x">
                <button onclick="incrementQuantity()" class="px-4 py-2 hover:bg-gray-100">+</button>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex gap-3 mb-6">
            <button onclick="addToCart()" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700">
                Add to Cart
            </button>
            <button onclick="toggleWishlist()" class="px-5 py-3 border rounded-lg">
                <svg id="wishlistIcon" class="w-5 h-5 {{ $inWishlist ? 'fill-red-600 text-red-600' : 'fill-none text-gray-600' }}" 
                     fill="{{ $inWishlist ? '#dc2626' : 'none' }}" 
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        </div>
        
        <!-- Description -->
        <div class="border-t pt-4 mb-4">
            <h3 class="font-semibold mb-2">Description</h3>
            <p class="text-gray-600 text-sm">{{ $product->short_description ?? $product->description }}</p>
        </div>
        
        <!-- Specifications -->
        @if($product->attributes->count() > 0)
        <div class="border-t pt-4">
            <h3 class="font-semibold mb-2">Specifications</h3>
            <div class="bg-gray-50 rounded-lg p-3 space-y-1">
                @foreach($product->attributes as $attr)
                <div class="flex text-sm">
                    <span class="w-1/2 text-gray-500">{{ $attr->key }}:</span>
                    <span class="w-1/2 text-gray-700">{{ $attr->value }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
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
            showToast('Added to cart!');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error adding to cart', 'error');
    }
}

async function toggleWishlist() {
    if (!{{ auth()->check() ? 'true' : 'false' }}) {
        showToast('Please login to add to wishlist', 'error');
        setTimeout(() => {
            window.location.href = '{{ route("login") }}';
        }, 1500);
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
            const wishlistIcon = document.getElementById('wishlistIcon');
            if (currentWishlistStatus) {
                wishlistIcon.classList.add('fill-red-600', 'text-red-600');
                wishlistIcon.classList.remove('fill-none');
                showToast('Added to wishlist');
            } else {
                wishlistIcon.classList.remove('fill-red-600', 'text-red-600');
                wishlistIcon.classList.add('fill-none');
                showToast('Removed from wishlist');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error', 'error');
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
</script>
@endsection