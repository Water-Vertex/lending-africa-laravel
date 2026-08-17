<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanApplication extends Model
{
    protected $fillable = [
        'application_no',
        'customer_id',
        'business_id',
        'loan_product_id',
        'loan_amount',
        'duration_months',
        'purpose',
        'status',
        'remarks',
        'application_date',
    ];

    protected $casts = [
        'loan_amount'      => 'decimal:2',
        'application_date' => 'date',
    ];

    const STATUSES = ['draft', 'submitted', 'under_review', 'approved', 'rejected', 'disbursed', 'closed'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function loanProduct(): BelongsTo
    {
        return $this->belongsTo(LoanProduct::class);
    }

    public static function generateApplicationNo(): string
    {
        $prefix = 'LN';
        $year   = date('Y');
        $last   = self::latest('id')->first();
        $number = $last ? intval(substr($last->application_no, -5)) + 1 : 1;

        return sprintf('%s%s%05d', $prefix, $year, $number);
    }
    public function collaterals(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(Collateral::class, 'application_id');
}
public function coSigners(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(CoSigner::class, 'application_id');
}

// app/Models/LoanApplication.php mein yeh relation add karo

public function customerByStaff()
{
    return $this->hasOneThrough(
        \App\Models\CustomerByStaff::class,
        \App\Models\Customer::class,
        'id',           // customers.id
        'customer_id',  // customer_by_staff.customer_id
        'customer_id',  // loan_applications.customer_id
        'id'            // customers.id
    );
}

}