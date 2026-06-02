<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OriginSeeder extends Seeder
{
    public function run()
    {
        $origins = [
            ['country_name' => 'Japan', 'flag_icon' => '🇯🇵'],
            ['country_name' => 'Korea', 'flag_icon' => '🇰🇷'],
            ['country_name' => 'Italy', 'flag_icon' => '🇮🇹'],
            ['country_name' => 'China', 'flag_icon' => '🇨🇳'],
            ['country_name' => 'Thailand', 'flag_icon' => '🇹🇭'],
            ['country_name' => 'India', 'flag_icon' => '🇮🇳'],
            ['country_name' => 'Vietnam', 'flag_icon' => '🇻🇳'],
        ];

        foreach ($origins as $origin) {
            DB::table('origins')->insert([
                'country_name' => $origin['country_name'],
                'flag_icon' => $origin['flag_icon'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('✅ Origins seeded: ' . DB::table('origins')->count());
    }
}