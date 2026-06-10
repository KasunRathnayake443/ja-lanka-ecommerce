<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin Panel - Ja Lanka</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gray-100">

<div class="flex h-screen">
    <!-- Sidebar - Fixed width -->
    <div class="w-64 bg-gray-900 text-white flex flex-col" style="min-width: 256px;">
        <!-- Logo -->
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-2xl font-bold">
                <span class="text-red-500">Ja</span>Lanka
                <span class="text-xs block text-gray-400">Admin Panel</span>
            </h2>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 py-6">
            <a href="<?php echo e(route('admin.dashboard')); ?>" 
               class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition mx-2 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="ml-3">Dashboard</span>
            </a>
            <a href="<?php echo e(route('admin.products.index')); ?>" 
            class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition mx-2 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span class="ml-3">Products</span>
            </a>
                        <a href="<?php echo e(route('admin.store.index')); ?>" 
            class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition mx-2 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span class="ml-3">Categories & Brands</span>
            </a>
            
            <a href="#" 
               class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition mx-2 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="ml-3">Orders</span>
            </a>
            
            <a href="#" 
               class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition mx-2 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="ml-3">Customers</span>
            </a>

            <!-- Banners Menu -->
            <a href="<?php echo e(route('admin.banners.index')); ?>" 
            class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition mx-2 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                </svg>
                <span class="ml-3">Banners</span>
            </a>

            <!-- Flash Sales Menu -->
            <a href="<?php echo e(route('admin.flash-sales.index')); ?>" 
            class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition mx-2 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="ml-3">Flash Sales</span>
            </a>
            
            <a href="#" 
               class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition mx-2 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="ml-3">Settings</span>
            </a>
        </nav>
        
        <!-- Logout -->
        <div class="p-6 border-t border-gray-700">
            <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="flex items-center w-full text-gray-300 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="ml-3">Logout</span>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-y-auto">
        <!-- Top Bar -->
        <div class="bg-white shadow-sm px-8 py-4 flex justify-between items-center sticky top-0 z-10">
            <h1 class="text-2xl font-semibold text-gray-800"><?php echo $__env->yieldContent('page_title', 'Dashboard'); ?></h1>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600"><?php echo e(Auth::guard('admin')->user()->name); ?></span>
                <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                    <?php echo e(substr(Auth::guard('admin')->user()->name, 0, 1)); ?>

                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="p-8">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
</div>

</body>
</html><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>