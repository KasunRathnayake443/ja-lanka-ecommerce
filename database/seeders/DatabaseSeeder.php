<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🌱 Starting database seeding...');
        
        // Run seeders in correct order
        $this->call(CategorySeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(OriginSeeder::class);
        
        // Now seed products
        $this->seedProducts();
        
        // Seed flash sale banners
        $this->seedFlashSaleBanners();
        
        // Create admin user if not exists
        $this->seedAdmin();
        
        $this->command->info('✅ Database seeding completed!');
    }
    
    private function seedProducts()
    {
        $this->command->info('📦 Seeding products...');
        
        // Get IDs
        $japanId = DB::table('origins')->where('country_name', 'Japan')->value('id');
        $koreaId = DB::table('origins')->where('country_name', 'Korea')->value('id');
        $italyId = DB::table('origins')->where('country_name', 'Italy')->value('id');
        $chinaId = DB::table('origins')->where('country_name', 'China')->value('id');
        
        $japaneseCatId = DB::table('categories')->where('slug', 'japanese')->value('id');
        $koreanCatId = DB::table('categories')->where('slug', 'korean')->value('id');
        $italianCatId = DB::table('categories')->where('slug', 'italian')->value('id');
        $chineseCatId = DB::table('categories')->where('slug', 'chinese')->value('id');
        $riceCookerCatId = DB::table('categories')->where('slug', 'rice-cookers')->value('id');
        $kettleCatId = DB::table('categories')->where('slug', 'electric-kettles')->value('id');
        $microwaveCatId = DB::table('categories')->where('slug', 'microwaves')->value('id');
        $airFryerCatId = DB::table('categories')->where('slug', 'air-fryers')->value('id');
        $blenderCatId = DB::table('categories')->where('slug', 'blenders')->value('id');
        
        // Get brand IDs
        $zojirushiId = DB::table('brands')->where('slug', 'zojirushi')->value('id');
        $tigerId = DB::table('brands')->where('slug', 'tiger')->value('id');
        $panasonicId = DB::table('brands')->where('slug', 'panasonic')->value('id');
        $philipsId = DB::table('brands')->where('slug', 'philips')->value('id');
        $nissinId = DB::table('brands')->where('slug', 'nissin')->value('id');
        $meijiId = DB::table('brands')->where('slug', 'meiji')->value('id');
        $itoEnId = DB::table('brands')->where('slug', 'ito-en')->value('id');
        $glicoId = DB::table('brands')->where('slug', 'glico')->value('id');
        $cjId = DB::table('brands')->where('slug', 'cj')->value('id');
        $samyangId = DB::table('brands')->where('slug', 'samyang')->value('id');
        $barillaId = DB::table('brands')->where('slug', 'barilla')->value('id');
        $lavazzaId = DB::table('brands')->where('slug', 'lavazza')->value('id');
        
        $products = [
            // Japanese Food
            ['type' => 'food', 'name' => 'Japanese Matcha Green Tea', 'slug' => 'japanese-matcha-green-tea', 'sku' => 'JPN-FOOD-001', 'brand_id' => $itoEnId, 'category_id' => $japaneseCatId, 'origin_id' => $japanId, 'short_description' => 'Premium matcha from Uji, Japan', 'description' => 'Authentic Japanese matcha green tea powder.', 'regular_price' => 2990, 'sale_price' => 2490, 'stock' => 50],
            ['type' => 'food', 'name' => 'Nissin Cup Noodles', 'slug' => 'nissin-cup-noodles', 'sku' => 'JPN-FOOD-002', 'brand_id' => $nissinId, 'category_id' => $japaneseCatId, 'origin_id' => $japanId, 'short_description' => 'Instant cup noodles', 'description' => 'Classic Japanese instant noodles.', 'regular_price' => 450, 'sale_price' => null, 'stock' => 200],
            ['type' => 'food', 'name' => 'Pocky Chocolate Sticks', 'slug' => 'pocky-chocolate-sticks', 'sku' => 'JPN-FOOD-003', 'brand_id' => $glicoId, 'category_id' => $japaneseCatId, 'origin_id' => $japanId, 'short_description' => 'Chocolate biscuit sticks', 'description' => 'Famous Japanese snack.', 'regular_price' => 380, 'sale_price' => null, 'stock' => 150],
            ['type' => 'food', 'name' => 'Meiji Hello Panda', 'slug' => 'meiji-hello-panda', 'sku' => 'JPN-FOOD-004', 'brand_id' => $meijiId, 'category_id' => $japaneseCatId, 'origin_id' => $japanId, 'short_description' => 'Chocolate filled biscuits', 'description' => 'Panda-shaped biscuits.', 'regular_price' => 420, 'sale_price' => 350, 'stock' => 120],
            // Korean Food
            ['type' => 'food', 'name' => 'Korean Kimchi', 'slug' => 'korean-kimchi', 'sku' => 'KOR-FOOD-001', 'brand_id' => $cjId, 'category_id' => $koreanCatId, 'origin_id' => $koreaId, 'short_description' => 'Authentic kimchi', 'description' => 'Traditional fermented cabbage.', 'regular_price' => 890, 'sale_price' => null, 'stock' => 80],
            ['type' => 'food', 'name' => 'Samyang Buldak Ramen', 'slug' => 'samyang-buldak-ramen', 'sku' => 'KOR-FOOD-002', 'brand_id' => $samyangId, 'category_id' => $koreanCatId, 'origin_id' => $koreaId, 'short_description' => 'Extra spicy ramen', 'description' => 'Famous Korean fire noodles.', 'regular_price' => 550, 'sale_price' => 490, 'stock' => 100],
            // Italian Food
            ['type' => 'food', 'name' => 'Barilla Spaghetti', 'slug' => 'barilla-spaghetti', 'sku' => 'ITA-FOOD-001', 'brand_id' => $barillaId, 'category_id' => $italianCatId, 'origin_id' => $italyId, 'short_description' => 'Premium pasta', 'description' => 'Authentic Italian spaghetti.', 'regular_price' => 490, 'sale_price' => 399, 'stock' => 300],
            ['type' => 'food', 'name' => 'Lavazza Coffee', 'slug' => 'lavazza-coffee', 'sku' => 'ITA-FOOD-002', 'brand_id' => $lavazzaId, 'category_id' => $italianCatId, 'origin_id' => $italyId, 'short_description' => 'Italian espresso', 'description' => 'Premium espresso coffee.', 'regular_price' => 2990, 'sale_price' => null, 'stock' => 40],
            // Chinese Food
            ['type' => 'food', 'name' => 'Dim Sum Set', 'slug' => 'dim-sum-set', 'sku' => 'CHN-FOOD-001', 'brand_id' => null, 'category_id' => $chineseCatId, 'origin_id' => $chinaId, 'short_description' => 'Frozen dim sum', 'description' => 'Assorted Chinese dim sum.', 'regular_price' => 1890, 'sale_price' => 1590, 'stock' => 60],
            // Appliances
            ['type' => 'appliance', 'name' => 'Zojirushi Rice Cooker', 'slug' => 'zojirushi-rice-cooker', 'sku' => 'APL-001', 'brand_id' => $zojirushiId, 'category_id' => $riceCookerCatId, 'origin_id' => $japanId, 'short_description' => 'Premium rice cooker', 'description' => 'Japanese rice cooker.', 'regular_price' => 89900, 'sale_price' => 79900, 'stock' => 15],
            ['type' => 'appliance', 'name' => 'Tiger Electric Kettle', 'slug' => 'tiger-kettle', 'sku' => 'APL-002', 'brand_id' => $tigerId, 'category_id' => $kettleCatId, 'origin_id' => $japanId, 'short_description' => 'Electric kettle', 'description' => 'Stainless steel kettle.', 'regular_price' => 12900, 'sale_price' => 9990, 'stock' => 25],
            ['type' => 'appliance', 'name' => 'Panasonic Microwave', 'slug' => 'panasonic-microwave', 'sku' => 'APL-003', 'brand_id' => $panasonicId, 'category_id' => $microwaveCatId, 'origin_id' => $japanId, 'short_description' => 'Microwave oven', 'description' => 'Inverter microwave.', 'regular_price' => 44900, 'sale_price' => 39900, 'stock' => 10],
            ['type' => 'appliance', 'name' => 'Philips Air Fryer', 'slug' => 'philips-air-fryer', 'sku' => 'APL-004', 'brand_id' => $philipsId, 'category_id' => $airFryerCatId, 'origin_id' => null, 'short_description' => 'Air fryer', 'description' => 'Healthy frying.', 'regular_price' => 39900, 'sale_price' => null, 'stock' => 20],
            ['type' => 'appliance', 'name' => 'Ninja Blender', 'slug' => 'ninja-blender', 'sku' => 'APL-005', 'brand_id' => null, 'category_id' => $blenderCatId, 'origin_id' => null, 'short_description' => 'Professional blender', 'description' => '1000W blender.', 'regular_price' => 24900, 'sale_price' => 19900, 'stock' => 12],
        ];
        
        foreach ($products as $product) {
            $productId = DB::table('products')->insertGetId([
                'type' => $product['type'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'sku' => $product['sku'],
                'brand_id' => $product['brand_id'],
                'category_id' => $product['category_id'],
                'origin_id' => $product['origin_id'],
                'short_description' => $product['short_description'],
                'description' => $product['description'],
                'regular_price' => $product['regular_price'],
                'sale_price' => $product['sale_price'],
                'sale_start_date' => null,
                'sale_end_date' => null,
                'is_available' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            DB::table('inventories')->insert([
                'product_id' => $productId,
                'quantity_on_hand' => $product['stock'],
                'quantity_sold' => 0,
                'quantity_reserved' => 0,
                'reorder_level' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('✅ Products seeded: ' . DB::table('products')->count());
    }
    
    private function seedFlashSaleBanners()
    {
        $this->command->info('🔥 Seeding flash sale banners...');
        
        $saleProducts = DB::table('products')->whereNotNull('sale_price')->get();
        
        $displayOrder = 0;
        foreach ($saleProducts as $product) {
            DB::table('flash_sale_banners')->insert([
                'product_id' => $product->id,
                'custom_title' => null,
                'custom_subtitle' => null,
                'display_order' => $displayOrder++,
                'is_active' => 0,
                'is_manual' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('✅ Flash sale banners seeded: ' . DB::table('flash_sale_banners')->count());
    }
    
    private function seedAdmin()
    {
        $exists = DB::table('admins')->where('email', 'admin@jalanka.com')->exists();
        
        if (!$exists) {
            DB::table('admins')->insert([
                'name' => 'Super Admin',
                'email' => 'admin@jalanka.com',
                'password' => bcrypt('password123'),
                'is_super_admin' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('✅ Admin user created: admin@jalanka.com / password123');
        }
    }
}