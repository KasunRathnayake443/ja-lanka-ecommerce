<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\StockHelper;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    /**
     * Display low stock products.
     */
    public function lowStock(Request $request)
    {
        $query = Product::where('is_active', true)
            ->whereHas('inventory', function($q) {
                $q->whereRaw('quantity_on_hand <= reorder_level');
            })
            ->with(['inventory', 'category', 'suppliers' => function($q) {
                $q->wherePivot('is_default', true);
            }]);

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        $products = $query->orderBy('name')->paginate(20);
        $stats = StockHelper::getStockStats();

        return view('admin.stock.low', compact('products', 'stats'));
    }

    /**
     * Display out of stock products.
     */
    public function outOfStock(Request $request)
    {
        $query = Product::where('is_active', true)
            ->whereHas('inventory', function($q) {
                $q->where('quantity_on_hand', '<=', 0);
            })
            ->with(['inventory', 'category', 'suppliers' => function($q) {
                $q->wherePivot('is_default', true);
            }]);

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        $products = $query->orderBy('name')->paginate(20);
        $stats = StockHelper::getStockStats();

        return view('admin.stock.out-of-stock', compact('products', 'stats'));
    }

    /**
     * Show restock request form for a product.
     */
    public function restockForm($productId)
    {
        $product = Product::with(['inventory', 'suppliers'])->findOrFail($productId);
        
        return view('admin.stock.restock', compact('product'));
    }

    /**
     * Store a restock request.
     */
    public function storeRestock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'expected_delivery_date' => 'nullable|date|after:today',
            'unit_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $supplier = Supplier::findOrFail($request->supplier_id);

        $inventory = Inventory::where('product_id', $request->product_id)->first();
        
        if ($inventory) {
            $oldQuantity = $inventory->quantity_on_hand;
            $newQuantity = $oldQuantity + $request->quantity;
            
            $inventory->update([
                'quantity_on_hand' => $newQuantity
            ]);

            Log::info('Manual restock performed', [
                'product' => $product->name,
                'supplier' => $supplier->name,
                'old_quantity' => $oldQuantity,
                'added_quantity' => $request->quantity,
                'new_quantity' => $newQuantity,
                'admin' => auth()->user()->name ?? 'System'
            ]);

            return redirect()->route('admin.stock.low')
                ->with('success', "Stock updated! Added {$request->quantity} units to {$product->name}. New stock: {$newQuantity}");
        }

        return redirect()->back()->with('error', 'Inventory not found for this product.');
    }

    /**
     * Get stock status for a product (AJAX).
     */
    public function getStockStatus($productId)
    {
        $status = StockHelper::getStockStatus($productId);
        $inventory = Inventory::where('product_id', $productId)->first();
        
        return response()->json([
            'status' => $status,
            'label' => StockHelper::getStockLabel($status),
            'badge_class' => StockHelper::getStockBadgeClass($status),
            'quantity_on_hand' => $inventory ? $inventory->quantity_on_hand : 0,
            'reorder_level' => $inventory ? $inventory->reorder_level : 0,
        ]);
    }

    /**
     * Get low stock count for menu badge (AJAX).
     */
    public function getStockCounts()
    {
        $stats = StockHelper::getStockStats();
        
        return response()->json([
            'low_stock' => $stats['low_stock_count'],
            'out_of_stock' => $stats['out_of_stock_count'],
        ]);
    }
}   