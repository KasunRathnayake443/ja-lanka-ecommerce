<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'body',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    // ========== SCOPES ==========

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ========== METHODS ==========

    public function parseBody($data)
    {
        $body = $this->body;
        
        foreach ($data as $key => $value) {
            $body = str_replace("{{ $key }}", $value, $body);
        }
        
        return $body;
    }

    public function parseSubject($data)
    {
        $subject = $this->subject;
        
        foreach ($data as $key => $value) {
            $subject = str_replace("{{ $key }}", $value, $subject);
        }
        
        return $subject;
    }
}