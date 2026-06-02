<!-- Wishlist Modal -->
<div id="wishlistModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" onclick="closeWishlistModal(event)">
    <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-xl overflow-y-auto" onclick="event.stopPropagation()">
        <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold">My Wishlist</h2>
            <button onclick="closeWishlistModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        
        <div id="wishlistContent" class="p-4">
            <div class="text-center py-8 text-gray-500">Loading...</div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/layouts/partials/wishlist-modal.blade.php ENDPATH**/ ?>