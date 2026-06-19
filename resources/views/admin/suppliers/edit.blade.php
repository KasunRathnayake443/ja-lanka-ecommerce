@extends('admin.layouts.app')

@section('page_title', 'Edit Supplier')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Edit Supplier</h2>
            <p class="text-sm text-gray-500">Update supplier information</p>
        </div>
        <a href="{{ route('admin.suppliers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
            Back to Suppliers
        </a>
    </div>

    <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="p-6">
            <!-- Basic Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Supplier Name *</label>
                        <input type="text" name="name" value="{{ old('name', $supplier->name) }}" 
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}" 
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $supplier->email) }}" 
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" 
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mobile</label>
                        <input type="text" name="mobile" value="{{ old('mobile', $supplier->mobile) }}" 
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                        <input type="url" name="website" value="{{ old('website', $supplier->website) }}" 
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('website') border-red-500 @enderror">
                        @error('website')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Address Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="address" rows="2" 
                                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $supplier->address) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <input type="text" name="city" value="{{ old('city', $supplier->city) }}" 
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">State/Province</label>
                        <input type="text" name="state" value="{{ old('state', $supplier->state) }}" 
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $supplier->postal_code) }}" 
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                        <input type="text" name="country" value="{{ old('country', $supplier->country) }}" 
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Additional Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tax Number</label>
                        <input type="text" name="tax_number" value="{{ old('tax_number', $supplier->tax_number) }}" 
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Terms</label>
                        <input type="text" name="payment_terms" value="{{ old('payment_terms', $supplier->payment_terms) }}" 
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" rows="3" 
                                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes', $supplier->notes) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" {{ $supplier->is_active ? 'checked' : '' }} 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Supplier</button>
            </div>
        </div>
    </form>
</div>
@endsection