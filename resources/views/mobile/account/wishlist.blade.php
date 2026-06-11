@extends('layouts.mobile')

@section('title', 'My Wishlist')

@section('content')
<div class="pb-20">
    
    <!-- Header -->
    <div class="bg-white border-b border-gray-100 px-5 py-4 sticky top-0 z-10">
        <div class="flex items-center gap-3">
            <a href="{{ route('mobile.account.dashboard') }}" class="text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800">My Wishlist</h1>
        </div>
    </div>
    
    <!-- Wishlist Items -->
    <div class="p-4 space-y-3">
        @forelse($wishlist as $item)
        <div class="bg-white rounded-xl p-3 flex gap-3 shadow-sm border border-gray-100" id="wishlist-item-{{ $item->product->id }}">
            <a href="{{ route('mobile.product', $item->product->slug) }}" class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                @if($item->product->images && $item->product->images->first())
                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">📷</div>
                @endif
            </a>
            <div class="flex-1">
                <a href="{{ route('mobile.product', $item->product->slug) }}">
                    <h3 class="font-semibold text-gray-800 text-sm">{{ $item->product->name }}</h3>
                </a>
                <div class="flex items-center justify-between mt-2">
                    <div>
                        @if($item->product->sale_price && $item->product->sale_price < $item->product->regular_price)
                            <p class="text-red-600 font-bold">LKR {{ number_format($item->product->sale_price, 2) }}</p>
                            <p class="text-gray-400 text-xs line-through">LKR {{ number_format($item->product->regular_price, 2) }}</p>
                        @else
                            <p class="text-red-600 font-bold">LKR {{ number_format($item->product->regular_price, 2) }}</p>
                        @endif
                    </div>
                    <button onclick="addToCartAndRemove({{ $item->product->id }})" class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <p class="text-gray-500">Your wishlist is empty</p>
            <a href="{{ route('mobile.shop') }}" class="inline-block mt-3 text-red-600">Start Shopping →</a>
        </div>
        @endforelse
    </div>
    
</div>

<script>
async function addToCartAndRemove(productId) {
    try {
        // Add to cart
        const cartResponse = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        });
        
        const cartData = await cartResponse.json();
        if (cartData.success) {
            updateCartCount(cartData.cart_count);
            showToast('Added to cart!');
            
            // Remove from wishlist
            await removeFromWishlist(productId);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error adding to cart', 'error');
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
            const item = document.getElementById(`wishlist-item-${productId}`);
            if (item) item.remove();
            showToast('Removed from wishlist');
        }
    } catch (error) {
        console.error('Error:', error);
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
    toast.className = `fixed bottom-20 left-4 right-4 text-center px-4 py-2 rounded-lg text-white z-50 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}
</script>
@endsection