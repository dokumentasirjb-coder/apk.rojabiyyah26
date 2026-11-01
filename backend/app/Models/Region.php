<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'pic_user_id',
        'description',
    ];

    public function picUser()
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function fieldReports()
    {
        return $this->hasMany(FieldReport::class);
    }
}

