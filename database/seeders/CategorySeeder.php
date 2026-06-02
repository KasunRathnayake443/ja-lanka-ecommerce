<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // Food Categories
            ['name' => 'Japanese', 'slug' => 'japanese', 'type' => 'food', 'display_order' => 1],
            ['name' => 'Korean', 'slug' => 'korean', 'type' => 'food', 'display_order' => 2],
            ['name' => 'Italian', 'slug' => 'italian', 'type' => 'food', 'display_order' => 3],
            ['name' => 'Chinese', 'slug' => 'chinese', 'type' => 'food', 'display_order' => 4],
            ['name' => 'Thai', 'slug' => 'thai', 'type' => 'food', 'display_order' => 5],
            ['name' => 'Indian', 'slug' => 'indian', 'type' => 'food', 'display_order' => 6],
            
            // Appliance Categories
            ['name' => 'Rice Cookers', 'slug' => 'rice-cookers', 'type' => 'appliance', 'display_order' => 7],
            ['name' => 'Electric Kettles', 'slug' => 'electric-kettles', 'type' => 'appliance', 'display_order' => 8],
            ['name' => 'Microwaves', 'slug' => 'microwaves', 'type' => 'appliance', 'display_order' => 9],
            ['name' => 'Air Fryers', 'slug' => 'air-fryers', 'type' => 'appliance', 'display_order' => 10],
            ['name' => 'Blenders', 'slug' => 'blenders', 'type' => 'appliance', 'display_order' => 11],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'parent_id' => null,
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'type' => $cat['type'],
                'display_order' => $cat['display_order'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('✅ Categories seeded: ' . DB::table('categories')->count());
    }
}