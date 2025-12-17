<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrossingTime extends Model
{
    protected $fillable = [
        'race_class_id',
        'day',
        'session_name',
        'index',
        'car_number',
        'driver_name',
        'trns_id',
        'lap',
        'laptime',
        'speed',
        'hits_power',
        'misc',
    ];

    public function raceClass(): BelongsTo
    {
        return $this->belongsTo(RaceClass::class);
    }
}

