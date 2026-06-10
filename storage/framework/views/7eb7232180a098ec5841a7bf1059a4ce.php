<?php $__env->startSection('page_title', 'Add Flash Sale Banner'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <h2 class="text-xl font-semibold">Add Flash Sale Banner</h2>
        <p class="text-sm text-gray-500">Select a product to feature as a flash sale banner</p>
    </div>
    
    <form action="<?php echo e(route('admin.flash-sales.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Product *</label>
                <select name="product_id" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                    <option value="">-- Select a product --</option>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $hasSale = $product->hasActiveSale();
                            $discount = $hasSale ? $product->discount_percent : 0;
                        ?>
                        <option value="<?php echo e($product->id); ?>" <?php echo e(old('product_id') == $product->id ? 'selected' : ''); ?>>
                            <?php echo e($product->name); ?> 
                            <?php if($hasSale): ?>
                                - LKR <?php echo e(number_format($product->sale_price, 2)); ?> 
                                (<?php echo e($discount); ?>% OFF)
                            <?php else: ?>
                                - LKR <?php echo e(number_format($product->regular_price, 2)); ?>

                            <?php endif; ?>
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                
                <?php if($products->isEmpty()): ?>
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">No products available to add.</p>
                        <p class="text-xs text-yellow-600 mt-1">Add products first in Product Management.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Custom Title (Optional)</label>
                <input type="text" name="custom_title" placeholder="e.g., 🔥 Weekend Special!" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                <p class="text-xs text-gray-500 mt-1">Leave empty to use product name</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Custom Subtitle (Optional)</label>
                <input type="text" name="custom_subtitle" placeholder="e.g., Limited time offer!" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                <input type="number" name="display_order" value="0" class="w-32 px-3 py-2 border rounded-lg">
                <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
            </div>
            
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                    <span class="text-sm font-medium text-gray-700">Active (show on homepage)</span>
                </label>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <a href="<?php echo e(route('admin.flash-sales.index')); ?>" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                Add Banner
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/admin/flash-sales/create.blade.php ENDPATH**/ ?>