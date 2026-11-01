<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'amount',
        'qty',
        'unit',
        'date',
        'category_id',
        'description',
        'notes',
        'pelaksana',
        'receipt_path',
        'created_by',
        'voided_at',
        'void_reason',
        'locked',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'voided_at' => 'datetime',
        'locked' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(CashCategory::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('voided_at');
    }
}

