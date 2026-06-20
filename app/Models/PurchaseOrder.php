<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'restock_request_id',
        'created_by',
        'order_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'grand_total',
        'status',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    // ========== RELATIONSHIPS ==========

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function restockRequest()
    {
        return $this->belongsTo(RestockRequest::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // ========== ACCESSORS ==========

    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => 'Draft',
            'sent' => 'Sent to Supplier',
            'partially_received' => 'Partially Received',
            'received' => 'Received',
            'cancelled' => 'Cancelled',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'gray',
            'sent' => 'blue',
            'partially_received' => 'yellow',
            'received' => 'green',
            'cancelled' => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    /**
     * Check if the purchase order can receive more stock.
     */
    public function canReceiveStock()
    {
        return in_array($this->status, ['sent', 'partially_received']);
    }

    /**
     * Check if all items are fully received.
     */
    public function isFullyReceived()
    {
        foreach ($this->items as $item) {
            if ($item->quantity_received < $item->quantity_ordered) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the total quantity ordered across all items.
     */
    public function getTotalQuantityOrderedAttribute()
    {
        return $this->items->sum('quantity_ordered');
    }

    /**
     * Get the total quantity received across all items.
     */
    public function getTotalQuantityReceivedAttribute()
    {
        return $this->items->sum('quantity_received');
    }

    /**
     * Get the remaining quantity across all items.
     */
    public function getTotalRemainingAttribute()
    {
        return $this->total_quantity_ordered - $this->total_quantity_received;
    }

    /**
     * Get the receiving progress percentage.
     */
    public function getReceiveProgressAttribute()
    {
        if ($this->total_quantity_ordered == 0) {
            return 0;
        }
        return round(($this->total_quantity_received / $this->total_quantity_ordered) * 100);
    }

    // ========== METHODS ==========

    public function generatePONumber()
    {
        $year = date('Y');
        $lastPO = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPO) {
            $lastNumber = intval(substr($lastPO->po_number, -4));
            $sequence = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $sequence = '0001';
        }

        return "PO-{$year}-{$sequence}";
    }

    public function calculateTotals()
    {
        $subtotal = $this->items->sum('total_cost');
        $grandTotal = $subtotal + $this->tax_amount + $this->shipping_amount;

        $this->update([
            'subtotal' => $subtotal,
            'grand_total' => $grandTotal,
        ]);

        return $this;
    }

    /**
     * Update the PO status based on receiving progress.
     */
    public function updateStatusBasedOnReceiving()
    {
        if ($this->isFullyReceived()) {
            $this->update([
                'status' => 'received',
                'actual_delivery_date' => now()->toDateString(),
            ]);
            return 'received';
        } elseif ($this->total_quantity_received > 0) {
            $this->update(['status' => 'partially_received']);
            return 'partially_received';
        }
        
        return $this->status;
    }
}