<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QualifyingTime extends Model
{
    protected $fillable = [
        'race_class_id',
        'day',
        'session_name',
        'car_number',
        'driver_name',
        'tx_id',
        'place',
        'laps',
        'adjust',
        'last_time',
        'fast_time',
        'fast_lap',
        'misc',
    ];

    public function raceClass(): BelongsTo
    {
        return $this->belongsTo(RaceClass::class);
    }
}

