<?php $__env->startSection('title', 'My Account - Ja Lanka'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-[1400px] mx-auto px-5 md:px-10 py-10">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Sidebar -->
        <aside class="lg:w-80">
            <?php echo $__env->make('account.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1">
            <div class="bg-white border border-gray-100 rounded-lg p-6">
                <h2 class="text-2xl font-light font-['Cormorant_Garamond'] mb-6">Welcome back, <?php echo e(Auth::user()->name); ?></h2>
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                    <div class="bg-gray-50 rounded-lg p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Total Orders</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo e($totalOrders); ?></p>
                            </div>
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Total Spent</p>
                                <p class="text-2xl font-bold text-gray-900">LKR <?php echo e(number_format($totalSpent, 2)); ?></p>
                            </div>
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Wishlist Items</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo e($wishlistCount); ?></p>
                            </div>
                            <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-pink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Orders -->
                <?php if($recentOrders->count() > 0): ?>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Recent Orders</h3>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-100 rounded-lg p-4 flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="font-medium text-gray-900">Order #<?php echo e($order->order_number); ?></p>
                                <p class="text-sm text-gray-500"><?php echo e($order->created_at->format('M d, Y')); ?></p>
                            </div>
                            <div>
                                <span class="px-3 py-1 text-xs rounded-full 
                                    <?php echo e($order->order_status == 'delivered' ? 'bg-green-100 text-green-700' : ''); ?>

                                    <?php echo e($order->order_status == 'processing' ? 'bg-blue-100 text-blue-700' : ''); ?>

                                    <?php echo e($order->order_status == 'shipped' ? 'bg-purple-100 text-purple-700' : ''); ?>

                                    <?php echo e($order->order_status == 'pending' ? 'bg-yellow-100 text-yellow-700' : ''); ?>

                                    <?php echo e($order->order_status == 'cancelled' ? 'bg-red-100 text-red-700' : ''); ?>">
                                    <?php echo e(ucfirst($order->order_status)); ?>

                                </span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">LKR <?php echo e(number_format($order->grand_total, 2)); ?></p>
                            </div>
                            <a href="<?php echo e(route('account.order.detail', $order->id)); ?>" class="text-sm text-red-700 hover:underline">View Details →</a>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <?php if($totalOrders > 5): ?>
                    <div class="mt-4 text-center">
                        <a href="<?php echo e(route('account.orders')); ?>" class="text-sm text-red-700 hover:underline">View All Orders →</a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <p class="text-gray-500">No orders yet</p>
                    <a href="<?php echo e(route('shop')); ?>" class="mt-3 inline-block text-red-700 hover:underline">Start Shopping →</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.desktop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\Herd\ja-lanka-ecommerce\resources\views/account/dashboard.blade.php ENDPATH**/ ?>