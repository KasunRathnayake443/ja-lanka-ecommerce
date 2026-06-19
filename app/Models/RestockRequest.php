<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number',
        'supplier_id',
        'created_by',
        'request_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'status',
        'notes',
        'admin_notes',
    ];

    protected $casts = [
        'request_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
    ];

    // ========== RELATIONSHIPS ==========

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(RestockRequestItem::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(RestockStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function purchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::class);
    }

    // ========== ACCESSORS ==========

    public function getTotalQuantityRequestedAttribute()
    {
        return $this->items->sum('quantity_requested');
    }

    public function getTotalQuantityReceivedAttribute()
    {
        return $this->items->sum('quantity_received');
    }

    public function getIsFullyReceivedAttribute()
    {
        return $this->total_quantity_requested == $this->total_quantity_received;
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => 'Draft',
            'sent' => 'Sent to Supplier',
            'acknowledged' => 'Acknowledged',
            'ordered' => 'Ordered',
            'partially_received' => 'Partially Received',
            'received' => 'Received',
            'closed' => 'Closed',
            'cancelled' => 'Cancelled',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'gray',
            'sent' => 'blue',
            'acknowledged' => 'indigo',
            'ordered' => 'purple',
            'partially_received' => 'yellow',
            'received' => 'green',
            'closed' => 'gray',
            'cancelled' => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    // ========== SCOPES ==========

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['closed', 'cancelled']);
    }

    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    // ========== METHODS ==========

/**
 * Update the status and log the change.
 */
public function updateStatus($newStatus, $performedBy, $notes = null)
{
    $oldStatus = $this->status;
    
    $this->update(['status' => $newStatus]);
    
    // Log status change
    RestockStatusHistory::create([
        'restock_request_id' => $this->id,
        'old_status' => $oldStatus,
        'new_status' => $newStatus,
        'notes' => $notes,
        'performed_by' => $performedBy,
    ]);

    return $this;
}

    public function receiveStock($productId, $quantity, $performedBy)
    {
        $item = $this->items()->where('product_id', $productId)->first();
        
        if (!$item) {
            throw new \Exception('Product not found in this request');
        }

        $newReceived = $item->quantity_received + $quantity;
        
        if ($newReceived > $item->quantity_requested) {
            throw new \Exception('Cannot receive more than requested');
        }

        // Update item
        $item->update([
            'quantity_received' => $newReceived
        ]);

        // Update inventory
        $inventory = Inventory::where('product_id', $productId)->first();
        if ($inventory) {
            $inventory->increment('quantity_on_hand', $quantity);
        }

        // Update request status
        $totalRequested = $this->items->sum('quantity_requested');
        $totalReceived = $this->items->sum('quantity_received');

        if ($totalRequested == $totalReceived) {
            $this->updateStatus('received', $performedBy, 'Stock fully received');
        } else {
            $this->updateStatus('partially_received', $performedBy, 'Stock partially received');
        }

        return $item;
    }

    public function generateRequestNumber()
    {
        $year = date('Y');
        $lastRequest = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRequest) {
            $lastNumber = intval(substr($lastRequest->request_number, -4));
            $sequence = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $sequence = '0001';
        }

        return "RQ-{$year}-{$sequence}";
    }

    public function canBeEdited()
    {
        return in_array($this->status, ['draft', 'sent']);
    }

    public function canBeCancelled()
    {
        return !in_array($this->status, ['closed', 'cancelled']);
    }

    public function canReceiveStock()
    {
        return in_array($this->status, ['ordered', 'partially_received']);
    }
}