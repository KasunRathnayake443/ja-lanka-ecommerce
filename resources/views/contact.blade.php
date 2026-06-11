@extends('layouts.desktop')

@section('title', 'Contact Us - Ja Lanka')

@section('content')

<!-- Hero Section -->
<section class="relative h-[40vh] min-h-[300px] bg-cover bg-center flex items-center" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('/images/contact-hero.jpg');">
    <div class="container mx-auto px-6 text-center text-white">
        <h1 class="text-5xl md:text-6xl font-bold font-['Playfair_Display'] mb-4">Contact Us</h1>
        <div class="w-20 h-0.5 bg-red-600 mx-auto mb-6"></div>
        <p class="text-lg max-w-2xl mx-auto">
            We'd love to hear from you. Get in touch with our team.
        </p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            
            <!-- Contact Form -->
            <div>
                <h2 class="text-3xl font-bold font-['Playfair_Display'] text-gray-900 mb-4">Send Us a Message</h2>
                <div class="w-16 h-px bg-red-600 mb-6"></div>
                <p class="text-gray-600 mb-8">
                    Have a question about our products, orders, or need assistance? 
                    Fill out the form and we'll get back to you within 24 hours.
                </p>
                
                <form action="#" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Your Name *</label>
                            <input type="text" id="name" name="name" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" id="email" name="email" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                        </div>
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="phone" name="phone" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                        <select id="subject" name="subject" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                            <option value="">Select a subject</option>
                            <option value="order">Order Inquiry</option>
                            <option value="product">Product Question</option>
                            <option value="returns">Returns & Refunds</option>
                            <option value="wholesale">Wholesale Inquiry</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                        <textarea id="message" name="message" rows="5" required 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition"></textarea>
                    </div>
                    
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-300 transform hover:scale-105">
                        Send Message
                    </button>
                </form>
            </div>
            
            <!-- Contact Information -->
            <div>
                <h2 class="text-3xl font-bold font-['Playfair_Display'] text-gray-900 mb-4">Get in Touch</h2>
                <div class="w-16 h-px bg-red-600 mb-6"></div>
                <p class="text-gray-600 mb-8">
                    Here's how you can reach us. We're available Monday to Friday, 9am to 6pm.
                </p>
                
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Visit Us</h3>
                            <p class="text-gray-600">
                                No. 123, Galle Road,<br>
                                Colombo 03, Sri Lanka
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Email Us</h3>
                            <p class="text-gray-600">
                                info@jalanka.com<br>
                                support@jalanka.com
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Call Us</h3>
                            <p class="text-gray-600">
                                +94 11 234 5678<br>
                                +94 77 123 4567 (WhatsApp)
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Business Hours</h3>
                            <p class="text-gray-600">
                                Monday - Friday: 9:00 AM - 6:00 PM<br>
                                Saturday: 10:00 AM - 4:00 PM<br>
                                Sunday: Closed
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Social Links -->
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h3 class="font-semibold text-gray-800 mb-4">Follow Us</h3>
                    <div class="flex gap-4">
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
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="bg-white rounded-xl overflow-hidden shadow-sm">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.945425300739!2d79.8430!3d6.9271!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2592d6a761d61%3A0x3a3e8d7e7b3b7b3b!2sColombo%2C%20Sri%20Lanka!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s" 
                width="100%" 
                height="400" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-10">
            <span class="text-red-600 text-sm uppercase tracking-wider">FAQ</span>
            <h2 class="text-3xl font-bold font-['Playfair_Display'] text-gray-900 mt-2">Frequently Asked Questions</h2>
            <div class="w-16 h-px bg-red-600 mx-auto mt-4"></div>
        </div>
        
        <div class="max-w-3xl mx-auto space-y-4">
            <div class="border border-gray-100 rounded-lg">
                <button class="w-full text-left px-6 py-4 font-semibold text-gray-800 hover:text-red-600 transition flex justify-between items-center">
                    How long does delivery take?
                    <svg class="w-5 h-5 transform transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600">
                    Delivery within Colombo takes 1-2 business days. For other areas, delivery takes 3-5 business days.
                </div>
            </div>
            
            <div class="border border-gray-100 rounded-lg">
                <button class="w-full text-left px-6 py-4 font-semibold text-gray-800 hover:text-red-600 transition flex justify-between items-center">
                    Do you offer international shipping?
                    <svg class="w-5 h-5 transform transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600">
                    Currently we only ship within Sri Lanka. We're working on expanding internationally soon!
                </div>
            </div>
            
            <div class="border border-gray-100 rounded-lg">
                <button class="w-full text-left px-6 py-4 font-semibold text-gray-800 hover:text-red-600 transition flex justify-between items-center">
                    What payment methods do you accept?
                    <svg class="w-5 h-5 transform transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600">
                    We accept credit/debit cards, online banking, and cash on delivery.
                </div>
            </div>
            
            <div class="border border-gray-100 rounded-lg">
                <button class="w-full text-left px-6 py-4 font-semibold text-gray-800 hover:text-red-600 transition flex justify-between items-center">
                    Can I return a product?
                    <svg class="w-5 h-5 transform transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600">
                    Yes, we have a 7-day return policy for unused products in original packaging. Food items cannot be returned.
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // FAQ Accordion
    document.querySelectorAll('.border').forEach(accordion => {
        const button = accordion.querySelector('button');
        const content = accordion.querySelector('div:last-child');
        const icon = button.querySelector('svg');
        
        button.addEventListener('click', () => {
            const isOpen = !content.classList.contains('hidden');
            
            // Close all others
            document.querySelectorAll('.border div:last-child').forEach(c => {
                if (c !== content) c.classList.add('hidden');
            });
            document.querySelectorAll('.border button svg').forEach(i => {
                if (i !== icon) i.style.transform = 'rotate(0deg)';
            });
            
            // Toggle current
            content.classList.toggle('hidden');
            icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    });
</script>

@endsection