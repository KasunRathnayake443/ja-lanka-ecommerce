<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Ja Lanka - <?php echo $__env->yieldContent('title', 'Global Flavors'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
      
    .mobile-container {
        max-width: 500px;
        margin: 0 auto;
        background: #f8fafc;
        min-height: 100vh;
        height: 100%;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    
    /* Bottom Navigation */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        max-width: 500px;
        margin: 0 auto;
        background: white;
        border-top: 1px solid #e5e7eb;
        padding: 8px 16px 20px;
        z-index: 40;
    }
    
    .mobile-content {
        flex: 1;
        overflow-y: auto;
        padding-bottom: 70px;
    }

        .mobile-container {
            max-width: 500px;
            margin: 0 auto;
            background: #f8fafc;
            min-height: 100vh;
            position: relative;
        }
        
        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-top: 1px solid #e5e7eb;
            padding: 8px 16px 20px;
            z-index: 40;
        }
        
        .mobile-content {
            padding-bottom: 80px;
        }
        
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            transition: color 0.2s;
        }
        
        .nav-item.active {
            color: #dc2626;
        }
        
        .nav-item.active svg {
            stroke: #dc2626;
        }
        
        .nav-item span {
            font-size: 11px;
        }
        
        /* Search Overlay */
        .search-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 100;
            display: none;
        }
        
        .search-panel {
            background: white;
            padding: 60px 20px 20px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }
    </style>
</head>
<body>
    <div class="mobile-container">
        
        <!-- Mobile Header -->
        <?php echo $__env->make('layouts.partials.mobile-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <!-- Search Overlay -->
        <div id="searchOverlay" class="search-overlay">
            <div class="search-panel">
                <div class="relative">
                    <input type="text" id="mobileSearchInput" placeholder="Search products..." 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500">
                    <button onclick="closeSearch()" class="absolute right-3 top-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="searchResults" class="mt-4"></div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="mobile-content">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
        
        <!-- Bottom Navigation -->
        <?php echo $__env->make('layouts.partials.mobile-bottom-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
    </div>
    
    <script>
        // Mobile search functions
        function openSearch() {
            document.getElementById('searchOverlay').style.display = 'block';
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                document.getElementById('mobileSearchInput')?.focus();
            }, 100);
        }
        
        function closeSearch() {
            document.getElementById('searchOverlay').style.display = 'none';
            document.body.style.overflow = '';
        }
        
        // Close on overlay click
        document.getElementById('searchOverlay')?.addEventListener('click', function(e) {
            if (e.target === this) closeSearch();
        });
    </script>
</body>
</html><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/layouts/mobile.blade.php ENDPATH**/ ?>