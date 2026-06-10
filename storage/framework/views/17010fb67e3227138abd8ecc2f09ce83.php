<!-- Cart Modal -->
<div id="cartModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" onclick="closeCartModal(event)">
    <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-xl overflow-y-auto" onclick="event.stopPropagation()">
        <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold">Your Cart</h2>
            <button onclick="closeCartModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        
        <div id="cartContent" class="p-4">
            <div class="text-center py-8 text-gray-500">Loading...</div>
        </div>
        
        <div id="cartFooter" class="sticky bottom-0 bg-white p-4 border-t hidden">
            <div class="mb-4">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Subtotal:</span>
                    <span id="cartSubtotal" class="font-semibold">LKR 0</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Shipping:</span>
                    <span id="cartShipping" class="font-semibold">LKR 0</span>
                </div>
                <div class="flex justify-between border-t pt-2 mt-2">
                    <span class="text-lg font-bold">Total:</span>
                    <span id="cartTotal" class="text-lg font-bold text-red-600">LKR 0</span>
                </div>
            </div>
            <button onclick="proceedToCheckout()" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                Proceed to Checkout
            </button>
            <button onclick="closeCartModal()" class="w-full mt-2 text-gray-500 py-2 text-sm hover:text-gray-700">
                Continue Shopping
            </button>
        </div>
    </div>
</div><?php /**PATH C:\Users\User\Herd\ja-lanka-ecommerce\resources\views/layouts/partials/cart-modal.blade.php ENDPATH**/ ?>