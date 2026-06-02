@extends('admin.layouts.app')

@section('page_title', 'Add Product')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <h2 class="text-xl font-semibold">Add New Product</h2>
    </div>
    
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="p-6 space-y-6">
            <!-- Product Type Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Type *</label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="food" class="form-radio text-red-600" required onchange="toggleProductType()">
                        <span class="ml-2">🍜 Food Item</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="appliance" class="form-radio text-red-600" required onchange="toggleProductType()">
                        <span class="ml-2">🔌 Kitchen Appliance</span>
                    </label>
                </div>
            </div>
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg focus:border-red-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category_id" required class="w-full px-3 py-2 border rounded-lg focus:border-red-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Brand (Appliance Only) -->
                <div id="brandField" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Brand *</label>
                    <select name="brand_id" class="w-full px-3 py-2 border rounded-lg focus:border-red-500">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Origin (Food Only) -->
                <div id="originField" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Origin (Country)</label>
                    <select name="origin_id" class="w-full px-3 py-2 border rounded-lg focus:border-red-500">
                        <option value="">Select Origin</option>
                        @foreach($origins as $origin)
                            <option value="{{ $origin->id }}">{{ $origin->flag_icon }} {{ $origin->country_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Descriptions -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                <textarea name="short_description" rows="2" class="w-full px-3 py-2 border rounded-lg focus:border-red-500"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Description</label>
                <textarea name="description" rows="5" class="w-full px-3 py-2 border rounded-lg focus:border-red-500"></textarea>
            </div>
            
            <!-- Pricing -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">💰 Pricing</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price (LKR) *</label>
                        <input type="number" name="regular_price" step="0.01" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price (LKR)</label>
                        <input type="number" name="sale_price" step="0.01" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sale Start Date</label>
                        <input type="datetime-local" name="sale_start_date" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sale End Date</label>
                        <input type="datetime-local" name="sale_end_date" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                </div>
            </div>
            
            <!-- Inventory -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">📦 Inventory</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="0" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reorder Level (Low Stock Alert)</label>
                        <input type="number" name="reorder_level" value="5" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                </div>
            </div>
            
            <!-- Product Images -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">🖼️ Product Images</h3>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden" onchange="previewImages()">
                    <label for="images" class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Select Images
                    </label>
                    <p class="text-sm text-gray-500 mt-2">You can select multiple images. First image will be the main product image.</p>
                </div>
                <div id="imagePreview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
            </div>
            
            <!-- Dynamic Attributes -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">🏷️ Additional Attributes</h3>
                <div id="attributesContainer">
                    <div class="attribute-row flex gap-3 mb-3">
                        <input type="text" name="attributes[0][key]" placeholder="Attribute name (e.g., Material, Weight)" class="flex-1 px-3 py-2 border rounded-lg">
                        <input type="text" name="attributes[0][value]" placeholder="Value" class="flex-1 px-3 py-2 border rounded-lg">
                        <button type="button" onclick="removeAttribute(this)" class="text-red-600">Remove</button>
                    </div>
                </div>
                <button type="button" onclick="addAttributeRow()" class="text-red-600 hover:text-red-800">+ Add More Attribute</button>
            </div>
            
            <!-- Status -->
            <div class="border-t pt-6 flex space-x-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_available" value="1" checked class="mr-2">
                    <span>Available for purchase</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                    <span>Active (visible on website)</span>
                </label>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <a href="{{ route('admin.products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">Save Product</button>
        </div>
    </form>
</div>

<script>
let attributeIndex = 1;

function toggleProductType() {
    const type = document.querySelector('input[name="type"]:checked').value;
    const brandField = document.getElementById('brandField');
    const originField = document.getElementById('originField');
    
    if (type === 'appliance') {
        brandField.style.display = 'block';
        originField.style.display = 'none';
    } else {
        brandField.style.display = 'none';
        originField.style.display = 'block';
    }
}

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
    
    for (let i = 0; i < files.length; i++) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">
                <div class="absolute top-1 right-1 bg-green-500 text-white text-xs px-2 py-1 rounded ${i === 0 ? '' : 'hidden'}">Main</div>
            `;
            preview.appendChild(div);
        }
        reader.readAsDataURL(files[i]);
    }
}
</script>
@endsection