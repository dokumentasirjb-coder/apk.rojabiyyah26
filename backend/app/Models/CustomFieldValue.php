<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'def_id',
        'entity_id',
        'value',
    ];

    public function definition()
    {
        return $this->belongsTo(CustomFieldDef::class, 'def_id');
    }
}

