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
    protected $appends = ['flows', 'incomes', 'expenses'];


    // ** RELATIONS **

    public function relateFlows()
    {
        return $this->hasMany(Flow::class);
    }


    // ** ATTRIBUTES **

    public function getFlowsAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->relateFlows;
    }

    public function getIncomesAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->relateFlows->filter(function (Flow $flow) {
            return $flow->is_income;
        });
    }

    public function getExpensesAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->relateFlows->filter(function (Flow $flow) {
            return !$flow->is_income;
        });
    }
}
