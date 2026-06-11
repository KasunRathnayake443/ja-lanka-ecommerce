@extends('layouts.mobile')

@section('title', 'Profile Settings')

@section('content')
<div class="pb-20">
    
    <!-- Header -->
    <div class="bg-white border-b border-gray-100 px-5 py-4 sticky top-0 z-10">
        <div class="flex items-center gap-3">
            <a href="{{ route('mobile.account.dashboard') }}" class="text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Profile Settings</h1>
        </div>
    </div>
    
    <div class="p-4 space-y-6">
        @if(session('success'))
            <div class="p-3 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        
        <!-- Profile Photo -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex flex-col items-center">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-20 h-20 rounded-full object-cover mb-3">
                @else
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-3">
                        <span class="text-3xl font-bold text-red-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                @endif
                <button onclick="document.getElementById('profilePhoto').click()" class="text-sm text-red-600">Change Photo</button>
                <input type="file" id="profilePhoto" class="hidden" accept="image/*">
            </div>
        </div>
        
        <!-- Profile Information Form -->
        <form action="{{ route('mobile.account.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="file" name="profile_photo" id="profilePhotoInput" class="hidden" accept="image/*">
            
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                    <input type="tel" name="mobile" value="{{ old('mobile', Auth::user()->mobile) }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition">
                </div>
            </div>
            
            <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                Update Profile
            </button>
        </form>
        
        <!-- Change Password Form -->
        <form action="{{ route('mobile.account.password.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 space-y-4">
                <h3 class="font-semibold text-gray-800">Change Password</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password *</label>
                    <input type="password" name="current_password" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition">
                    @error('current_password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password *</label>
                    <input type="password" name="new_password" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password *</label>
                    <input type="password" name="new_password_confirmation" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition">
                </div>
            </div>
            
            <button type="submit" class="w-full bg-gray-800 text-white py-3 rounded-lg font-semibold hover:bg-gray-900 transition">
                Change Password
            </button>
        </form>
    </div>
    
</div>

<script>
    document.getElementById('profilePhoto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const input = document.getElementById('profilePhotoInput');
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
            document.querySelector('form').submit();
        }
    });
</script>
@endsection