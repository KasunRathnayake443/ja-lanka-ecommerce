@extends('admin.layouts.app')

@section('page_title', 'Edit Customer')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <h2 class="text-xl font-semibold">Edit Customer: {{ $customer->name }}</h2>
    </div>
    
    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                    <input type="text" name="mobile" value="{{ old('mobile', $customer->mobile) }}" class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                    @if($customer->profile_photo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $customer->profile_photo) }}" class="w-16 h-16 rounded-full object-cover">
                        </div>
                    @endif
                    <input type="file" name="profile_photo" accept="image/*" class="w-full">
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <a href="{{ route('admin.customers.show', $customer->id) }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">Update Customer</button>
        </div>
    </form>
</div>
@endsection