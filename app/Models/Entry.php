<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entry extends Model
{
    protected $fillable = [
        'car_number',
        'driver_name',
        'team_name',
        'tire_info',
        'race_class_id',
    ];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function getTotalPointsAttribute(): int
    {
        return $this->results->sum('points_earned');
    }

    public function getFridayPointsAttribute(): int
    {
        return $this->results()
            ->whereHas('session', fn($q) => $q->where('day', 'friday'))
            ->sum('points_earned');
    }
}

