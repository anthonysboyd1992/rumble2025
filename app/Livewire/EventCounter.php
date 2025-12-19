<?php

namespace App\Livewire;

use App\Models\Event;
use Livewire\Component;

class EventCounter extends Component
{
    public function increment(): void
    {
        $event = Event::current();
        $event->increment('event_number');
        $this->dispatch('event-updated');
    }

    public function decrement(): void
    {
        $event = Event::current();
        if ($event->event_number > 1) {
            $event->decrement('event_number');
            $this->dispatch('event-updated');
        }
    }

    public function render()
    {
        return view('livewire.event-counter', [
            'eventNumber' => Event::current()->event_number,
        ]);
    }
}

