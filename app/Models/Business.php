<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    protected $fillable = [
        'customer_id',
        'business_name',
        'registration_number',
        'tax_number',
        'business_type',
        'monthly_revenue',
        'monthly_expense',
        'address',
        'city',
        'state',
        'local_government_area',
        'status',
    ];

    protected $casts = [
        'monthly_revenue' => 'decimal:2',
        'monthly_expense' => 'decimal:2',
    ];

    const STATUSES = ['active', 'inactive'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function loanApplications(): HasMany
    {
        return $this->hasMany(LoanApplication::class);
    }
}