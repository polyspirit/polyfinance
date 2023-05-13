<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public const DEFAULT_CURRENCY_CODE = 'GEL';

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
}
