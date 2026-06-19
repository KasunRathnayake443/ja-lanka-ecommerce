<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'restock_request_id',
        'product_id',
        'quantity_requested',
        'quantity_received',
        'unit_cost',
        'total_cost',
        'supplier_sku',
        'notes',
    ];

    protected $casts = [
        'quantity_requested' => 'integer',
        'quantity_received' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    // ========== RELATIONSHIPS ==========

    public function restockRequest()
    {
        return $this->belongsTo(RestockRequest::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ========== ACCESSORS ==========

    public function getRemainingQuantityAttribute()
    {
        return $this->quantity_requested - $this->quantity_received;
    }

    public function getIsFullyReceivedAttribute()
    {
        return $this->quantity_received >= $this->quantity_requested;
    }

    // ========== METHODS ==========

    public function calculateTotalCost()
    {
        if ($this->unit_cost) {
            $this->update([
                'total_cost' => $this->unit_cost * $this->quantity_requested
            ]);
        }
    }
}