<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Session extends Model
{
    protected $table = 'race_sessions';

    protected $fillable = [
        'name',
        'type',
        'day',
        'group',
        'laps',
        'duration',
        'sponsor',
        'race_class_id',
    ];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}

