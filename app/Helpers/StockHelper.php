<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\Inventory;

class StockHelper
{
    /**
     * Get products that are low on stock.
     */
    public static function getLowStockProducts($limit = null)
    {
        $query = Product::where('is_active', true)
            ->whereHas('inventory', function($q) {
                $q->whereRaw('quantity_on_hand <= reorder_level');
            })
            ->with(['inventory', 'category', 'suppliers' => function($q) {
                $q->wherePivot('is_default', true);
            }])
            ->orderBy('name');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get products that are out of stock.
     */
    public static function getOutOfStockProducts($limit = null)
    {
        $query = Product::where('is_active', true)
            ->whereHas('inventory', function($q) {
                $q->where('quantity_on_hand', '<=', 0);
            })
            ->with(['inventory', 'category', 'suppliers' => function($q) {
                $q->wherePivot('is_default', true);
            }])
            ->orderBy('name');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get stock statistics.
     */
    public static function getStockStats()
    {
        $totalProducts = Product::where('is_active', true)->count();
        $lowStockCount = Product::where('is_active', true)
            ->whereHas('inventory', function($q) {
                $q->whereRaw('quantity_on_hand <= reorder_level');
            })
            ->count();
        
        $outOfStockCount = Product::where('is_active', true)
            ->whereHas('inventory', function($q) {
                $q->where('quantity_on_hand', '<=', 0);
            })
            ->count();

        $totalStockValue = Inventory::sum('quantity_on_hand');

        return [
            'total_products' => $totalProducts,
            'low_stock_count' => $lowStockCount,
            'out_of_stock_count' => $outOfStockCount,
            'total_stock_value' => $totalStockValue,
        ];
    }

    /**
     * Check if a product is low on stock.
     */
    public static function isLowStock($productId)
    {
        $inventory = Inventory::where('product_id', $productId)->first();
        if (!$inventory) {
            return false;
        }
        return $inventory->quantity_on_hand <= $inventory->reorder_level;
    }

    /**
     * Get the reorder status for a product.
     */
    public static function getStockStatus($productId)
    {
        $inventory = Inventory::where('product_id', $productId)->first();
        if (!$inventory) {
            return 'no_inventory';
        }

        if ($inventory->quantity_on_hand <= 0) {
            return 'out_of_stock';
        }

        if ($inventory->quantity_on_hand <= $inventory->reorder_level) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    /**
     * Get the stock level badge class.
     */
    public static function getStockBadgeClass($status)
    {
        switch ($status) {
            case 'out_of_stock':
                return 'bg-red-100 text-red-800';
            case 'low_stock':
                return 'bg-yellow-100 text-yellow-800';
            case 'in_stock':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    /**
     * Get the stock level label.
     */
    public static function getStockLabel($status)
    {
        switch ($status) {
            case 'out_of_stock':
                return 'Out of Stock';
            case 'low_stock':
                return 'Low Stock';
            case 'in_stock':
                return 'In Stock';
            default:
                return 'Unknown';
        }
    }
}