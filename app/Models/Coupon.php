<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'description', 'discount_type', 'discount_value',
        'minimum_order', 'maximum_discount', 'usage_limit', 'used_count',
        'usage_limit_per_user', 'starts_at', 'expires_at', 'is_active'
    ];

    protected $casts = [
        'starts_at' => 'date',
        'expires_at' => 'date',
        'is_active' => 'boolean',
        'minimum_order' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'used_count' => 'integer',
    ];

    // ========== RELATIONSHIPS ==========
    
    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    // ========== METHODS ==========
    
    // Check if coupon is valid
    public function isValid($subtotal, $userId = null)
    {
        $now = now();
        
        // Check if active
        if (!$this->is_active) return false;
        
        // Check date range
        if ($this->starts_at && $this->starts_at > $now) return false;
        if ($this->expires_at && $this->expires_at < $now) return false;
        
        // Check minimum order
        if ($this->minimum_order > 0 && $subtotal < $this->minimum_order) return false;
        
        // Check usage limit
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        
        // Check per user limit
        if ($userId && $this->usage_limit_per_user > 0) {
            $userUsage = CouponUsage::where('coupon_id', $this->id)
                ->where('user_id', $userId)
                ->count();
            if ($userUsage >= $this->usage_limit_per_user) return false;
        }
        
        return true;
    }

    // Calculate discount amount
    public function calculateDiscount($subtotal)
    {
        $discount = 0;
        
        if ($this->discount_type === 'percentage') {
            $discount = ($subtotal * $this->discount_value) / 100;
        } else {
            $discount = $this->discount_value;
        }
        
        // Apply maximum discount limit
        if ($this->maximum_discount && $discount > $this->maximum_discount) {
            $discount = $this->maximum_discount;
        }
        
        // Don't discount more than subtotal
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }
        
        return round($discount, 2);
    }

    // Apply coupon usage
    public function applyUsage($userId, $orderId, $discountAmount)
    {
        $this->increment('used_count');
        
        CouponUsage::create([
            'coupon_id' => $this->id,
            'user_id' => $userId,
            'order_id' => $orderId,
            'discount_amount' => $discountAmount,
        ]);
    }
}