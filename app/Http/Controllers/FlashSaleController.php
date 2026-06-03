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
        
        // Add computed fields for each product
        foreach ($banners as $banner) {
            if ($banner->product) {
                $hasActiveSale = $banner->product->hasActiveSale();
                $banner->product->discount_percent = $hasActiveSale 
                    ? round((($banner->product->regular_price - $banner->product->sale_price) / $banner->product->regular_price) * 100) 
                    : 0;
            }
        }
        
        return response()->json($banners);
    }
}