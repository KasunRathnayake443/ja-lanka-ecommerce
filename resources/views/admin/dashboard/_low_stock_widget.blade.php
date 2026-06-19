@php
    use App\Helpers\StockHelper;
    $lowStockProducts = StockHelper::getLowStockProducts(10);
    $outOfStockProducts = StockHelper::getOutOfStockProducts(10);
    $stockStats = StockHelper::getStockStats();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Low Stock Products -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-yellow-50 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-yellow-800">⚠️ Low Stock Products</h3>
                <p class="text-sm text-yellow-600">Products below reorder level</p>
            </div>
            @if($stockStats['low_stock_count'] > 0)
                <a href="{{ route('admin.stock.low') }}" class="text-sm text-yellow-600 hover:text-yellow-800">
                    View All ({{ $stockStats['low_stock_count'] }})
                </a>
            @endif
        </div>
        <div class="p-4">
            @if($lowStockProducts->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($lowStockProducts as $product)
                        <div class="py-3 flex justify-between items-center">
                            <div>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-gray-800 hover:text-blue-600">
                                    {{ $product->name }}
                                </a>
                                <div class="text-sm text-gray-500">
                                    SKU: {{ $product->sku }}
                                    @if($product->defaultSupplier)
                                        <span class="ml-2">Supplier: {{ $product->defaultSupplier->name }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-medium text-yellow-700">
                                    {{ $product->inventory->quantity_on_hand ?? 0 }} / 
                                    {{ $product->inventory->reorder_level ?? 5 }}
                                </div>
                                <a href="{{ route('admin.stock.restock', $product->id) }}" class="text-xs text-blue-600 hover:text-blue-800">
                                    Create Restock Request
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>All products are well stocked! 🎉</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Out of Stock Products -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-red-50 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-red-800">🚫 Out of Stock</h3>
                <p class="text-sm text-red-600">Products with zero inventory</p>
            </div>
            @if($stockStats['out_of_stock_count'] > 0)
                <a href="{{ route('admin.stock.out-of-stock') }}" class="text-sm text-red-600 hover:text-red-800">
                    View All ({{ $stockStats['out_of_stock_count'] }})
                </a>
            @endif
        </div>
        <div class="p-4">
            @if($outOfStockProducts->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($outOfStockProducts as $product)
                        <div class="py-3 flex justify-between items-center">
                            <div>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-gray-800 hover:text-blue-600">
                                    {{ $product->name }}
                                </a>
                                <div class="text-sm text-gray-500">
                                    SKU: {{ $product->sku }}
                                    @if($product->defaultSupplier)
                                        <span class="ml-2">Supplier: {{ $product->defaultSupplier->name }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-medium text-red-700">
                                    {{ $product->inventory->quantity_on_hand ?? 0 }}
                                </div>
                                <a href="{{ route('admin.stock.restock', $product->id) }}" class="text-xs text-blue-600 hover:text-blue-800">
                                    Create Restock Request
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>No products are out of stock! 🎉</p>
                </div>
            @endif
        </div>
    </div>
</div>