<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Origin;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // Get products with filters (for infinite scroll)
    public function getProducts(Request $request)
    {
        $query = Product::where('is_active', true)
            ->with(['images' => function ($q) {
                $q->where('is_main', true);
            }, 'origin', 'category', 'brand']);

        // Filter by type (food/appliance)
        if ($request->has('type') && in_array($request->type, ['food', 'appliance'])) {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by brand
        if ($request->has('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Filter by origin
        if ($request->has('origin')) {
            $query->where('origin_id', $request->origin);
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('regular_price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('regular_price', '<=', $request->max_price);
        }

        // Handle array filters for categories, brands, origins
        if ($request->has('categories') && is_array($request->categories)) {
            $query->whereIn('category_id', $request->categories);
        }
        if ($request->has('brands') && is_array($request->brands)) {
            $query->whereIn('brand_id', $request->brands);
        }
        if ($request->has('origins') && is_array($request->origins)) {
            $query->whereIn('origin_id', $request->origins);
        }

        // Filter by sale (products with active sale)
        if ($request->has('has_sale') && $request->has_sale == 'true') {
            $now = now();
            $query->whereNotNull('sale_price')
                ->where('sale_price', '<', 'regular_price')
                ->where(function ($q) use ($now) {
                    $q->whereNull('sale_start_date')
                        ->orWhere('sale_start_date', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('sale_end_date')
                        ->orWhere('sale_end_date', '>=', $now);
                });
        }

        // Sorting
        switch ($request->get('sort', 'newest')) {
            case 'price_low':
                $query->orderBy('regular_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('regular_price', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);

        // Add sale price and discount calculation
        foreach ($products as $product) {
            // Check if sale is active
            $hasActiveSale = $product->sale_price &&
                             $product->sale_price < $product->regular_price &&
                             ($product->sale_start_date <= now() || ! $product->sale_start_date) &&
                             ($product->sale_end_date >= now() || ! $product->sale_end_date);

            $product->current_price = $hasActiveSale ? $product->sale_price : $product->regular_price;
            $product->discount_percent = $hasActiveSale
                ? round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100)
                : 0;
            $product->has_sale = $hasActiveSale;
        }

        return response()->json($products);
    }

    // Get single product
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['images', 'origin', 'category', 'brand', 'attributes', 'inventory'])
            ->firstOrFail();

        // Check if sale is active
        $hasActiveSale = $product->sale_price &&
                         $product->sale_price < $product->regular_price &&
                         ($product->sale_start_date <= now() || ! $product->sale_start_date) &&
                         ($product->sale_end_date >= now() || ! $product->sale_end_date);

        $product->current_price = $hasActiveSale ? $product->sale_price : $product->regular_price;
        $product->discount_percent = $hasActiveSale
            ? round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100)
            : 0;
        $product->has_sale = $hasActiveSale;
        $product->stock = $product->inventory->quantity_on_hand ?? 0;

        // Get related products (same type, different product)
        $related = Product::where('type', $product->type)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['images' => function ($q) {
                $q->where('is_main', true);
            }])
            ->limit(4)
            ->get();

        // Also add sale info to related products
        foreach ($related as $item) {
            $itemHasSale = $item->sale_price &&
                           $item->sale_price < $item->regular_price &&
                           ($item->sale_start_date <= now() || ! $item->sale_start_date) &&
                           ($item->sale_end_date >= now() || ! $item->sale_end_date);
            $item->current_price = $itemHasSale ? $item->sale_price : $item->regular_price;
            $item->discount_percent = $itemHasSale
                ? round((($item->regular_price - $item->sale_price) / $item->regular_price) * 100)
                : 0;
        }

        // Check if in wishlist
        $inWishlist = false;
        if (Auth::check()) {
            $inWishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();
        }

        return view('product.show', compact('product', 'related', 'inWishlist'));
    }

    // Mobile product detail
    public function mobileShow($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['images', 'origin', 'category', 'brand', 'attributes', 'inventory'])
            ->firstOrFail();

        // Check if sale is active
        $hasActiveSale = $product->sale_price &&
                         $product->sale_price < $product->regular_price &&
                         ($product->sale_start_date <= now() || ! $product->sale_start_date) &&
                         ($product->sale_end_date >= now() || ! $product->sale_end_date);

        $product->current_price = $hasActiveSale ? $product->sale_price : $product->regular_price;
        $product->discount_percent = $hasActiveSale
            ? round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100)
            : 0;
        $product->has_sale = $hasActiveSale;
        $product->stock = $product->inventory->quantity_on_hand ?? 0;

        // Get related products (same type, different product)
        $related = Product::where('type', $product->type)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['images' => function ($q) {
                $q->where('is_main', true);
            }])
            ->limit(4)
            ->get();

        // Also add sale info to related products
        foreach ($related as $item) {
            $itemHasSale = $item->sale_price &&
                           $item->sale_price < $item->regular_price &&
                           ($item->sale_start_date <= now() || ! $item->sale_start_date) &&
                           ($item->sale_end_date >= now() || ! $item->sale_end_date);
            $item->current_price = $itemHasSale ? $item->sale_price : $item->regular_price;
            $item->discount_percent = $itemHasSale
                ? round((($item->regular_price - $item->sale_price) / $item->regular_price) * 100)
                : 0;
        }

        // Check if in wishlist
        $inWishlist = false;
        if (Auth::check()) {
            $inWishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();
        }

        return view('mobile.product', compact('product', 'related', 'inWishlist'));
    }

    // Get filter data (for sidebar and mobile filters)
    public function getFilters()
    {
        return response()->json([
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'brands' => Brand::where('is_active', true)->orderBy('name')->get(),
            'origins' => Origin::where('is_active', true)->orderBy('country_name')->get(),
        ]);
    }

    // Search products (for live search)
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->with(['images' => function ($q) {
                $q->where('is_main', true);
            }])
            ->limit(10)
            ->get();

        // Add sale info to search results
        foreach ($products as $product) {
            $hasActiveSale = $product->sale_price &&
                             $product->sale_price < $product->regular_price &&
                             ($product->sale_start_date <= now() || ! $product->sale_start_date) &&
                             ($product->sale_end_date >= now() || ! $product->sale_end_date);
            $product->current_price = $hasActiveSale ? $product->sale_price : $product->regular_price;
            $product->discount_percent = $hasActiveSale
                ? round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100)
                : 0;
        }

        return response()->json($products);
    }
}
