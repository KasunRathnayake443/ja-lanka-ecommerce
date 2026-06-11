@extends('admin.layouts.app')

@section('page_title', 'Add Coupon')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <h2 class="text-xl font-semibold">Add New Coupon</h2>
    </div>
    
    <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Coupon Code *</label>
                    <input type="text" name="code" required class="w-full px-3 py-2 border rounded-lg" placeholder="e.g., SUMMER20">
                    <p class="text-xs text-gray-500 mt-1">Will be converted to uppercase</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <input type="text" name="description" class="w-full px-3 py-2 border rounded-lg" placeholder="e.g., 20% off summer sale">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Discount Type *</label>
                    <select name="discount_type" required class="w-full px-3 py-2 border rounded-lg">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (LKR)</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Discount Value *</label>
                    <input type="number" name="discount_value" step="0.01" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Order Amount</label>
                    <input type="number" name="minimum_order" step="0.01" value="0" class="w-full px-3 py-2 border rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Minimum cart value to apply coupon</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Discount (Optional)</label>
                    <input type="number" name="maximum_discount" step="0.01" class="w-full px-3 py-2 border rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">For percentage discounts only</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Usage Limit (Total)</label>
                    <input type="number" name="usage_limit" class="w-full px-3 py-2 border rounded-lg" placeholder="Leave empty for unlimited">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Usage Limit Per User</label>
                    <input type="number" name="usage_limit_per_user" value="1" class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="starts_at" class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                    <input type="date" name="expires_at" class="w-full px-3 py-2 border rounded-lg">
                </div>
            </div>
            
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                    <span>Active (available for customers)</span>
                </label>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <a href="{{ route('admin.coupons.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">Create Coupon</button>
        </div>
    </form>
</div>
@endsection