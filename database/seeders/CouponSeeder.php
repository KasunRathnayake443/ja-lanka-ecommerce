<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run()
    {
        Coupon::create([
            'code' => 'WELCOME10',
            'description' => '10% off on first order',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'minimum_order' => 1000,
            'maximum_discount' => 500,
            'usage_limit' => 100,
            'usage_limit_per_user' => 1,
            'starts_at' => now(),
            'expires_at' => now()->addMonths(6),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'FREESHIP',
            'description' => 'Free shipping on orders over LKR 5000',
            'discount_type' => 'fixed',
            'discount_value' => 350,
            'minimum_order' => 5000,
            'usage_limit' => 50,
            'usage_limit_per_user' => 1,
            'starts_at' => now(),
            'expires_at' => now()->addMonths(3),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'SAVE20',
            'description' => 'LKR 200 off on orders over LKR 2000',
            'discount_type' => 'fixed',
            'discount_value' => 2000,
            'minimum_order' => 2000,
            'usage_limit' => 200,
            'usage_limit_per_user' => 2,
            'starts_at' => now(),
            'expires_at' => now()->addMonths(1),
            'is_active' => true,
        ]);
    }
}