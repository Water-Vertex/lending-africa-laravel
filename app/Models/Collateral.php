<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function collateralType()
    {
        return $this->belongsTo(CollateralType::class);
    }


     public function application()
    {
        return $this->belongsTo(LoanApplication::class, 'application_id');
    }
}
