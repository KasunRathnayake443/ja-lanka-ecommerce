<?php $__env->startSection('title', 'My Orders - Ja Lanka'); ?>

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
                <h2 class="text-2xl font-light font-['Cormorant_Garamond'] mb-6">My Orders</h2>
                
                <?php if($orders->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-100 rounded-lg p-5">
                            <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                                <div>
                                    <p class="font-medium text-gray-900">Order #<?php echo e($order->order_number); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo e($order->created_at->format('F d, Y')); ?></p>
                                </div>
                                <div>
                                    <span class="px-3 py-1 text-xs rounded-full 
                                        <?php echo e($order->order_status == 'delivered' ? 'bg-green-100 text-green-700' : ''); ?>

                                        <?php echo e($order->order_status == 'processing' ? 'bg-blue-100 text-blue-700' : ''); ?>

                                        <?php echo e($order->order_status == 'shipped' ? 'bg-purple-100 text-purple-700' : ''); ?>

                                        <?php echo e($order->order_status == 'pending' ? 'bg-yellow-100 text-yellow-700' : ''); ?>">
                                        <?php echo e(ucfirst($order->order_status)); ?>

                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap justify-between items-center gap-4 pt-4 border-t border-gray-100">
                                <div>
                                    <p class="text-sm text-gray-500">Total Amount</p>
                                    <p class="font-semibold text-gray-900">LKR <?php echo e(number_format($order->grand_total, 2)); ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Items</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($order->items->sum('quantity')); ?> items</p>
                                </div>
                                <a href="<?php echo e(route('account.order.detail', $order->id)); ?>" class="text-sm text-red-700 hover:underline">View Details →</a>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <div class="mt-6">
                        <?php echo e($orders->links()); ?>

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
<?php echo $__env->make('layouts.desktop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/account/orders.blade.php ENDPATH**/ ?>