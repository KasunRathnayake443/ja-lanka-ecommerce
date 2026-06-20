<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockStatusHistory extends Model
{
    use HasFactory;
    protected $table = 'restock_status_history'; 
    protected $fillable = [
        'restock_request_id',
        'old_status',
        'new_status',
        'notes',
        'performed_by',
    ];

    // ========== RELATIONSHIPS ==========

    public function restockRequest()
    {
        return $this->belongsTo(RestockRequest::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(Admin::class, 'performed_by');
    }

    // ========== ACCESSORS ==========

    public function getOldStatusLabelAttribute()
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

        return $labels[$this->old_status] ?? ucfirst($this->old_status);
    }

    public function getNewStatusLabelAttribute()
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

        return $labels[$this->new_status] ?? ucfirst($this->new_status);
    }
}