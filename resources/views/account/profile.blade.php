
@extends('layouts.desktop')

@section('title', 'Profile Settings - Ja Lanka')

@section('content')
<div class="max-w-[1400px] mx-auto px-5 md:px-10 py-10">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Sidebar -->
        <aside class="lg:w-80">
            @include('account.partials.sidebar')
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1">
            <div class="bg-white border border-gray-100 rounded-lg p-6">
                <h2 class="text-2xl font-light font-['Cormorant_Garamond'] mb-6">Profile Settings</h2>
                
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
                @endif
                
                <!-- Profile Info Form -->
                <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data" class="mb-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2 flex items-center gap-4 mb-4">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-16 h-16 rounded-full object-cover">
                            @else
                                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                                    <span class="text-2xl font-bold text-red-700">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                                <input type="file" name="profile_photo" accept="image/*" class="text-sm">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500 focus:outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500 focus:outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                            <input type="text" name="mobile" value="{{ old('mobile', Auth::user()->mobile) }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500 focus:outline-none">
                        </div>
                    </div>
                    
                    <div class="mt-5">
                        <button type="submit" class="bg-red-700 text-white px-6 py-2 rounded-lg hover:bg-red-800 transition">Update Profile</button>
                    </div>
                </form>
                
                <!-- Change Password Form -->
                <div class="border-t border-gray-100 pt-6">
                    <h3 class="text-lg font-semibold mb-4">Change Password</h3>
                    
                    <form action="{{ route('account.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password *</label>
                                <input type="password" name="current_password" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500 focus:outline-none">
                                @error('current_password')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div></div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">New Password *</label>
                                <input type="password" name="new_password" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500 focus:outline-none">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password *</label>
                                <input type="password" name="new_password_confirmation" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500 focus:outline-none">
                            </div>
                        </div>
                        
                        <div class="mt-5">
                            <button type="submit" class="bg-gray-900 text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection