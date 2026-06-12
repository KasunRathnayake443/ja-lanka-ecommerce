<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $products = Product::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->with(['images' => function($q) {
                $q->where('is_main', true);
            }])
            ->limit(10)
            ->get();
        
        // Add current price and discount info
        foreach ($products as $product) {
            $hasActiveSale = $product->sale_price && 
                             $product->sale_price < $product->regular_price &&
                             ($product->sale_start_date <= now() || !$product->sale_start_date) &&
                             ($product->sale_end_date >= now() || !$product->sale_end_date);
            
            $product->current_price = $hasActiveSale ? $product->sale_price : $product->regular_price;
            $product->discount_percent = $hasActiveSale 
                ? round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100) 
                : 0;
        }
        
        return response()->json($products);
    }
}