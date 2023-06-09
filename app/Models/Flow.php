<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\User;
use App\Models\Currency;
use App\Models\Tag;

class Flow extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'relateUser', 'relateCurrency'];
    protected $appends = ['date', 'year', 'month', 'day', 'currency'];
    protected $casts = ['amount' => 'float'];

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

    public function relateTags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }


    // ** ATTRIBUTES **

    public function getDateAttribute(): string
    {
        return Carbon::createFromDate($this->created_at)->format('d.m.Y H:i:s');
    }

    public function getTimestampAttribute(): string
    {
        return Carbon::createFromDate($this->created_at)->timestamp;
    }

    public function getYearAttribute(): string
    {
        return Carbon::createFromDate($this->created_at)->year;
    }

    public function getMonthAttribute(): string
    {
        return Carbon::createFromDate($this->created_at)->month;
    }

    public function getDayAttribute(): string
    {
        return Carbon::createFromDate($this->created_at)->day;
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
