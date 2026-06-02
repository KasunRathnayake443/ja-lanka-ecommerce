<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSaleBanner extends Model
{
    protected $fillable = [
        'product_id', 'custom_title', 'custom_subtitle', 
        'display_order', 'is_active', 'is_manual'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_manual' => 'boolean',
        'display_order' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Get display title (custom or product name)
    public function getDisplayTitleAttribute()
    {
        return $this->custom_title ?? $this->product?->name;
    }

    // Get display subtitle (custom or auto-generated)
    public function getDisplaySubtitleAttribute()
    {
        if ($this->custom_subtitle) {
            return $this->custom_subtitle;
        }
        
        if ($this->product && $this->product->hasActiveSale()) {
            $discount = $this->product->discount_percent;
            return "🔥 {$discount}% OFF - Limited Time!";
        }
        
        return null;
    }

    // Get banner price display info
    public function getPriceInfoAttribute()
    {
        if (!$this->product) return null;
        
        if ($this->product->hasActiveSale()) {
            return [
                'regular_price' => $this->product->regular_price,
                'sale_price' => $this->product->sale_price,
                'discount_percent' => $this->product->discount_percent,
                'has_sale' => true
            ];
        }
        
        return [
            'regular_price' => $this->product->regular_price,
            'sale_price' => null,
            'discount_percent' => 0,
            'has_sale' => false
        ];
    }
}