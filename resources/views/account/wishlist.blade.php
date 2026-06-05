@extends('layouts.desktop')

@section('title', 'My Wishlist - Ja Lanka')

@section('content')
<div class="max-w-[1400px] mx-auto px-5 md:px-10 py-10">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Sidebar -->
        <aside class="lg:w-80">
            @include('account.partials.sidebar')
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1">
            <div class="bg-white border border-gray-100 rounded-lg p-6">
                <h2 class="text-2xl font-light font-['Cormorant_Garamond'] mb-6">My Wishlist</h2>
                
                @if($wishlist->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach($wishlist as $item)
                        <div class="border border-gray-100 rounded-lg p-4 flex gap-4">
                            <a href="/product/{{ $item->product->slug }}" class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                                <img src="{{ $item->product->images && $item->product->images->first() ? asset('storage/' . $item->product->images->first()->image_path) : '/images/placeholder.jpg' }}" 
                                     class="w-full h-full object-cover">
                            </a>
                            <div class="flex-1">
                                <a href="/product/{{ $item->product->slug }}">
                                    <h3 class="font-semibold text-gray-900 hover:text-red-700">{{ $item->product->name }}</h3>
                                </a>
                                <p class="text-red-700 font-bold mt-1">LKR {{ number_format($item->product->regular_price, 2) }}</p>
                                <div class="flex gap-2 mt-3">
                                    <button onclick="addToCart({{ $item->product->id }})" class="flex-1 bg-gray-900 hover:bg-red-700 text-white text-xs font-semibold py-1.5 rounded transition">Add to Cart</button>
                                    <button onclick="removeFromWishlist({{ $item->product->id }}, this)" class="px-3 bg-gray-100 hover:bg-red-100 text-gray-600 hover:text-red-600 rounded transition">Remove</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <p class="text-gray-500">Your wishlist is empty</p>
                        <a href="{{ route('shop') }}" class="mt-3 inline-block text-red-700 hover:underline">Continue Shopping →</a>
                    </div>
                @endif
            </div>
        </div>
        
    </div>
</div>

<script>
async function addToCart(productId) {
    try {
        const data = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        }).then(r => r.json());
        
        if (data.success) {
            updateCartCount(data.cart_count);
            showNotification('Added to cart!', 'success');
        }
    } catch(e) {
        showNotification('Error adding to cart', 'error');
    }
}

async function removeFromWishlist(productId, element) {
    try {
        const data = await fetch(`/api/wishlist/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(r => r.json());
        
        if (data.success) {
            element.closest('.border').remove();
            showNotification('Removed from wishlist', 'success');
        }
    } catch(e) {
        showNotification('Error removing item', 'error');
    }
}

function updateCartCount(count) {
    const el = document.getElementById('cartCount');
    if (el) {
        count > 0 ? (el.textContent = count, el.classList.remove('hidden')) : el.classList.add('hidden');
    }
}

function showNotification(msg, type = 'success') {
    const n = document.createElement('div');
    n.className = `fixed bottom-6 right-6 px-5 py-3 rounded text-sm font-semibold text-white z-[9999] transition-opacity shadow-lg ${type === 'success' ? 'bg-emerald-700' : 'bg-red-700'}`;
    n.textContent = msg;
    document.body.appendChild(n);
    setTimeout(() => { n.style.opacity = '0'; setTimeout(() => n.remove(), 300); }, 2800);
}
</script>
@endsection