<?php $__env->startSection('title', $product->name); ?>

<?php $__env->startSection('content'); ?>
<div class="pb-24 bg-gray-50">
    
    <!-- Product Images Gallery -->
    <div class="relative bg-white">
        <div class="relative overflow-hidden">
            <img id="mobileMainImage" src="<?php echo e(asset('storage/' . ($product->images->first()->image_path ?? 'images/placeholder.jpg'))); ?>" 
                 alt="<?php echo e($product->name); ?>"
                 class="w-full h-96 object-cover">
            
            <!-- Sale Badge on Image -->
            <?php if($product->sale_price && $product->sale_price < $product->regular_price): ?>
            <div class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-full">
                <?php echo e($product->discount_percent); ?>% OFF
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Thumbnail Gallery -->
        <?php if($product->images->count() > 1): ?>
        <div class="flex gap-2 p-3 overflow-x-auto bg-white border-t border-gray-100">
            <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="w-16 h-16 flex-shrink-0 cursor-pointer border-2 rounded-lg overflow-hidden transition-all <?php echo e($index == 0 ? 'border-red-600' : 'border-gray-200 hover:border-red-400'); ?>"
                 onclick="document.getElementById('mobileMainImage').src = '<?php echo e(asset('storage/' . $image->image_path)); ?>';
                          document.querySelectorAll('.thumbnail-m').forEach(t => t.classList.remove('border-red-600'));
                          this.classList.add('border-red-600')">
                <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" class="w-full h-full object-cover">
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="px-4 py-5">
        <!-- Origin & Brand -->
        <div class="flex justify-between items-start mb-2">
            <?php if($product->origin): ?>
            <div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                <?php echo e($product->origin->flag_icon ?? '🌏'); ?> <?php echo e($product->origin->country_name); ?>

            </div>
            <?php endif; ?>
            <?php if($product->brand): ?>
            <div class="text-xs text-gray-500">Brand: <?php echo e($product->brand->name); ?></div>
            <?php endif; ?>
        </div>
        
        <!-- Product Name -->
        <h1 class="text-2xl font-bold text-gray-900 mt-2 mb-3 font-['Playfair_Display']"><?php echo e($product->name); ?></h1>
        
        <!-- Price -->
        <div class="mb-4">
            <?php if($product->sale_price && $product->sale_price < $product->regular_price): ?>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-3xl font-bold text-red-600">LKR <?php echo e(number_format($product->sale_price, 2)); ?></span>
                    <span class="text-gray-400 line-through">LKR <?php echo e(number_format($product->regular_price, 2)); ?></span>
                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">
                        Save <?php echo e($product->discount_percent); ?>%
                    </span>
                </div>
            <?php else: ?>
                <span class="text-3xl font-bold text-gray-900">LKR <?php echo e(number_format($product->regular_price, 2)); ?></span>
            <?php endif; ?>
        </div>
        
        <!-- Stock Status -->
        <div class="mb-5">
            <?php if($product->stock > 0): ?>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    <span class="text-green-600 text-sm font-medium">In Stock (<?php echo e($product->stock); ?> available)</span>
                </div>
            <?php else: ?>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    <span class="text-red-600 text-sm font-medium">Out of Stock</span>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Short Description -->
        <?php if($product->short_description): ?>
        <div class="mb-5 p-4 bg-gray-100 rounded-xl">
            <p class="text-gray-600 text-sm leading-relaxed"><?php echo e($product->short_description); ?></p>
        </div>
        <?php endif; ?>
        
        <!-- Quantity Selector -->
        <div class="flex items-center justify-between mb-6">
            <span class="text-gray-700 font-medium">Quantity:</span>
            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                <button onclick="decrementQuantity()" class="w-10 h-10 flex items-center justify-center bg-gray-50 hover:bg-gray-100 transition-colors text-lg font-medium">-</button>
                <input type="number" id="quantity" value="1" min="1" max="<?php echo e($product->stock); ?>" class="w-14 h-10 text-center border-x border-gray-200 focus:outline-none">
                <button onclick="incrementQuantity()" class="w-10 h-10 flex items-center justify-center bg-gray-50 hover:bg-gray-100 transition-colors text-lg font-medium">+</button>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex gap-3 mb-8">
            <button onclick="addToCart()" class="flex-1 bg-red-600 text-white py-3.5 rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow-md active:scale-98">
                Add to Cart
            </button>
            <button onclick="toggleWishlist()" class="w-14 h-14 flex items-center justify-center border border-gray-300 rounded-xl hover:border-red-400 transition-all duration-200 bg-white">
                <svg id="wishlistIcon" class="w-6 h-6 <?php echo e($inWishlist ? 'fill-red-600 text-red-600' : 'fill-none text-gray-600'); ?>" 
                     fill="<?php echo e($inWishlist ? '#dc2626' : 'none'); ?>" 
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        </div>
        
        <!-- Tabs (Description & Specifications) -->
        <div class="mt-4">
            <div class="flex border-b border-gray-200">
                <button onclick="showTab('description')" id="tabDescriptionBtn" class="flex-1 py-3 text-center font-semibold text-red-600 border-b-2 border-red-600 transition-all">
                    Description
                </button>
                <button onclick="showTab('specifications')" id="tabSpecsBtn" class="flex-1 py-3 text-center font-semibold text-gray-500 hover:text-gray-700 transition-all">
                    Specifications
                </button>
            </div>
            
            <div id="tabDescription" class="py-5">
                <div class="text-gray-600 text-sm leading-relaxed space-y-2">
                    <?php echo nl2br(e($product->description ?? 'No description available.')); ?>

                </div>
            </div>
            
            <div id="tabSpecifications" class="py-5 hidden">
                <?php if($product->attributes->count() > 0): ?>
                    <div class="bg-gray-50 rounded-xl overflow-hidden">
                        <div class="divide-y divide-gray-200">
                            <?php $__currentLoopData = $product->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex py-3 px-4">
                                <div class="w-1/2 text-gray-600 text-sm font-medium"><?php echo e($attr->key); ?></div>
                                <div class="w-1/2 text-gray-800 text-sm"><?php echo e($attr->value); ?></div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-sm text-center py-8">No specifications available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if($related->count() > 0): ?>
    <div class="mt-4 px-4 pb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900 font-['Playfair_Display']">You May Also Like</h2>
            <a href="<?php echo e(route('mobile.shop')); ?>" class="text-xs text-red-600 font-medium">View All →</a>
        </div>
        
        <div class="overflow-x-auto pb-2 -mx-1 px-1">
            <div class="flex gap-3" style="min-width: max-content;">
                <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('mobile.product', $item->slug)); ?>" class="w-40 flex-shrink-0 bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="h-32 overflow-hidden">
                        <img src="<?php echo e(asset('storage/' . ($item->images->first()->image_path ?? 'images/placeholder.jpg'))); ?>" 
                             class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                    </div>
                    <div class="p-3">
                        <h3 class="font-semibold text-xs line-clamp-2"><?php echo e(Str::limit($item->name, 35)); ?></h3>
                        <p class="text-red-600 font-bold text-sm mt-1">LKR <?php echo e(number_format($item->regular_price, 2)); ?></p>
                        <?php if($item->discount_percent > 0): ?>
                            <span class="text-green-600 text-xs">-<?php echo e($item->discount_percent); ?>%</span>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
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
            showToast('Added to cart!');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error adding to cart', 'error');
    }
}

async function toggleWishlist() {
    if (!<?php echo e(auth()->check() ? 'true' : 'false'); ?>) {
        showToast('Please login to add to wishlist', 'error');
        setTimeout(() => {
            window.location.href = '<?php echo e(route("login")); ?>';
        }, 1500);
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
        
        if (data.success) {
            currentWishlistStatus = data.in_wishlist;
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
    toast.className = `fixed bottom-24 left-4 right-4 text-center px-4 py-3 rounded-xl text-white z-50 transition-all duration-300 ${type === 'success' ? 'bg-emerald-600' : 'bg-red-600'}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}

function showTab(tab) {
    const descTab = document.getElementById('tabDescription');
    const specsTab = document.getElementById('tabSpecifications');
    const descBtn = document.getElementById('tabDescriptionBtn');
    const specsBtn = document.getElementById('tabSpecsBtn');
    
    if (tab === 'description') {
        descTab.classList.remove('hidden');
        specsTab.classList.add('hidden');
        descBtn.classList.add('text-red-600', 'border-red-600');
        descBtn.classList.remove('text-gray-500', 'border-transparent');
        specsBtn.classList.remove('text-red-600', 'border-red-600');
        specsBtn.classList.add('text-gray-500', 'border-transparent');
    } else {
        descTab.classList.add('hidden');
        specsTab.classList.remove('hidden');
        specsBtn.classList.add('text-red-600', 'border-red-600');
        specsBtn.classList.remove('text-gray-500', 'border-transparent');
        descBtn.classList.remove('text-red-600', 'border-red-600');
        descBtn.classList.add('text-gray-500', 'border-transparent');
    }
}
</script>

<style>
.active-tab {
    color: #dc2626;
    border-bottom-color: #dc2626;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.active\:scale-98:active {
    transform: scale(0.98);
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\Herd\ja-lanka-ecommerce\resources\views/mobile/product.blade.php ENDPATH**/ ?>