<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'role_id',
        'branch',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

   public function role()
    {
        return $this->belongsTo(Role::class);
    }

  
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // ✅ Permission check helper
    public function hasPermission(string $permission): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()->where('name', $permission)->exists();
    }

    // ✅ Multiple permissions check (any one matched)
    public function hasAnyPermission(array $permissions): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()->whereIn('name', $permissions)->exists();
    }

    // ✅ Get all permission names of this user's role (Angular ko bhejne ke liye)
    public function getPermissionNames(): array
    {
        if (!$this->role) {
            return [];
        }

        return $this->role->permissions()->pluck('name')->toArray();
    }
}