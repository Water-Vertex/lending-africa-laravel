<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerByStaff extends Model
{
    use HasFactory;

    protected $table = 'customer_by_staff';

    protected $fillable = [
        'customer_id',
        'customer_code',
        'staff_id',
        'staff_code'
    ];

    // Relation with Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Relation with Staff
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}