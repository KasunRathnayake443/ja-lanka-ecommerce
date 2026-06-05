<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Origin extends Model
{
    protected $fillable = ['country_name', 'flag_icon', 'is_active'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
