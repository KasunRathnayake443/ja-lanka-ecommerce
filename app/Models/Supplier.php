<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'mobile',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'website',
        'tax_number',
        'payment_terms',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get the products for this supplier.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_supplier')
            ->withPivot('supplier_sku', 'supplier_price', 'lead_time_days', 'is_default')
            ->withTimestamps();
    }

    /**
     * Get the default products for this supplier.
     */
    public function defaultProducts()
    {
        return $this->belongsToMany(Product::class, 'product_supplier')
            ->withPivot('supplier_sku', 'supplier_price', 'lead_time_days', 'is_default')
            ->wherePivot('is_default', true)
            ->withTimestamps();
    }

    /**
     * Get the restock requests for this supplier.
     */
    public function restockRequests()
    {
        return $this->hasMany(RestockRequest::class);
    }

    // ========== ACCESSORS ==========

    /**
     * Get the full address for display.
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get the formatted contact info.
     */
    public function getContactInfoAttribute()
    {
        $info = [];
        if ($this->contact_person) {
            $info[] = $this->contact_person;
        }
        if ($this->phone) {
            $info[] = $this->phone;
        }
        if ($this->email) {
            $info[] = $this->email;
        }
        return implode(' | ', $info);
    }

    // ========== SCOPES ==========

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('contact_person', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%");
    }
}