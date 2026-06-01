<footer class="bg-gray-800 text-white mt-12">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">Ja Lanka</h3>
                <p class="text-gray-400">Your trusted source for authentic Japanese kitchen appliances and food items in Sri Lanka.</p>
            </div>
            
            <div>
                <h4 class="font-semibold mb-3">Quick Links</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('products.index') }}" class="hover:text-white transition">Shop</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-white transition">FAQ</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-semibold mb-3">Customer Service</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Shipping Info</a></li>
                    <li><a href="#" class="hover:text-white transition">Returns Policy</a></li>
                    <li><a href="#" class="hover:text-white transition">Terms & Conditions</a></li>
                    <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-semibold mb-3">Contact Us</h4>
                <ul class="space-y-2 text-gray-400">
                    <li>📞 +94 11 234 5678</li>
                    <li>✉️ info@jalanka.com</li>
                    <li>📍 Colombo, Sri Lanka</li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400 text-sm">
            <p>&copy; {{ date('Y') }} Ja Lanka. All rights reserved.</p>
        </div>
    </div>
</footer>