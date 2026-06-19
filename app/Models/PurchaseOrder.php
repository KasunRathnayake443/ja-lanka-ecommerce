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
            'received' => 'green',
            'cancelled' => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
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
}