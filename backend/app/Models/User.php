<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'member_no',
        'name',
        'email',
        'password',
        'role',
        'registration_source',
        'phone_wa',
        'is_active',
        'must_change_password',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'borrower_id');
    }

    public function fieldReports()
    {
        return $this->hasMany(FieldReport::class, 'reporter_id');
    }

    public function cashTransactions()
    {
        return $this->hasMany(CashTransaction::class, 'created_by');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    // Role checks
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isBendahara(): bool
    {
        return $this->role === 'bendahara';
    }

    public function isKetuaDivisi(): bool
    {
        return $this->role === 'ketua_divisi';
    }

    public function isPicPosko(): bool
    {
        return $this->role === 'pic_posko';
    }

    public function isAnggota(): bool
    {
        return $this->role === 'anggota';
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }
}
