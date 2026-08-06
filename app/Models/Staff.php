<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Staff extends Authenticatable
{
    use Notifiable,HasApiTokens;

    protected $table = 'staff';

    protected $fillable = [
        'staff_code', 'bank_id', 'branch_name', 'first_name', 'last_name',
        'email', 'phone', 'designation', 'employee_id', 'password', 'status','created_by',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

     public function customers()
    {
        return $this->hasMany(CustomerByStaff::class);
    }


     public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
