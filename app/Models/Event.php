<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['event_number', 'message'];

    public static function current(): self
    {
        return static::firstOrCreate([], ['event_number' => 1]);
    }
}
