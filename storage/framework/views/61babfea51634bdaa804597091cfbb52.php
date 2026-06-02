

<?php $__env->startSection('page_title', 'Edit Flash Sale Banner'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <h2 class="text-xl font-semibold">Edit Flash Sale Banner</h2>
        <p class="text-sm text-gray-500">Manage flash sale banner settings</p>
    </div>
    
    <form action="<?php echo e(route('admin.flash-sales.update', $banner->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="p-6 space-y-6">
            <!-- Product Information (Read-only) -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Product Information</h3>
                <div class="flex gap-4">
                    <?php if($banner->product && $banner->product->images->first()): ?>
                        <img src="<?php echo e(asset('storage/' . $banner->product->images->first()->image_path)); ?>" 
                             class="w-20 h-20 object-cover rounded">
                    <?php else: ?>
                        <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                            <span class="text-gray-400 text-2xl">📷</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="flex-1">
                        <div class="font-semibold text-gray-800"><?php echo e($banner->product->name ?? 'Product Deleted'); ?></div>
                        <div class="text-sm text-gray-500">SKU: <?php echo e($banner->product->sku ?? 'N/A'); ?></div>
                        
                        <?php if($banner->product && $banner->product->hasActiveSale()): ?>
                            <div class="mt-2">
                                <span class="text-red-600 font-bold">LKR <?php echo e(number_format($banner->product->sale_price, 2)); ?></span>
                                <span class="text-gray-400 line-through ml-2">LKR <?php echo e(number_format($banner->product->regular_price, 2)); ?></span>
                                <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-800 rounded text-xs">
                                    -<?php echo e($banner->product->discount_percent); ?>% OFF
                                </span>
                            </div>
                        <?php elseif($banner->product): ?>
                            <div class="mt-2">
                                <span class="text-gray-800">LKR <?php echo e(number_format($banner->product->regular_price, 2)); ?></span>
                                <span class="ml-2 text-yellow-600 text-xs">(No active sale)</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Custom Title (Optional) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Custom Title (Optional)</label>
                <input type="text" name="custom_title" value="<?php echo e($banner->custom_title); ?>" 
                       placeholder="e.g., 🔥 Flash Sale - Limited Time!"
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                <p class="text-xs text-gray-500 mt-1">Leave empty to use product name</p>
            </div>
            
            <!-- Custom Subtitle (Optional) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Custom Subtitle (Optional)</label>
                <input type="text" name="custom_subtitle" value="<?php echo e($banner->custom_subtitle); ?>" 
                       placeholder="e.g., Up to 50% OFF on selected items"
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate from sale price</p>
            </div>
            
            <!-- Display Order -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                <input type="number" name="display_order" value="<?php echo e($banner->display_order); ?>" 
                       class="w-32 px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                <p class="text-xs text-gray-500 mt-1">Lower numbers appear first on homepage</p>
            </div>
            
            <!-- Active Status -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" <?php echo e($banner->is_active ? 'checked' : ''); ?> class="mr-2">
                    <span class="text-sm font-medium text-gray-700">Active (show on homepage)</span>
                </label>
                <p class="text-xs text-gray-500 mt-1">Only active banners will appear on the mobile homepage</p>
            </div>
            
            <!-- Preview Section -->
            <div class="border-t pt-6">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Preview</h3>
                <div class="bg-gray-100 rounded-lg p-4">
                    <div class="max-w-sm mx-auto bg-white rounded-lg shadow overflow-hidden">
                        <div class="flex">
                            <?php if($banner->product && $banner->product->images->first()): ?>
                                <div class="w-24 h-24 bg-gray-100">
                                    <img src="<?php echo e(asset('storage/' . $banner->product->images->first()->image_path)); ?>" 
                                         class="w-full h-full object-cover">
                                </div>
                            <?php else: ?>
                                <div class="w-24 h-24 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-2xl">📷</span>
                                </div>
                            <?php endif; ?>
                            <div class="flex-1 p-3">
                                <div class="font-semibold text-sm">
                                    <?php echo e($banner->custom_title ?? ($banner->product->name ?? 'Product Name')); ?>

                                </div>
                                <?php if($banner->custom_subtitle || ($banner->product && $banner->product->hasActiveSale())): ?>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <?php echo e($banner->custom_subtitle ?? ($banner->product && $banner->product->hasActiveSale() ? 'Limited time offer!' : '')); ?>

                                    </div>
                                <?php endif; ?>
                                <?php if($banner->product && $banner->product->hasActiveSale()): ?>
                                    <div class="mt-2">
                                        <span class="text-red-600 font-bold text-sm">LKR <?php echo e(number_format($banner->product->sale_price, 2)); ?></span>
                                        <span class="text-gray-400 text-xs line-through ml-1">LKR <?php echo e(number_format($banner->product->regular_price, 2)); ?></span>
                                    </div>
                                <?php elseif($banner->product): ?>
                                    <div class="mt-2">
                                        <span class="text-gray-800 text-sm">LKR <?php echo e(number_format($banner->product->regular_price, 2)); ?></span>
                                    </div>
                                <?php endif; ?>
                                <button class="mt-2 w-full bg-red-600 text-white text-xs py-1 rounded">
                                    Shop Now →
                                </button>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 text-center mt-3">This is how the banner will appear on mobile homepage</p>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <a href="<?php echo e(route('admin.flash-sales.index')); ?>" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                Update Banner
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/admin/flash-sales/edit.blade.php ENDPATH**/ ?>