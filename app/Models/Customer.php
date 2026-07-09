<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'customer_type',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'gender',
        'national_id',
        'email',
        'phone_primary',
        'phone_secondary',
        'occupation',
        'monthly_income',
        'country',
        'state',
        'city',
        'local_government_area',
        'address',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'monthly_income' => 'decimal:2',
    ];

    const CUSTOMER_TYPES = ['personal', 'sme'];
    const GENDERS = ['male', 'female', 'other'];
    const STATUSES = ['active', 'inactive', 'blacklisted'];

    /**
     * Get the documents for the customer.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(CustomerDocument::class);
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }

    /**
     * Generate a unique customer code.
     */
    public static function generateCustomerCode(): string
    {
        $prefix = 'CUS';
        $year = date('Y');
        $lastCustomer = self::latest()->first();
        $number = $lastCustomer ? intval(substr($lastCustomer->customer_code, -4)) + 1 : 1;

        return sprintf('%s%s%04d', $prefix, $year, $number);
    }
}
