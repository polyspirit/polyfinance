<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Flow extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = ['is_income' => 'boolean'];


    // RELATIONS

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
