<?php $__env->startSection('page_title', 'Edit Banner'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <h2 class="text-xl font-semibold">Edit Banner: <?php echo e($banner->title); ?></h2>
    </div>
    
    <form action="<?php echo e(route('admin.banners.update', $banner->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                <img src="<?php echo e(asset('storage/' . $banner->image)); ?>" class="w-48 h-32 object-cover rounded">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Image (Optional)</label>
                <input type="file" name="image" accept="image/*" class="w-full">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" value="<?php echo e($banner->title); ?>" required class="w-full px-3 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                <input type="text" name="subtitle" value="<?php echo e($banner->subtitle); ?>" class="w-full px-3 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                <input type="text" name="button_text" value="<?php echo e($banner->button_text); ?>" class="w-full px-3 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Button Link</label>
                <input type="url" name="button_link" value="<?php echo e($banner->button_link); ?>" class="w-full px-3 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                <input type="number" name="display_order" value="<?php echo e($banner->display_order); ?>" class="w-32 px-3 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" <?php echo e($banner->is_active ? 'checked' : ''); ?> class="mr-2">
                    <span>Active</span>
                </label>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <a href="<?php echo e(route('admin.banners.index')); ?>" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg">Update Banner</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\Herd\ja-lanka-ecommerce\resources\views/admin/banners/edit.blade.php ENDPATH**/ ?>