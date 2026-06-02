<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Origin;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Get existing IDs
        $categoryId = Category::first()->id ?? 1;
        $brandId = Brand::first()->id ?? 1;
        $originId = Origin::first()->id ?? 1;
        
        $products = [
            [
                'type' => 'food',
                'name' => 'Japanese Matcha Green Tea',
                'slug' => 'japanese-matcha-green-tea',
                'sku' => 'TEA-001',
                'brand_id' => null,
                'category_id' => $categoryId,
                'origin_id' => $originId,
                'short_description' => 'Premium ceremonial grade matcha from Uji, Japan',
                'description' => 'Authentic Japanese matcha green tea powder. Perfect for tea ceremonies and baking.',
                'regular_price' => 2990,
                'sale_price' => 2490,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'type' => 'food',
                'name' => 'Korean Kimchi',
                'slug' => 'korean-kimchi',
                'sku' => 'KOR-001',
                'brand_id' => null,
                'category_id' => $categoryId,
                'origin_id' => $originId,
                'short_description' => 'Authentic Korean fermented cabbage',
                'description' => 'Traditional Korean kimchi made with napa cabbage, radish, and special seasonings.',
                'regular_price' => 890,
                'sale_price' => null,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'type' => 'appliance',
                'name' => 'Zojirushi Rice Cooker',
                'slug' => 'zojirushi-rice-cooker',
                'sku' => 'APL-001',
                'brand_id' => $brandId,
                'category_id' => $categoryId,
                'origin_id' => $originId,
                'short_description' => 'Premium Japanese rice cooker',
                'description' => 'Zojirushi Neuro Fuzzy rice cooker with advanced technology for perfect rice every time.',
                'regular_price' => 89900,
                'sale_price' => 79900,
                'is_available' => true,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);
            
            // Create inventory
            Inventory::create([
                'product_id' => $product->id,
                'quantity_on_hand' => 50,
                'quantity_sold' => 0,
                'quantity_reserved' => 0,
                'reorder_level' => 10,
            ]);
            
            // Create placeholder image (optional - you can skip if no images)
            // ProductImage::create([
            //     'product_id' => $product->id,
            //     'image_path' => 'products/placeholder.jpg',
            //     'is_main' => true,
            // ]);
        }
        
        $this->command->info('Products seeded successfully!');
    }
}