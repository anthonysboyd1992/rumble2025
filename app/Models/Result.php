<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    protected $fillable = [
        'entry_id',
        'race_session_id',
        'position',
        'time',
        'starting_position',
        'points_earned',
        'is_dns',
        'is_dnf',
    ];

    protected $casts = [
        'is_dns' => 'boolean',
        'is_dnf' => 'boolean',
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class, 'race_session_id');
    }
}

