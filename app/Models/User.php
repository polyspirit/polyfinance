<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Flow;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password',];
    protected $hidden = ['password', 'remember_token', 'relateFlows'];
    protected $casts = ['email_verified_at' => 'datetime'];
    protected $appends = [
        'flows',
        'incomes', 'expenses', 'spendings',
        'incomes_dated', 'expenses_dated', 'spendings_dated',
    ];


    // ** RELATIONS **

    public function relateFlows()
    {
        return $this->hasMany(Flow::class);
    }


    // ** ATTRIBUTES **

    public function getFlowsAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->relateFlows->sortBy('timestamp')->values();
    }

    public function getIncomesAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->relateFlows->filter(function (Flow $flow) {
            return $flow->isIncome();
        });
    }

    public function getExpensesAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->relateFlows->filter(function (Flow $flow) {
            return $flow->isExpense();
        });
    }

    public function getSpendingsAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->relateFlows->filter(function (Flow $flow) {
            return $flow->isSpending();
        });
    }

    public function getIncomesDatedAttribute(): \Illuminate\Support\Collection
    {
        return $this->getIncomesAttribute()->groupBy(['year', 'month']);
    }

    public function getExpensesDatedAttribute(): \Illuminate\Support\Collection
    {
        return $this->getExpensesAttribute()->groupBy(['year', 'month']);
    }

    public function getSpendingsDatedAttribute(): \Illuminate\Support\Collection
    {
        return $this->getExpensesAttribute()->groupBy(['year', 'month']);
    }
}
