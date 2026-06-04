

<?php $__env->startSection('page_title', 'Edit Product'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <h2 class="text-xl font-semibold">Edit Product: <?php echo e($product->name); ?></h2>
    </div>
    
    <form action="<?php echo e(route('admin.products.update', $product->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="p-6 space-y-6">
            <!-- Product Type Display -->
            <div class="p-3 bg-gray-100 rounded-lg">
                <span class="text-sm text-gray-600">Product Type:</span>
                <span class="ml-2 px-2 py-1 text-sm rounded-full <?php echo e($product->type == 'food' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                    <?php echo e(ucfirst($product->type)); ?>

                </span>
            </div>
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                    <input type="text" name="name" value="<?php echo e($product->name); ?>" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category_id" required class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Select Category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e($product->category_id == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <?php if($product->type == 'appliance'): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                    <select name="brand_id" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Select Brand</option>
                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($brand->id); ?>" <?php echo e($product->brand_id == $brand->id ? 'selected' : ''); ?>>
                                <?php echo e($brand->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <?php if($product->type == 'food'): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Origin</label>
                    <select name="origin_id" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Select Origin</option>
                        <?php $__currentLoopData = $origins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $origin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($origin->id); ?>" <?php echo e($product->origin_id == $origin->id ? 'selected' : ''); ?>>
                                <?php echo e($origin->flag_icon); ?> <?php echo e($origin->country_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Descriptions -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                <textarea name="short_description" rows="2" class="w-full px-3 py-2 border rounded-lg"><?php echo e($product->short_description); ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Description</label>
                <textarea name="description" rows="5" class="w-full px-3 py-2 border rounded-lg"><?php echo e($product->description); ?></textarea>
            </div>
            
            <!-- Pricing -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">💰 Pricing</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price (LKR) *</label>
                        <input type="number" name="regular_price" step="0.01" value="<?php echo e($product->regular_price); ?>" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price (LKR)</label>
                        <input type="number" name="sale_price" step="0.01" value="<?php echo e($product->sale_price); ?>" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                </div>
            </div>
            
            <!-- Inventory -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">📦 Inventory</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="<?php echo e($product->inventory->quantity_on_hand ?? 0); ?>" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reorder Level</label>
                        <input type="number" name="reorder_level" value="<?php echo e($product->inventory->reorder_level ?? 5); ?>" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                </div>
            </div>
            
            <!-- Current Images -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">🖼️ Current Images</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="relative border rounded-lg p-2">
                        <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" class="w-full h-32 object-cover rounded">
                        <div class="mt-2 flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="radio" name="main_image_id" value="<?php echo e($image->id); ?>" <?php echo e($image->is_main ? 'checked' : ''); ?> class="mr-1">
                                <span class="text-xs">Main</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="delete_images[]" value="<?php echo e($image->id); ?>" class="mr-1">
                                <span class="text-xs text-red-600">Delete</span>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            
            <!-- Add New Images -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">➕ Add More Images</h3>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <input type="file" name="new_images[]" id="new_images" multiple accept="image/*" class="hidden" onchange="previewNewImages()">
                    <label for="new_images" class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Select Images
                    </label>
                </div>
                <div id="newImagePreview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
            </div>
            
            <!-- Attributes -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">🏷️ Attributes</h3>
                <div id="attributesContainer">
                    <?php $__currentLoopData = $product->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="attribute-row flex gap-3 mb-3">
                        <input type="text" name="attributes[<?php echo e($index); ?>][key]" value="<?php echo e($attr->key); ?>" class="flex-1 px-3 py-2 border rounded-lg">
                        <input type="text" name="attributes[<?php echo e($index); ?>][value]" value="<?php echo e($attr->value); ?>" class="flex-1 px-3 py-2 border rounded-lg">
                        <button type="button" onclick="removeAttribute(this)" class="text-red-600">Remove</button>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <button type="button" onclick="addAttributeRow()" class="text-red-600 hover:text-red-800">+ Add More Attribute</button>
            </div>
            
            <!-- Status -->
            <div class="border-t pt-6 flex space-x-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_available" value="1" <?php echo e($product->is_available ? 'checked' : ''); ?> class="mr-2">
                    <span>Available for purchase</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" <?php echo e($product->is_active ? 'checked' : ''); ?> class="mr-2">
                    <span>Active (visible on website)</span>
                </label>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <a href="<?php echo e(route('admin.products.index')); ?>" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">Update Product</button>
        </div>
    </form>
</div>

<script>
let attributeIndex = <?php echo e($product->attributes->count()); ?>;

function addAttributeRow() {
    const container = document.getElementById('attributesContainer');
    const newRow = document.createElement('div');
    newRow.className = 'attribute-row flex gap-3 mb-3';
    newRow.innerHTML = `
        <input type="text" name="attributes[${attributeIndex}][key]" placeholder="Attribute name" class="flex-1 px-3 py-2 border rounded-lg">
        <input type="text" name="attributes[${attributeIndex}][value]" placeholder="Value" class="flex-1 px-3 py-2 border rounded-lg">
        <button type="button" onclick="removeAttribute(this)" class="text-red-600">Remove</button>
    `;
    container.appendChild(newRow);
    attributeIndex++;
}

function removeAttribute(button) {
    button.closest('.attribute-row').remove();
}

function previewImages() {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    const files = document.getElementById('images').files;
    
    if (files.length === 0) return;
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Validate file type
        if (!file.type.match('image.*')) {
            alert('File "' + file.name + '" is not an image. Skipping.');
            continue;
        }
        
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File "' + file.name + '" is too large (max 5MB). Skipping.');
            continue;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative border rounded-lg p-2';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-32 object-cover rounded">
                <div class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded ${i === 0 ? '' : 'hidden'}">Main</div>
                <div class="text-xs text-gray-500 mt-1 text-center truncate">${file.name}</div>
            `;
            preview.appendChild(div);
        }
        reader.readAsDataURL(file);
    }
}

function previewNewImages() {
    const preview = document.getElementById('newImagePreview');
    preview.innerHTML = '';
    const files = document.getElementById('new_images').files;
    
    if (files.length === 0) return;
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        if (!file.type.match('image.*')) {
            alert('File "' + file.name + '" is not an image. Skipping.');
            continue;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            alert('File "' + file.name + '" is too large (max 5MB). Skipping.');
            continue;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative border rounded-lg p-2';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-32 object-cover rounded">
                <div class="text-xs text-gray-500 mt-1 text-center truncate">${file.name}</div>
            `;
            preview.appendChild(div);
        }
        reader.readAsDataURL(file);
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/admin/products/edit.blade.php ENDPATH**/ ?>