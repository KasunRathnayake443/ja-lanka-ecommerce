<?php $__env->startSection('title', $product->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 mb-6">
        <a href="<?php echo e(route('home')); ?>" class="hover:text-red-600">Home</a> /
        <a href="<?php echo e(route('shop')); ?>" class="hover:text-red-600">Shop</a> /
        <span class="text-gray-800"><?php echo e($product->name); ?></span>
    </nav>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Images Gallery -->
        <div>
            <div class="bg-gray-100 rounded-lg overflow-hidden">
                <img id="mainImage" src="<?php echo e(asset('storage/' . ($product->images->first()->image_path ?? 'images/placeholder.jpg'))); ?>" 
                     alt="<?php echo e($product->name); ?>"
                     class="w-full h-96 object-cover">
            </div>
            
            <?php if($product->images->count() > 1): ?>
            <div class="flex gap-2 mt-4 overflow-x-auto pb-2">
                <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="w-20 h-20 cursor-pointer border-2 rounded overflow-hidden hover:border-red-600 transition"
                     onclick="document.getElementById('mainImage').src = '<?php echo e(asset('storage/' . $image->image_path)); ?>'">
                    <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" class="w-full h-full object-cover">
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Product Info -->
        <div>
            <?php if($product->origin): ?>
            <div class="text-sm text-gray-500 mb-2">
                <?php echo e($product->origin->flag_icon ?? '🌏'); ?> <?php echo e($product->origin->country_name); ?>

            </div>
            <?php endif; ?>
            
            <h1 class="text-3xl font-bold text-gray-800 mb-2"><?php echo e($product->name); ?></h1>
            
            <?php if($product->brand): ?>
            <div class="text-gray-600 mb-4">Brand: <?php echo e($product->brand->name); ?></div>
            <?php endif; ?>
            
            <!-- Price -->
            <div class="mb-4">
                <?php if($product->sale_price && $product->sale_price < $product->regular_price): ?>
                    <span class="text-3xl font-bold text-red-600">LKR <?php echo e(number_format($product->sale_price, 2)); ?></span>
                    <span class="text-gray-400 line-through ml-3">LKR <?php echo e(number_format($product->regular_price, 2)); ?></span>
                    <span class="bg-red-100 text-red-600 px-2 py-1 rounded ml-3 text-sm">
                        <?php echo e($product->discount_percent); ?>% OFF
                    </span>
                <?php else: ?>
                    <span class="text-3xl font-bold text-gray-800">LKR <?php echo e(number_format($product->regular_price, 2)); ?></span>
                <?php endif; ?>
            </div>
            
            <!-- Stock Status -->
            <div class="mb-4">
                <?php if($product->stock > 0): ?>
                    <span class="text-green-600 text-sm">✓ In Stock (<?php echo e($product->stock); ?> available)</span>
                <?php else: ?>
                    <span class="text-red-600 text-sm">✗ Out of Stock</span>
                <?php endif; ?>
            </div>
            
            <!-- Short Description -->
            <?php if($product->short_description): ?>
            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                <p class="text-gray-600"><?php echo e($product->short_description); ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Quantity Selector -->
            <div class="flex items-center gap-4 mb-6">
                <span class="text-gray-700">Quantity:</span>
                <div class="flex border rounded">
                    <button onclick="decrementQuantity()" class="px-3 py-2 hover:bg-gray-100">-</button>
                    <input type="number" id="quantity" value="1" min="1" max="<?php echo e($product->stock); ?>" class="w-16 text-center border-x">
                    <button onclick="incrementQuantity()" class="px-3 py-2 hover:bg-gray-100">+</button>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-4 mb-6">
                <button onclick="addToCart()" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                    Add to Cart
                </button>
                <button onclick="toggleWishlist()" id="wishlistBtn" class="px-6 py-3 border rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 <?php echo e($inWishlist ? 'fill-red-600 text-red-600' : 'fill-none text-gray-600'); ?>" 
                         fill="<?php echo e($inWishlist ? '#dc2626' : 'none'); ?>" 
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
                <?php echo nl2br(e($product->description ?? 'No description available.')); ?>

            </div>
        </div>
        
        <div id="tabSpecifications" class="py-6 hidden">
            <?php if($product->attributes->count() > 0): ?>
            <div class="bg-gray-50 rounded-lg overflow-hidden">
                <table class="w-full">
                    <tbody>
                        <?php $__currentLoopData = $product->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b">
                            <td class="px-6 py-3 font-medium text-gray-700 w-1/3"><?php echo e($attr->key); ?></td>
                            <td class="px-6 py-3 text-gray-600"><?php echo e($attr->value); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-gray-500">No specifications available.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if($related->count() > 0): ?>
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">You May Also Like</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                <a href="<?php echo e(route('product.show', $item->slug)); ?>">
                    <div class="h-40 overflow-hidden rounded-t-lg">
                        <img src="<?php echo e(asset('storage/' . ($item->images->first()->image_path ?? 'images/placeholder.jpg'))); ?>" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="p-3">
                        <h3 class="font-semibold text-sm"><?php echo e(Str::limit($item->name, 40)); ?></h3>
                        <p class="text-red-600 font-bold mt-1">LKR <?php echo e(number_format($item->regular_price, 2)); ?></p>
                    </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
let currentWishlistStatus = <?php echo e($inWishlist ? 'true' : 'false'); ?>;

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
                product_id: <?php echo e($product->id); ?>, 
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
    if (!<?php echo e(auth()->check() ? 'true' : 'false'); ?>) {
        showNotification('Please login to add to wishlist', 'error');
        window.location.href = '<?php echo e(route("login")); ?>';
        return;
    }
    
    try {
        const response = await fetch('/api/wishlist/<?php echo e($product->id); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        console.log('Wishlist response:', data);
        
        if (data.success) {
            currentWishlistStatus = data.in_wishlist;
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.desktop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Kasun Rathnayake\Herd\ja-lanka-ecommerce\resources\views/product/show.blade.php ENDPATH**/ ?>