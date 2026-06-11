<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'guest_email',
        'guest_name',
        'guest_mobile',
        'total_amount',
        'subtotal',
        'discount_amount',
        'shipping_amount',
        'tax_amount',
        'grand_total',
        'coupon_code',
        'coupon_discount',
        'shipping_address_id',
        'billing_address_id',
        'shipping_method',
        'payment_method',
        'payment_status',
        'order_status',
        'tracking_number',
        'delivery_date',
        'notes',
        'placed_at'
    ];

    protected $casts = [
        'placed_at' => 'datetime',
        'delivery_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }
}