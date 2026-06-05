<?php

namespace App\Observers;

use App\Models\FlashSaleBanner;
use App\Models\Product;

class ProductObserver
{
    public function saved(Product $product)
    {
        $this->syncFlashSaleBanner($product);
    }

    public function updated(Product $product)
    {
        $this->syncFlashSaleBanner($product);
    }

    public function deleted(Product $product)
    {
        // Delete associated flash sale banner
        FlashSaleBanner::where('product_id', $product->id)->delete();
    }

    protected function syncFlashSaleBanner(Product $product)
    {
        $hasActiveSale = $product->hasActiveSale();
        $existingBanner = FlashSaleBanner::where('product_id', $product->id)->first();

        if ($hasActiveSale && ! $existingBanner) {
            // Auto-create banner (inactive until admin approves)
            FlashSaleBanner::create([
                'product_id' => $product->id,
                'is_active' => false,
                'is_manual' => false,
                'display_order' => 0,
            ]);
        } elseif (! $hasActiveSale && $existingBanner && ! $existingBanner->is_manual) {
            // Auto-delete banner only if it wasn't manually created
            $existingBanner->delete();
        }
    }
}
