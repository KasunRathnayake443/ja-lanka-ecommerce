<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSaleBanner;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $banners = FlashSaleBanner::with('product')
            ->orderBy('display_order')
            ->get();

        return view('admin.flash-sales.index', compact('banners'));
    }

    public function create()
    {
        $existingIds = FlashSaleBanner::pluck('product_id')->toArray();

        $products = Product::whereNotIn('id', $existingIds)->get();

        return view('admin.flash-sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        FlashSaleBanner::create([
            'product_id' => $request->product_id,
            'custom_title' => $request->custom_title,
            'custom_subtitle' => $request->custom_subtitle,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active'),
            'is_manual' => true,
        ]);

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash sale banner added!');
    }

    public function edit($id)
    {
        $banner = FlashSaleBanner::with('product')->findOrFail($id);

        return view('admin.flash-sales.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = FlashSaleBanner::findOrFail($id);

        $banner->update([
            'custom_title' => $request->custom_title,
            'custom_subtitle' => $request->custom_subtitle,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash sale banner updated!');
    }

    public function destroy($id)
    {
        $banner = FlashSaleBanner::findOrFail($id);
        $banner->delete();

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash sale banner removed!');
    }
}
