<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'total',
        'success',
        'failed',
        'report_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

