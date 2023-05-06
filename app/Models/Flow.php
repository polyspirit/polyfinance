<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\Currency;

class Flow extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'relateUser', 'relateCurrency'];
    protected $casts = ['is_income' => 'boolean'];
    protected $appends = ['date', 'currency'];


    // ** RELATIONS **

    public function relateUser()
    {
        return $this->belongsTo(User::class);
    }

    public function relateCurrency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }


    // ** ATTRIBUTES **

    public function getDateAttribute(): string
    {
        return Carbon::createFromDate($this->created_at)->format('d.m.Y H:i:s');
    }

    public function getCurrencyAttribute(): Currency|null
    {
        return $this->relateCurrency;
    }
}
