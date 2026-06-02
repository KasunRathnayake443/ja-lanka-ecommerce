<?php

namespace App\Http\Controllers;

use App\Models\FlashSaleBanner;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function getActiveBanners()
    {
        $banners = FlashSaleBanner::where('is_active', true)
            ->with(['product' => function($q) {
                $q->with('images');
            }])
            ->orderBy('display_order')
            ->get();
        
        // Add computed fields
        foreach ($banners as $banner) {
            if ($banner->product) {
                $banner->product->has_sale = $banner->product->hasActiveSale();
                $banner->product->discount_percent = $banner->product->discount_percent;
                $banner->product->current_price = $banner->product->current_price;
            }
        }
        
        return response()->json($banners);
    }
}