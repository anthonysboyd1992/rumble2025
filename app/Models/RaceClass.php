<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RaceClass extends Model
{
    protected $fillable = ['name', 'sort_order'];

    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }
}

