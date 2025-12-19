<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventMessage extends Model
{
    protected $fillable = ['text', 'sort_order'];

    protected $table = 'event_messages';
}
