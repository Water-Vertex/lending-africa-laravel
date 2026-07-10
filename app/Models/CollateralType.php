<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollateralType extends Model
{

    public $timestamps = false;
    protected $fillable = ['name'];

    public function collaterals()
    {
        return $this->hasMany(Collateral::class);
    }
}