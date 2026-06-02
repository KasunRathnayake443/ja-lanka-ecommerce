

<?php $__env->startSection('page_title', 'Categories & Brands'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>
    
    <!-- ========== CATEGORIES SECTION ========== -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold">📂 Categories</h2>
                <p class="text-sm text-gray-500">For Food & Appliances</p>
            </div>
            <button onclick="openCategoryModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                + Add Category
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4"><?php echo e($category->name); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full <?php echo e($category->type == 'food' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                                <?php echo e(ucfirst($category->type)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="<?php echo e($category->is_active ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e($category->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick='editCategory(<?php echo e(json_encode($category)); ?>)' class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <form action="<?php echo e(route('admin.store.category.delete', $category->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this category?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">No categories yet</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- ========== BRANDS SECTION (Appliances Only) ========== -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold">🔌 Brands</h2>
                <p class="text-sm text-gray-500">For Appliances Only</p>
            </div>
            <button onclick="openBrandModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                + Add Brand
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Country</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4"><?php echo e($brand->name); ?></td>
                        <td class="px-6 py-4"><?php echo e($brand->country ?? '-'); ?></td>
                        <td class="px-6 py-4">
                            <span class="<?php echo e($brand->is_active ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e($brand->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick='editBrand(<?php echo e(json_encode($brand)); ?>)' class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <form action="<?php echo e(route('admin.store.brand.delete', $brand->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this brand?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">No brands yet</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- ========== ORIGINS SECTION ========== -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold">🌍 Origins (Countries)</h2>
                <p class="text-sm text-gray-500">For Food Items</p>
            </div>
            <button onclick="openOriginModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                + Add Origin
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Flag</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Country</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $origins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $origin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 text-xl"><?php echo e($origin->flag_icon ?? '🌏'); ?></td>
                        <td class="px-6 py-4"><?php echo e($origin->country_name); ?></td>
                        <td class="px-6 py-4">
                            <span class="<?php echo e($origin->is_active ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e($origin->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick='editOrigin(<?php echo e(json_encode($origin)); ?>)' class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <form action="<?php echo e(route('admin.store.origin.delete', $origin->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this origin?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">No origins yet</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="px-6 py-4 border-b flex justify-between">
            <h3 id="categoryModalTitle" class="text-lg font-semibold">Add Category</h3>
            <button onclick="closeCategoryModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form id="categoryForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="p-6 space-y-4">
                <input type="text" name="name" id="categoryName" placeholder="Category Name" required class="w-full px-3 py-2 border rounded-lg">
                <select name="type" id="categoryType" required class="w-full px-3 py-2 border rounded-lg">
                    <option value="food">🍜 Food</option>
                    <option value="appliance">🔌 Appliance</option>
                </select>
                <input type="number" name="display_order" id="categoryOrder" placeholder="Display Order" value="0" class="w-full px-3 py-2 border rounded-lg">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                    <span>Active</span>
                </label>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
                <button type="button" onclick="closeCategoryModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Brand Modal -->
<div id="brandModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="px-6 py-4 border-b flex justify-between">
            <h3 id="brandModalTitle" class="text-lg font-semibold">Add Brand</h3>
            <button onclick="closeBrandModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form id="brandForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="p-6 space-y-4">
                <input type="text" name="name" id="brandName" placeholder="Brand Name" required class="w-full px-3 py-2 border rounded-lg">
                <input type="text" name="country" id="brandCountry" placeholder="Country (e.g., Japan)" class="w-full px-3 py-2 border rounded-lg">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                    <span>Active</span>
                </label>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
                <button type="button" onclick="closeBrandModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Origin Modal -->
<div id="originModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="px-6 py-4 border-b flex justify-between">
            <h3 id="originModalTitle" class="text-lg font-semibold">Add Origin</h3>
            <button onclick="closeOriginModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form id="originForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="p-6 space-y-4">
                <input type="text" name="flag_icon" id="originFlag" placeholder="Flag Icon (e.g., 🇯🇵, 🇮🇹)" class="w-full px-3 py-2 border rounded-lg">
                <input type="text" name="country_name" id="originCountry" placeholder="Country Name" required class="w-full px-3 py-2 border rounded-lg">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                    <span>Active</span>
                </label>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
                <button type="button" onclick="closeOriginModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
// Route URLs
const categoryStoreUrl = "<?php echo e(route('admin.store.category.store')); ?>";
const categoryUpdateUrl = "<?php echo e(route('admin.store.category.update', ['id' => '__ID__'])); ?>";
const brandStoreUrl = "<?php echo e(route('admin.store.brand.store')); ?>";
const brandUpdateUrl = "<?php echo e(route('admin.store.brand.update', ['id' => '__ID__'])); ?>";
const originStoreUrl = "<?php echo e(route('admin.store.origin.store')); ?>";
const originUpdateUrl = "<?php echo e(route('admin.store.origin.update', ['id' => '__ID__'])); ?>";

// Category Functions
function openCategoryModal() {
    document.getElementById('categoryModal').classList.remove('hidden');
    document.getElementById('categoryModal').classList.add('flex');
    document.getElementById('categoryForm').action = categoryStoreUrl;
    document.getElementById('categoryModalTitle').innerText = 'Add Category';
    document.getElementById('categoryName').value = '';
    document.getElementById('categoryType').value = 'food';
    document.getElementById('categoryOrder').value = '0';
    document.getElementById('categoryForm').querySelector('input[name="_method"]')?.remove();
}

function editCategory(category) {
    openCategoryModal();
    document.getElementById('categoryModalTitle').innerText = 'Edit Category';
    document.getElementById('categoryForm').action = categoryUpdateUrl.replace('__ID__', category.id);
    let method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'PUT';
    document.getElementById('categoryForm').appendChild(method);
    document.getElementById('categoryName').value = category.name;
    document.getElementById('categoryType').value = category.type;
    document.getElementById('categoryOrder').value = category.display_order;
    document.getElementById('categoryForm').querySelector('input[name="is_active"]').checked = category.is_active;
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

// Brand Functions
function openBrandModal() {
    document.getElementById('brandModal').classList.remove('hidden');
    document.getElementById('brandModal').classList.add('flex');
    document.getElementById('brandForm').action = brandStoreUrl;
    document.getElementById('brandModalTitle').innerText = 'Add Brand';
    document.getElementById('brandName').value = '';
    document.getElementById('brandCountry').value = '';
    document.getElementById('brandForm').querySelector('input[name="_method"]')?.remove();
}

function editBrand(brand) {
    openBrandModal();
    document.getElementById('brandModalTitle').innerText = 'Edit Brand';
    document.getElementById('brandForm').action = brandUpdateUrl.replace('__ID__', brand.id);
    let method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'PUT';
    document.getElementById('brandForm').appendChild(method);
    document.getElementById('brandName').value = brand.name;
    document.getElementById('brandCountry').value = brand.country || '';
    document.getElementById('brandForm').querySelector('input[name="is_active"]').checked = brand.is_active;
}

function closeBrandModal() {
    document.getElementById('brandModal').classList.add('hidden');
}

// Origin Functions
function openOriginModal() {
    document.getElementById('originModal').classList.remove('hidden');
    document.getElementById('originModal').classList.add('flex');
    document.getElementById('originForm').action = originStoreUrl;
    document.getElementById('originModalTitle').innerText = 'Add Origin';
    document.getElementById('originFlag').value = '';
    document.getElementById('originCountry').value = '';
    document.getElementById('originForm').querySelector('input[name="_method"]')?.remove();
}

function editOrigin(origin) {
    openOriginModal();
    document.getElementById('originModalTitle').innerText = 'Edit Origin';
    document.getElementById('originForm').action = originUpdateUrl.replace('__ID__', origin.id);
    let method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'PUT';
    document.getElementById('originForm').appendChild(method);
    document.getElementById('originFlag').value = origin.flag_icon || '';
    document.getElementById('originCountry').value = origin.country_name;
    document.getElementById('originForm').querySelector('input[name="is_active"]').checked = origin.is_active;
}

function closeOriginModal() {
    document.getElementById('originModal').classList.add('hidden');
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/admin/store/index.blade.php ENDPATH**/ ?>