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

    // ========== CORE RELATIONSHIPS ==========

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

    // ========== SALE / PRICING ==========

    public function hasActiveSale()
    {
        if (!$this->sale_price || $this->sale_price >= $this->regular_price) {
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

    // ========== SUPPLIER RELATIONSHIPS ==========

    /**
     * Relationship: all suppliers for this product (safe for eager loading).
     */
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier')
            ->withPivot('supplier_sku', 'supplier_price', 'lead_time_days', 'is_default')
            ->withTimestamps();
    }

    /**
     * Relationship: only the default supplier (safe for eager loading
     * via ->with('defaultSupplierRelation') — does NOT execute the query itself).
     */
    public function defaultSupplierRelation()
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier')
            ->withPivot('supplier_sku', 'supplier_price', 'lead_time_days', 'is_default')
            ->wherePivot('is_default', true)
            ->withTimestamps();
    }

    /**
     * Accessor: $product->default_supplier
     * Executes the query and returns a Supplier model or null.
     * DO NOT use this name inside ->with([...]) — use defaultSupplierRelation() instead.
     */
    public function getDefaultSupplierAttribute()
    {
        return $this->defaultSupplierRelation()->first();
    }

    /**
     * Check if product has any supplier linked.
     */
    public function hasSupplier()
    {
        return $this->suppliers()->exists();
    }

    // ========== RESTOCK RELATIONSHIPS ==========

    public function restockRequestItems()
    {
        return $this->hasMany(RestockRequestItem::class);
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function getTotalRestockRequestedAttribute()
    {
        return $this->restockRequestItems()->sum('quantity_requested');
    }

    public function getTotalRestockReceivedAttribute()
    {
        return $this->restockRequestItems()->sum('quantity_received');
    }
}