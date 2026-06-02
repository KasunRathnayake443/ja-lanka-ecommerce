@extends('layouts.app')

@section('title', $product->name . ' - Ja Lanka')

@section('content')
<div class="container mx-auto px-4 py-8">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('food.index') }}" class="hover:text-red-600">Food</a> / 
        <span class="text-gray-800">{{ $product->name }}</span>
    </nav>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div>
            <div class="bg-gray-100 rounded-lg overflow-hidden">
                @if($product->images->first())
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                        alt="{{ $product->name }}"
                        class="w-full h-96 object-cover" id="mainImage">
                @else
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">No image available</span>
                    </div>
                @endif
            </div>
            
            @if($product->images->count() > 1)
            <div class="flex gap-2 mt-4">
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
            <div class="text-sm text-gray-500 mb-2">
                {{ $product->origin->flag_icon ?? '🌏' }} {{ $product->origin->country_name ?? 'International' }}
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
            
            @if($product->brand)
            <div class="text-gray-600 mb-4">Brand: {{ $product->brand->name }}</div>
            @endif
            
            <div class="flex items-center mb-4">
                @if($product->sale_price)
                    <span class="text-3xl font-bold text-red-600">LKR {{ number_format($product->sale_price, 2) }}</span>
                    <span class="text-gray-400 line-through ml-3">LKR {{ number_format($product->regular_price, 2) }}</span>
                    <span class="bg-red-100 text-red-600 px-2 py-1 rounded ml-3 text-sm">
                        {{ round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100) }}% OFF
                    </span>
                @else
                    <span class="text-3xl font-bold text-gray-800">LKR {{ number_format($product->regular_price, 2) }}</span>
                @endif
            </div>
            
            <div class="border-t pt-4 mb-4">
                <h3 class="font-semibold mb-2">Description</h3>
                <p class="text-gray-600">{{ $product->short_description ?? $product->description }}</p>
            </div>
            
            <!-- Attributes -->
            @if($product->attributes->count() > 0)
            <div class="border-t pt-4 mb-4">
                <h3 class="font-semibold mb-2">Product Details</h3>
                <div class="space-y-1">
                    @foreach($product->attributes as $attr)
                    <div class="flex">
                        <span class="w-1/3 text-gray-500">{{ $attr->key }}:</span>
                        <span class="text-gray-700">{{ $attr->value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Stock & Availability -->
            <div class="border-t pt-4 mb-6">
                @php $stock = $product->inventory->quantity_on_hand ?? 0; @endphp
                @if($stock > 0)
                    <div class="text-green-600 mb-2">✓ In Stock ({{ $stock }} available)</div>
                @else
                    <div class="text-red-600 mb-2">✗ Out of Stock</div>
                @endif
            </div>
            
            <!-- Add to Cart -->
            <div class="flex gap-4">
                <div class="flex border rounded">
                    <button class="px-3 py-2 hover:bg-gray-100" onclick="decrementQuantity()">-</button>
                    <input type="number" id="quantity" value="1" min="1" max="{{ $stock }}" class="w-16 text-center border-x">
                    <button class="px-3 py-2 hover:bg-gray-100" onclick="incrementQuantity()">+</button>
                </div>
                <button onclick="addToCart()" class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition">
                    Add to Cart
                </button>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">You May Also Like</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                <a href="{{ route('food.show', $related->slug) }}">
                    <div class="h-40 overflow-hidden rounded-t-lg">
                        @if($related->images->first())
                            <img src="{{ asset('storage/' . $related->images->first()->image_path) }}" 
                                alt="{{ $related->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No image</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-3">
                        <h3 class="font-semibold text-sm">{{ Str::limit($related->name, 40) }}</h3>
                        <p class="text-red-600 font-bold mt-1">LKR {{ number_format($related->regular_price, 2) }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
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

function addToCart() {
    let quantity = document.getElementById('quantity').value;
    alert('Added ' + quantity + ' item(s) to cart! (Cart functionality coming soon)');
}
</script>
@endsection