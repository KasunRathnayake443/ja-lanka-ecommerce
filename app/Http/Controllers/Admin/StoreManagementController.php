<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Origin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StoreManagementController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('display_order')->get();
        $brands = Brand::orderBy('name')->get();
        $origins = Origin::orderBy('country_name')->get();
        
        return view('admin.store.index', compact('categories', 'brands', 'origins'));
    }
    
    // ========== CATEGORY METHODS ==========
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:food,appliance',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'type' => $request->type,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.store.index')->with('success', 'Category added successfully!');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'type' => $request->type,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.store.index')->with('success', 'Category updated successfully!');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products!');
        }
        
        $category->delete();
        return redirect()->route('admin.store.index')->with('success', 'Category deleted successfully!');
    }
    
    // ========== BRAND METHODS (ONLY FOR APPLIANCES) ==========
    public function storeBrand(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'country' => $request->country,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.store.index')->with('success', 'Brand added successfully!');
    }

    public function updateBrand(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        
        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'country' => $request->country,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.store.index')->with('success', 'Brand updated successfully!');
    }

    public function deleteBrand($id)
    {
        $brand = Brand::findOrFail($id);
        
        if ($brand->products()->count() > 0) {
            return back()->with('error', 'Cannot delete brand with products!');
        }
        
        $brand->delete();
        return redirect()->route('admin.store.index')->with('success', 'Brand deleted successfully!');
    }
    
    // ========== ORIGIN METHODS ==========
    public function storeOrigin(Request $request)
    {
        $request->validate([
            'country_name' => 'required|string|max:255|unique:origins',
        ]);

        Origin::create([
            'country_name' => $request->country_name,
            'flag_icon' => $request->flag_icon,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.store.index')->with('success', 'Origin added successfully!');
    }

    public function updateOrigin(Request $request, $id)
    {
        $origin = Origin::findOrFail($id);
        
        $origin->update([
            'country_name' => $request->country_name,
            'flag_icon' => $request->flag_icon,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.store.index')->with('success', 'Origin updated successfully!');
    }

    public function deleteOrigin($id)
    {
        $origin = Origin::findOrFail($id);
        
        if ($origin->products()->count() > 0) {
            return back()->with('error', 'Cannot delete origin with products!');
        }
        
        $origin->delete();
        return redirect()->route('admin.store.index')->with('success', 'Origin deleted successfully!');
    }
}