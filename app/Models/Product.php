<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type', 'name', 'slug', 'sku', 'brand_id', 'category_id', 'origin_id',
        'short_description', 'description', 'regular_price', 'sale_price',
        'sale_start_date', 'sale_end_date', 'is_available', 'is_active',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function origin()
    {
        return $this->belongsTo(Origin::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    // Add these methods to your Product model

    public function hasActiveSale()
    {
        if (! $this->sale_price || $this->sale_price >= $this->regular_price) {
            return false;
        }

        $now = now();

        if ($this->sale_start_date && $this->sale_start_date > $now) {
            return false;
        }

        if ($this->sale_end_date && $this->sale_end_date < $now) {
            return false;
        }

        return true;
    }

    public function getDiscountPercentAttribute()
    {
        if ($this->hasActiveSale()) {
            return round((($this->regular_price - $this->sale_price) / $this->regular_price) * 100);
        }

        return 0;
    }

    public function getCurrentPriceAttribute()
    {
        return $this->hasActiveSale() ? $this->sale_price : $this->regular_price;
    }
}
