<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getWishlist()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with('product.images')
            ->get();

        return response()->json($wishlist);
    }

    public function add($productId)
    {
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        if (!$exists) {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Added to wishlist'
        ]);
    }

    public function remove($productId)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Removed from wishlist'
        ]);
    }

    public function toggle($productId)
    {
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();
            $message = 'Removed from wishlist';
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
            $message = 'Added to wishlist';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'in_wishlist' => !$exists
        ]);
    }
}