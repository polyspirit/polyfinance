<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Flow;

class Tag extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['flows'];

    // ** RELATIONS **

    public function relateFlows(): BelongsToMany
    {
        return $this->belongsToMany(Flow::class);
    }


    // ** ATTRIBUTES **

    public function getFlowsAttribute(): Collection
    {
        return $this->relateFlows;
    }
}
