<?php

namespace App\Http\Controllers;

use App\Models\Banner;

class BannerController extends Controller
{
    public function getActiveBanners()
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return response()->json($banners);
    }
}
