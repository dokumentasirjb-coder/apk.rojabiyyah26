<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'reporter_id',
        'status',
        'followup_status',
        'description',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function media()
    {
        return $this->hasMany(ReportMedia::class, 'report_id');
    }

    public function followups()
    {
        return $this->hasMany(ReportFollowup::class, 'report_id');
    }
}

