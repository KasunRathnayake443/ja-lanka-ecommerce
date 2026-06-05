<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function getWishlist()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with('product.images')
            ->get();

        return response()->json($wishlist);
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
            $inWishlist = false;
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
            $message = 'Added to wishlist';
            $inWishlist = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'in_wishlist' => $inWishlist
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
}