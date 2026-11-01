<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportFollowup extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'user_id',
        'status',
        'solution',
    ];

    public function report()
    {
        return $this->belongsTo(FieldReport::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

