<?php $__env->startSection('page_title', 'Products'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">📦 Products</h2>
            <p class="text-sm text-gray-500">Manage your food and appliance products</p>
        </div>
        <a href="<?php echo e(route('admin.products.create')); ?>" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
            + Add Product
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Name / SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-6 py-4">
                        <?php if($product->images->first()): ?>
                            <img src="<?php echo e(asset('storage/' . $product->images->first()->image_path)); ?>" class="w-12 h-12 object-cover rounded">
                        <?php else: ?>
                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-400">📷</div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium"><?php echo e($product->name); ?></div>
                        <div class="text-sm text-gray-500">SKU: <?php echo e($product->sku); ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo e($product->type == 'food' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                            <?php echo e(ucfirst($product->type)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4">
                        LKR <?php echo e(number_format($product->regular_price, 2)); ?>

                        <?php if($product->sale_price): ?>
                            <div class="text-sm text-red-600">Sale: LKR <?php echo e(number_format($product->sale_price, 2)); ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php $stock = $product->inventory->quantity_on_hand ?? 0; ?>
                        <?php if($stock > 10): ?>
                            <span class="text-green-600"><?php echo e($stock); ?> in stock</span>
                        <?php elseif($stock > 0): ?>
                            <span class="text-orange-600">Only <?php echo e($stock); ?> left</span>
                        <?php else: ?>
                            <span class="text-red-600">Out of stock</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="<?php echo e($product->is_active ? 'text-green-600' : 'text-red-600'); ?>">
                            <?php echo e($product->is_active ? 'Active' : 'Inactive'); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        <form action="<?php echo e(route('admin.products.destroy', $product->id)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        No products yet. <a href="<?php echo e(route('admin.products.create')); ?>" class="text-red-600">Create your first product</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t">
        <?php echo e($products->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/admin/products/index.blade.php ENDPATH**/ ?>