<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerBankAccount extends Model
{
    // Table sirf 'created_at' rakhti hai, 'updated_at' nahi
    const UPDATED_AT = null;

    protected $fillable = [
        'customer_id',
        'bank_id',
        'account_name',
        'account_number',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}