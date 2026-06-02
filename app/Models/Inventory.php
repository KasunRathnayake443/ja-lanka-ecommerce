<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = ['product_id', 'quantity_on_hand', 'quantity_sold', 'quantity_reserved', 'reorder_level'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}