@extends('layouts.app')

@section('title', 'Japanese & International Food - Ja Lanka')

@section('content')
<!-- Hero Banner -->
<div class="relative h-[50vh] bg-cover bg-center" style="background-image: url('{{ asset('images/food-banner.jpg') }}');">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    <div class="relative container mx-auto px-4 h-full flex flex-col justify-center items-center text-center text-white">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">Global Food Collection</h1>
        <p class="text-lg md:text-xl mb-6">Authentic flavors from Japan, Korea, Italy & more</p>
        <div class="flex gap-4">
            <span class="bg-red-600 px-4 py-2 rounded-full text-sm">🍜 Noodles</span>
            <span class="bg-red-600 px-4 py-2 rounded-full text-sm">🍱 Snacks</span>
            <span class="bg-red-600 px-4 py-2 rounded-full text-sm">🍵 Tea</span>
            <span class="bg-red-600 px-4 py-2 rounded-full text-sm">🥫 Sauces</span>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                <h3 class="text-lg font-semibold mb-4 pb-2 border-b">Filters</h3>
                
                <form method="GET" action="{{ route('food.index') }}" id="filterForm">
                    <!-- Categories Filter -->
                    <div class="mb-6">
                        <h4 class="font-medium mb-2">Categories</h4>
                        @foreach($categories as $category)
                        <label class="flex items-center mb-2">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}
                                class="mr-2" onchange="this.form.submit()">
                            <span class="text-sm">{{ $category->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    
                    <!-- Origins Filter -->
                    <div class="mb-6">
                        <h4 class="font-medium mb-2">Country of Origin</h4>
                        @foreach($origins as $origin)
                        <label class="flex items-center mb-2">
                            <input type="checkbox" name="origins[]" value="{{ $origin->id }}" 
                                {{ in_array($origin->id, $selectedOrigins) ? 'checked' : '' }}
                                class="mr-2" onchange="this.form.submit()">
                            <span class="text-sm">{{ $origin->flag_icon }} {{ $origin->country_name }}</span>
                        </label>
                        @endforeach
                    </div>
                    
                    <!-- Price Range Filter -->
                    <div class="mb-6">
                        <h4 class="font-medium mb-2">Price Range</h4>
                        <div class="flex gap-2">
                            <input type="number" name="min_price" value="{{ $minPrice }}" 
                                placeholder="Min" class="w-1/2 px-3 py-1 border rounded text-sm"
                                onchange="this.form.submit()">
                            <input type="number" name="max_price" value="{{ $maxPrice }}" 
                                placeholder="Max" class="w-1/2 px-3 py-1 border rounded text-sm"
                                onchange="this.form.submit()">
                        </div>
                    </div>
                    
                    <!-- Sort By -->
                    <div class="mb-6">
                        <h4 class="font-medium mb-2">Sort By</h4>
                        <select name="sort" class="w-full px-3 py-2 border rounded" onchange="this.form.submit()">
                            <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_low" {{ $sort == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ $sort == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>
                    
                    <!-- Clear Filters -->
                    <button type="button" onclick="clearFilters()" class="w-full bg-gray-200 text-gray-700 py-2 rounded hover:bg-gray-300">
                        Clear All Filters
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="lg:w-3/4">
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} products</p>
            </div>
            
            @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($products as $product)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition group">
                    <a href="{{ route('food.show', $product->slug) }}">
                        <div class="relative overflow-hidden rounded-t-lg h-48">
                            @if($product->images->first())
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No image</span>
                                </div>
                            @endif
                            
                            @if($product->sale_price)
                            <div class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded">
                                Sale!
                            </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <div class="text-xs text-gray-500 mb-1">
                                {{ $product->origin->flag_icon ?? '🌏' }} {{ $product->origin->country_name ?? 'International' }}
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1 line-clamp-2">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between mt-2">
                                <div>
                                    @if($product->sale_price)
                                        <span class="text-red-600 font-bold">LKR {{ number_format($product->sale_price, 2) }}</span>
                                        <span class="text-gray-400 text-sm line-through ml-2">LKR {{ number_format($product->regular_price, 2) }}</span>
                                    @else
                                        <span class="text-gray-800 font-bold">LKR {{ number_format($product->regular_price, 2) }}</span>
                                    @endif
                                </div>
                                <button onclick="addToCart({{ $product->id }})" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $products->withQueryString()->links() }}
            </div>
            @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <p class="text-gray-500">No products found. Try adjusting your filters.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function clearFilters() {
    // Clear all checkboxes
    document.querySelectorAll('#filterForm input[type="checkbox"]').forEach(cb => cb.checked = false);
    // Reset price inputs
    document.querySelectorAll('#filterForm input[type="number"]').forEach(input => input.value = '');
    // Reset select
    document.querySelector('#filterForm select').value = 'newest';
    // Submit form
    document.getElementById('filterForm').submit();
}

function addToCart(productId) {
    // Temporary alert - will implement cart later
    alert('Added to cart! (Cart functionality coming soon)');
}
</script>
@endsection