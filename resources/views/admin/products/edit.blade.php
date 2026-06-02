@extends('admin.layouts.app')

@section('page_title', 'Edit Product')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <h2 class="text-xl font-semibold">Edit Product: {{ $product->name }}</h2>
    </div>
    
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="p-6 space-y-6">
            <!-- Product Type Display -->
            <div class="p-3 bg-gray-100 rounded-lg">
                <span class="text-sm text-gray-600">Product Type:</span>
                <span class="ml-2 px-2 py-1 text-sm rounded-full {{ $product->type == 'food' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ ucfirst($product->type) }}
                </span>
            </div>
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                    <input type="text" name="name" value="{{ $product->name }}" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category_id" required class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                @if($product->type == 'appliance')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                    <select name="brand_id" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                @if($product->type == 'food')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Origin</label>
                    <select name="origin_id" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Select Origin</option>
                        @foreach($origins as $origin)
                            <option value="{{ $origin->id }}" {{ $product->origin_id == $origin->id ? 'selected' : '' }}>
                                {{ $origin->flag_icon }} {{ $origin->country_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
            
            <!-- Descriptions -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                <textarea name="short_description" rows="2" class="w-full px-3 py-2 border rounded-lg">{{ $product->short_description }}</textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Description</label>
                <textarea name="description" rows="5" class="w-full px-3 py-2 border rounded-lg">{{ $product->description }}</textarea>
            </div>
            
            <!-- Pricing -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">💰 Pricing</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price (LKR) *</label>
                        <input type="number" name="regular_price" step="0.01" value="{{ $product->regular_price }}" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price (LKR)</label>
                        <input type="number" name="sale_price" step="0.01" value="{{ $product->sale_price }}" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                </div>
            </div>
            
            <!-- Inventory -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">📦 Inventory</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="{{ $product->inventory->quantity_on_hand ?? 0 }}" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reorder Level</label>
                        <input type="number" name="reorder_level" value="{{ $product->inventory->reorder_level ?? 5 }}" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                </div>
            </div>
            
            <!-- Current Images -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium mb-4">🖼️ Current Images</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($product->images as $image)
                    <div class="relative border rounded-lg p-2">
                        <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-32 object-cover rounded">
                        <div class="mt-2 flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="radio" name="main_image_id" value="{{ $image->id }}" {{ $image->is_main ? 'checked' : '' }} class="mr-1">
                                <span class="text-xs">Main</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="mr-1">
                                <span class="text-xs text-red-600">Delete</span>
                            </label>
                        </div>
                    </div>
                    @endforeach
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
                    @foreach($product->attributes as $index => $attr)
                    <div class="attribute-row flex gap-3 mb-3">
                        <input type="text" name="attributes[{{ $index }}][key]" value="{{ $attr->key }}" class="flex-1 px-3 py-2 border rounded-lg">
                        <input type="text" name="attributes[{{ $index }}][value]" value="{{ $attr->value }}" class="flex-1 px-3 py-2 border rounded-lg">
                        <button type="button" onclick="removeAttribute(this)" class="text-red-600">Remove</button>
                    </div>
                    @endforeach
                </div>
                <button type="button" onclick="addAttributeRow()" class="text-red-600 hover:text-red-800">+ Add More Attribute</button>
            </div>
            
            <!-- Status -->
            <div class="border-t pt-6 flex space-x-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }} class="mr-2">
                    <span>Available for purchase</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="mr-2">
                    <span>Active (visible on website)</span>
                </label>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <a href="{{ route('admin.products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">Update Product</button>
        </div>
    </form>
</div>

<script>
let attributeIndex = {{ $product->attributes->count() }};

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

function previewNewImages() {
    const preview = document.getElementById('newImagePreview');
    preview.innerHTML = '';
    const files = document.getElementById('new_images').files;
    
    for (let i = 0; i < files.length; i++) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.innerHTML = `<img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">`;
            preview.appendChild(div);
        }
        reader.readAsDataURL(files[i]);
    }
}
</script>
@endsection