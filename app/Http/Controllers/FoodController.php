<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Origin;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        // Get filter values
        $selectedCategories = $request->get('categories', []);
        $selectedOrigins = $request->get('origins', []);
        $minPrice = $request->get('min_price', 0);
        $maxPrice = $request->get('max_price', 100000);
        $sort = $request->get('sort', 'newest');
        
        // Build query
        $query = Product::where('type', 'food')
            ->where('is_active', true)
            ->with(['category', 'origin', 'images' => function($q) {
                $q->where('is_main', true);
            }]);
        
        // Apply category filter
        if (!empty($selectedCategories)) {
            $query->whereIn('category_id', $selectedCategories);
        }
        
        // Apply origin filter
        if (!empty($selectedOrigins)) {
            $query->whereIn('origin_id', $selectedOrigins);
        }
        
        // Apply price filter
        $query->where('regular_price', '>=', $minPrice)
              ->where('regular_price', '<=', $maxPrice);
        
        // Apply sorting
        switch ($sort) {
            case 'price_low':
                $query->orderBy('regular_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('regular_price', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Get products with pagination
        $products = $query->paginate(12);
        
        // Get filter data
        $categories = Category::where('type', 'food')->where('is_active', true)->get();
        $origins = Origin::where('is_active', true)->get();
        
        return view('food.index', compact('products', 'categories', 'origins', 'selectedCategories', 'selectedOrigins', 'minPrice', 'maxPrice', 'sort'));
    }
    
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('type', 'food')
            ->where('is_active', true)
            ->with(['category', 'origin', 'brand', 'attributes', 'images', 'inventory'])
            ->firstOrFail();
        
        // Increment view count
        $product->increment('views_count');
        
        // Get related products (same category)
        $relatedProducts = Product::where('type', 'food')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['images' => function($q) {
                $q->where('is_main', true);
            }])
            ->limit(4)
            ->get();
        
        return view('food.show', compact('product', 'relatedProducts'));
    }
}