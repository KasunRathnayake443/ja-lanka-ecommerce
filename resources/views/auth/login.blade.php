@extends('layouts.desktop')

@section('title', 'Login - Ja Lanka')

@section('content')
<div class="min-h-screen flex items-center justify-center py-20 px-5">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-light font-['Cormorant_Garamond'] text-gray-900">Welcome Back</h1>
            <p class="text-gray-500 mt-2">Sign in to your account</p>
        </div>
        
        <div class="bg-white border border-gray-100 rounded-xl p-8 shadow-sm">
            @if(session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:outline-none transition">
                    @error('email')
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
                
                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-red-700 border-gray-300 rounded focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-red-700 hover:underline">
                            Forgot password?
                        </a>
                    @endif
                </div>
                
                <button type="submit" class="w-full bg-red-700 text-white py-3 rounded-lg font-semibold hover:bg-red-800 transition">
                    Sign In
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-red-700 hover:underline font-medium">
                        Create an account
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