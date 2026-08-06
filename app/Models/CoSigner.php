<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoSigner extends Model
{
    protected $fillable = [
        'application_id',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'occupation',
        'evidence_of_occupation',
        'email',
        'phone_primary',
        'phone_secondary',
        'address',
        'city',
        'state',
        'country',
        'bvn',
        'photo_id',
        'relationship',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class, 'application_id');
    }

}

