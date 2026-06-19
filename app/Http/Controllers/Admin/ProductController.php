<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Origin;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand', 'images' => function ($q) {
            $q->where('is_main', true);
        }])->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        $origins = Origin::where('is_active', true)->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get(); // NEW

        return view('admin.products.create', compact('categories', 'brands', 'origins', 'suppliers')); // UPDATED
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:food,appliance',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'regular_price' => 'required|numeric|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Generate SKU
        $sku = 'PROD-'.strtoupper(Str::random(8));

        // Create product
        $product = Product::create([
            'type' => $request->type,
            'name' => $request->name,
            'slug' => Str::slug($request->name).'-'.uniqid(),
            'sku' => $sku,
            'brand_id' => $request->type == 'appliance' ? $request->brand_id : null,
            'category_id' => $request->category_id,
            'origin_id' => $request->origin_id,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->sale_price,
            'sale_start_date' => $request->sale_start_date,
            'sale_end_date' => $request->sale_end_date,
            'is_available' => $request->has('is_available'),
            'is_active' => $request->has('is_active'),
        ]);

        // Create inventory
        Inventory::create([
            'product_id' => $product->id,
            'quantity_on_hand' => $request->stock_quantity ?? 0,
            'reorder_level' => $request->reorder_level ?? 5,
        ]);

        // NEW: Sync suppliers
        if ($request->has('suppliers')) {
            $supplierData = [];
            foreach ($request->suppliers as $index => $data) {
                if (!empty($data['supplier_id'])) {
                    $supplierData[$data['supplier_id']] = [
                        'supplier_sku' => $data['supplier_sku'] ?? null,
                        'supplier_price' => $data['supplier_price'] ?? null,
                        'lead_time_days' => $data['lead_time_days'] ?? null,
                        'is_default' => isset($data['is_default']) && $data['is_default'] == '1',
                    ];
                }
            }
            if (!empty($supplierData)) {
                $product->suppliers()->sync($supplierData);
            }
        }

        // Save dynamic attributes
        if ($request->has('attributes')) {
            foreach ($request->attributes as $attribute) {
                if (! empty($attribute['key']) && ! empty($attribute['value'])) {
                    $product->attributes()->create([
                        'key' => $attribute['key'],
                        'value' => $attribute['value'],
                    ]);
                }
            }
        }

        // Upload images
        if ($request->hasFile('images')) {
            $isMain = true;
            foreach ($request->file('images') as $image) {
                if ($image && $image->isValid()) {
                    try {
                        $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                        $path = $image->storeAs('products', $filename, 'public');

                        if ($path) {
                            ProductImage::create([
                                'product_id' => $product->id,
                                'image_path' => $path,
                                'is_main' => $isMain,
                                'display_order' => 0,
                            ]);
                            $isMain = false;
                        }
                    } catch (\Exception $e) {
                        Log::error('Image upload error: '.$e->getMessage());
                    }
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function edit($id)
    {
        $product = Product::with(['attributes', 'images', 'inventory', 'suppliers'])->findOrFail($id); // UPDATED
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        $origins = Origin::where('is_active', true)->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get(); // NEW

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'origins', 'suppliers')); // UPDATED
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'regular_price' => 'required|numeric|min:0',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name).'-'.$product->id,
            'brand_id' => $product->type == 'appliance' ? $request->brand_id : null,
            'category_id' => $request->category_id,
            'origin_id' => $request->origin_id,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->sale_price,
            'sale_start_date' => $request->sale_start_date,
            'sale_end_date' => $request->sale_end_date,
            'is_available' => $request->has('is_available'),
            'is_active' => $request->has('is_active'),
        ]);

        // Update inventory
        if ($product->inventory) {
            $product->inventory->update([
                'quantity_on_hand' => $request->stock_quantity ?? 0,
                'reorder_level' => $request->reorder_level ?? 5,
            ]);
        }

        // NEW: Sync suppliers
        if ($request->has('suppliers')) {
            $supplierData = [];
            foreach ($request->suppliers as $index => $data) {
                if (!empty($data['supplier_id'])) {
                    $supplierData[$data['supplier_id']] = [
                        'supplier_sku' => $data['supplier_sku'] ?? null,
                        'supplier_price' => $data['supplier_price'] ?? null,
                        'lead_time_days' => $data['lead_time_days'] ?? null,
                        'is_default' => isset($data['is_default']) && $data['is_default'] == '1',
                    ];
                }
            }
            if (!empty($supplierData)) {
                $product->suppliers()->sync($supplierData);
            } else {
                $product->suppliers()->detach();
            }
        } else {
            $product->suppliers()->detach();
        }

        // Update attributes
        if ($request->has('attributes')) {
            $product->attributes()->delete();
            foreach ($request->attributes as $attribute) {
                if (! empty($attribute['key']) && ! empty($attribute['value'])) {
                    $product->attributes()->create([
                        'key' => $attribute['key'],
                        'value' => $attribute['value'],
                    ]);
                }
            }
        }

        // Upload new images
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                if ($image && $image->isValid()) {
                    try {
                        $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                        $path = $image->storeAs('products', $filename, 'public');

                        if ($path) {
                            ProductImage::create([
                                'product_id' => $product->id,
                                'image_path' => $path,
                                'is_main' => false,
                                'display_order' => 0,
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Image upload error: '.$e->getMessage());
                    }
                }
            }
        }

        // Delete selected images
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image) {
                    $filePath = storage_path('app/public/'.$image->image_path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $image->delete();
                }
            }
        }

        // Set main image
        if ($request->has('main_image_id')) {
            ProductImage::where('product_id', $product->id)->update(['is_main' => false]);
            ProductImage::where('id', $request->main_image_id)->update(['is_main' => true]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete all images from storage
        foreach ($product->images as $image) {
            $filePath = storage_path('app/public/'.$image->image_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}