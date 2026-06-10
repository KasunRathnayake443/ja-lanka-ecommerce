<?php $__env->startSection('page_title', 'Banners'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Banner Management</h2>
            <p class="text-sm text-gray-500">Manage homepage hero banners</p>
        </div>
        <a href="<?php echo e(route('admin.banners.create')); ?>" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
            + Add Banner
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Button</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-6 py-4">
                        <img src="<?php echo e(asset('storage/' . $banner->image)); ?>" class="w-16 h-12 object-cover rounded">
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium"><?php echo e($banner->title); ?></div>
                        <?php if($banner->subtitle): ?>
                            <div class="text-sm text-gray-500"><?php echo e($banner->subtitle); ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php if($banner->button_text): ?>
                            <span class="text-sm"><?php echo e($banner->button_text); ?></span>
                        <?php else: ?>
                            <span class="text-sm text-gray-400">None</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4"><?php echo e($banner->display_order); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo e($banner->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                            <?php echo e($banner->is_active ? 'Active' : 'Inactive'); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="<?php echo e(route('admin.banners.edit', $banner->id)); ?>" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        <form action="<?php echo e(route('admin.banners.destroy', $banner->id)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this banner?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        No banners yet. <a href="<?php echo e(route('admin.banners.create')); ?>" class="text-red-600">Create your first banner</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\Herd\ja-lanka-ecommerce\resources\views/admin/banners/index.blade.php ENDPATH**/ ?>