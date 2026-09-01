<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanApproval extends Model
{
    protected $fillable = [
        'application_id',
        'actioned_by',
        'action',
        'message',
        'remarks',
        'actioned_at',
    ];

    protected $casts = [
        'actioned_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function actionedBy()
    {
        return $this->belongsTo(User::class, 'actioned_by');
    }
}