<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\Currency;

use App\Enums\FlowType;

class Flow extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'relateUser', 'relateCurrency'];
    protected $appends = ['date', 'currency'];

    // TODO: make in emum
    private const TYPES = ['income', 'expense', 'spending'];


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


    // ** TYPES **

    public function isIncome()
    {
        return $this->type === 'income';
    }

    public function isExpense()
    {
        return $this->type === 'expense';
    }

    public function isSpending()
    {
        return $this->type === 'spending';
    }
}
