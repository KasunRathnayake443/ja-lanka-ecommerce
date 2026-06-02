@extends('admin.layouts.app')

@section('page_title', 'Add Banner')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <h2 class="text-xl font-semibold">Add New Banner</h2>
    </div>
    
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Banner Image *</label>
                <input type="file" name="image" accept="image/*" required class="w-full">
                <p class="text-xs text-gray-500 mt-1">Recommended size: 800x400px for mobile</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" required class="w-full px-3 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle (Optional)</label>
                <input type="text" name="subtitle" class="w-full px-3 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Button Text (Optional)</label>
                <input type="text" name="button_text" placeholder="e.g., Shop Now" class="w-full px-3 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Button Link (Optional)</label>
                <input type="url" name="button_link" placeholder="https://..." class="w-full px-3 py-2 border rounded-lg">
                <p class="text-xs text-gray-500 mt-1">e.g., /mobile/shop, /mobile/sale, /product/slug</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                <input type="number" name="display_order" value="0" class="w-32 px-3 py-2 border rounded-lg">
                <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
            </div>
            
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                    <span>Active (visible on homepage)</span>
                </label>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <a href="{{ route('admin.banners.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg">Save Banner</button>
        </div>
    </form>
</div>
@endsection