<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RaceClass extends Model
{
    protected $fillable = ['name', 'sort_order', 'show_on_leaderboard', 'show_on_practice'];

    protected $casts = [
        'show_on_leaderboard' => 'boolean',
        'show_on_practice' => 'boolean',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }
}

