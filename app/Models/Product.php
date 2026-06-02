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
        'sale_start_date', 'sale_end_date', 'is_available', 'is_active'
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
}