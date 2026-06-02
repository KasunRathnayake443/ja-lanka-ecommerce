<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            ['name' => 'Zojirushi', 'slug' => 'zojirushi', 'country' => 'Japan', 'type' => 'appliance'],
            ['name' => 'Tiger', 'slug' => 'tiger', 'country' => 'Japan', 'type' => 'appliance'],
            ['name' => 'Panasonic', 'slug' => 'panasonic', 'country' => 'Japan', 'type' => 'appliance'],
            ['name' => 'Sharp', 'slug' => 'sharp', 'country' => 'Japan', 'type' => 'appliance'],
            ['name' => 'Philips', 'slug' => 'philips', 'country' => 'Netherlands', 'type' => 'appliance'],
            ['name' => 'Nissin', 'slug' => 'nissin', 'country' => 'Japan', 'type' => 'food'],
            ['name' => 'Meiji', 'slug' => 'meiji', 'country' => 'Japan', 'type' => 'food'],
            ['name' => 'Ito En', 'slug' => 'ito-en', 'country' => 'Japan', 'type' => 'food'],
            ['name' => 'Glico', 'slug' => 'glico', 'country' => 'Japan', 'type' => 'food'],
            ['name' => 'CJ', 'slug' => 'cj', 'country' => 'Korea', 'type' => 'food'],
            ['name' => 'Samyang', 'slug' => 'samyang', 'country' => 'Korea', 'type' => 'food'],
            ['name' => 'Barilla', 'slug' => 'barilla', 'country' => 'Italy', 'type' => 'food'],
            ['name' => 'Lavazza', 'slug' => 'lavazza', 'country' => 'Italy', 'type' => 'food'],
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->insert([
                'name' => $brand['name'],
                'slug' => $brand['slug'],
                'logo' => null,
                'country' => $brand['country'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('✅ Brands seeded: ' . DB::table('brands')->count());
    }
}