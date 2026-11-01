<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldDef extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity',
        'label',
        'type',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function values()
    {
        return $this->hasMany(CustomFieldValue::class, 'def_id');
    }
}

