import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

console.log('Alpine.js loaded');

// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeMobileMenu = document.getElementById('closeMobileMenu');
    const overlay = document.getElementById('mobileMenuOverlay');
    
    function openMobileMenu() {
        if (mobileMenu) mobileMenu.classList.remove('-translate-x-full');
        if (overlay) overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMobileMenuFunc() {
        if (mobileMenu) mobileMenu.classList.add('-translate-x-full');
        if (overlay) overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openMobileMenu);
    if (closeMobileMenu) closeMobileMenu.addEventListener('click', closeMobileMenuFunc);
    if (overlay) overlay.addEventListener('click', closeMobileMenuFunc);
    
    // Search Bar Toggle
    const searchBtn = document.getElementById('searchBtn');
    const searchBar = document.getElementById('searchBar');
    const closeSearch = document.getElementById('closeSearch');
    
    if (searchBtn) {
        searchBtn.addEventListener('click', () => {
            if (searchBar) searchBar.classList.toggle('hidden');
        });
    }
    
    if (closeSearch) {
        closeSearch.addEventListener('click', () => {
            if (searchBar) searchBar.classList.add('hidden');
        });
    }
    
    // User Dropdown
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    
    if (userMenuBtn && userDropdown) {
        userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });
        
        document.addEventListener('click', () => {
            userDropdown.classList.add('hidden');
        });
    }
});

window.updateCartCount = function(count) {
    const cartCountEl = document.getElementById('cartCount');
    if (cartCountEl) {
        if (count > 0) {
            cartCountEl.textContent = count;
            cartCountEl.classList.remove('hidden');
        } else {
            cartCountEl.classList.add('hidden');
        }
    }
};