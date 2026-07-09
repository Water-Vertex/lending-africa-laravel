<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'document_type',
        'file_path',
        'verification_status',
    ];

    const DOCUMENT_TYPES = ['national_id', 'passport', 'driver_license', 'other'];
    const VERIFICATION_STATUSES = ['pending', 'verified', 'rejected'];

    /**
     * Get the customer that owns the document.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
