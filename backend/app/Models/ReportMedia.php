<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'path',
        'type',
    ];

    public function report()
    {
        return $this->belongsTo(FieldReport::class);
    }
}

