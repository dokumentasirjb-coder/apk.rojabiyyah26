<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'source',
        'partner_id',
        'category_id',
        'qty_total',
        'qty_available',
        'qty_reserved',
        'qty_damaged',
        'condition',
        'photo_path',
        'location',
        'kelengkapan',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }

    public function recalculateAvailableStock(): void
    {
        // Available = total - reserved - damaged
        $available = $this->qty_total - $this->qty_reserved - $this->qty_damaged;
        $this->qty_available = max(0, $available);
        $this->save();
    }
}

