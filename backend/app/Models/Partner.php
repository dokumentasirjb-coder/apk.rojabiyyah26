<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'phone',
        'description',
        'return_due_date',
    ];

    protected $casts = [
        'return_due_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}

