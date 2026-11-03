<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'user_id',
        'role_in_post',
        'duty_name',
        'category',
        'execution_time',
        'specific_location',
        'description',
        'start_at',
        'end_at',
        'notif_assigned_at',
        'notif_10m_at',
        'notif_start_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'notif_assigned_at' => 'datetime',
        'notif_10m_at' => 'datetime',
        'notif_start_at' => 'datetime',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

