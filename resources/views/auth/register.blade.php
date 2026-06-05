@extends('layouts.desktop')

@section('title', 'Register - Ja Lanka')

@section('content')
<div class="min-h-screen flex items-center justify-center py-20 px-5">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-light font-['Cormorant_Garamond'] text-gray-900">Create Account</h1>
            <p class="text-gray-500 mt-2">Join Ja Lanka today</p>
        </div>
        
        <div class="bg-white border border-gray-100 rounded-xl p-8 shadow-sm">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Name -->
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:outline-none transition">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:outline-none transition">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Mobile (Optional) -->
                <div class="mb-5">
                    <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number (Optional)</label>
                    <input id="mobile" type="text" name="mobile" value="{{ old('mobile') }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:outline-none transition">
                    @error('mobile')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password -->
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:outline-none transition">
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:outline-none transition">
                </div>
                
                <button type="submit" class="w-full bg-red-700 text-white py-3 rounded-lg font-semibold hover:bg-red-800 transition">
                    Create Account
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-red-700 hover:underline font-medium">
                        Sign in
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-red-700 transition">
                ← Back to Home
            </a>
        </div>
    </div>
</div>
@endsection