@extends('layouts.mobile')

@section('title', 'Contact Us')

@section('content')
<div class="pb-20">
    
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-red-700 to-red-800 py-12 px-4 text-center text-white">
        <h1 class="text-3xl font-bold mb-2">Contact Us</h1>
        <div class="w-12 h-0.5 bg-white/50 mx-auto mb-3"></div>
        <p class="text-sm opacity-90">We'd love to hear from you</p>
    </div>
    
    <!-- Contact Info Cards -->
    <div class="p-4 space-y-3">
        <div class="flex gap-4 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Visit Us</h3>
                <p class="text-sm text-gray-500">No. 123, Galle Road, Colombo 03, Sri Lanka</p>
            </div>
        </div>
        
        <div class="flex gap-4 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Email Us</h3>
                <p class="text-sm text-gray-500">info@jalanka.com</p>
                <p class="text-sm text-gray-500">support@jalanka.com</p>
            </div>
        </div>
        
        <div class="flex gap-4 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Call Us</h3>
                <p class="text-sm text-gray-500">+94 11 234 5678</p>
                <p class="text-sm text-gray-500">+94 77 123 4567 (WhatsApp)</p>
            </div>
        </div>
        
        <div class="flex gap-4 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Business Hours</h3>
                <p class="text-sm text-gray-500">Mon-Fri: 9:00 AM - 6:00 PM</p>
                <p class="text-sm text-gray-500">Sat: 10:00 AM - 4:00 PM</p>
                <p class="text-sm text-gray-500">Sun: Closed</p>
            </div>
        </div>
    </div>
    
    <!-- Contact Form -->
    <div class="p-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-xl font-bold text-gray-800 text-center mb-4">Send Us a Message</h2>
            <p class="text-sm text-gray-500 text-center mb-5">We'll get back to you within 24 hours</p>
            
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <input type="text" name="name" placeholder="Your Name *" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition">
                </div>
                <div>
                    <input type="email" name="email" placeholder="Email Address *" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition">
                </div>
                <div>
                    <input type="tel" name="phone" placeholder="Phone Number"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition">
                </div>
                <div>
                    <select name="subject" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition">
                        <option value="">Select a subject</option>
                        <option value="order">Order Inquiry</option>
                        <option value="product">Product Question</option>
                        <option value="returns">Returns & Refunds</option>
                        <option value="wholesale">Wholesale Inquiry</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <textarea name="message" rows="5" placeholder="Your Message *" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition"></textarea>
                </div>
                <button type="submit" 
                        class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition">
                    Send Message
                </button>
            </form>
        </div>
    </div>
    
    <!-- Social Links -->
    <div class="p-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
            <h3 class="font-semibold text-gray-800 mb-3">Follow Us</h3>
            <div class="flex justify-center gap-4">
                <a href="#" class="w-10 h-10 bg-gray-100 hover:bg-red-600 rounded-full flex items-center justify-center transition group">
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                    </svg>
                </a>
                <a href="#" class="w-10 h-10 bg-gray-100 hover:bg-red-600 rounded-full flex items-center justify-center transition group">
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0021.68-12.372 9.97 9.97 0 002.5-2.66z"/>
                    </svg>
                </a>
                <a href="#" class="w-10 h-10 bg-gray-100 hover:bg-red-600 rounded-full flex items-center justify-center transition group">
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                    </svg>
                </a>
                <a href="#" class="w-10 h-10 bg-gray-100 hover:bg-red-600 rounded-full flex items-center justify-center transition group">
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22.162 5.656a8.384 8.384 0 01-2.402.658A4.196 4.196 0 0021.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 00-7.126 3.814 11.874 11.874 0 01-8.62-4.37 4.168 4.168 0 00-.566 2.103c0 1.45.738 2.731 1.86 3.48a4.168 4.168 0 01-1.894-.523v.052a4.185 4.185 0 003.355 4.101 4.21 4.21 0 01-1.89.072A4.185 4.185 0 007.97 16.65a8.394 8.394 0 01-6.191 1.732 11.83 11.83 0 006.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 002.087-2.165z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Google Map (Simplified) -->
    <div class="px-4 pb-4">
        <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
            <div class="h-48 bg-gray-200 flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    <p class="text-sm text-gray-500">View map for directions</p>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection