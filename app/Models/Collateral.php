<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Collateral extends Model
{
    protected $fillable = [
        'application_id',
        'collateral_type_id',
        'asset_name',
        'estimated_value',
        'ownership_document_no',
        'verification_status',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
    ];

    const VERIFICATION_STATUSES = ['pending', 'verified', 'rejected'];

    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class, 'application_id');
    }

    public function collateralType(): BelongsTo
    {
        return $this->belongsTo(CollateralType::class);
    }
}