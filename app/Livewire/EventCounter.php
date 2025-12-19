<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventMessage;
use Livewire\Component;

class EventCounter extends Component
{
    public ?int $eventNumber = null;
    public string $message = '';
    public string $newMessageText = '';

    public function mount(): void
    {
        $event = Event::current();
        $this->eventNumber = $event->event_number;
        $this->message = $event->message ?? '';
    }

    public function updatedEventNumber($value): void
    {
        if ($value === null || $value === '') {
            return;
        }
        
        $value = (int) $value;
        if ($value < 1) {
            $value = 1;
        }
        
        $event = Event::current();
        $event->update(['event_number' => $value]);
        $this->eventNumber = $value;
        $this->dispatch('event-updated');
    }

    public function updatedMessage($value): void
    {
        $event = Event::current();
        $event->update(['message' => $value]);
        $this->dispatch('event-updated');
    }

    public function applyMessage(string $text): void
    {
        $this->message = $text;
        $event = Event::current();
        $event->update(['message' => $text]);
        $this->dispatch('event-updated');
    }

    public function clearMessage(): void
    {
        $this->message = '';
        $event = Event::current();
        $event->update(['message' => null]);
        $this->dispatch('event-updated');
    }

    public function addPredefinedMessage(): void
    {
        if (empty(trim($this->newMessageText))) {
            return;
        }

        $maxOrder = EventMessage::max('sort_order') ?? 0;
        EventMessage::create([
            'text' => trim($this->newMessageText),
            'sort_order' => $maxOrder + 1,
        ]);

        $this->newMessageText = '';
    }

    public function deleteMessage(int $id): void
    {
        EventMessage::find($id)?->delete();
    }

    public function increment(): void
    {
        $event = Event::current();
        $event->increment('event_number');
        $this->eventNumber = $event->event_number;
        $this->dispatch('event-updated');
    }

    public function decrement(): void
    {
        $event = Event::current();
        if ($event->event_number > 1) {
            $event->decrement('event_number');
            $this->eventNumber = $event->event_number;
            $this->dispatch('event-updated');
        }
    }

    public function render()
    {
        return view('livewire.event-counter', [
            'predefinedMessages' => EventMessage::orderBy('sort_order')->orderBy('text')->get(),
        ]);
    }
}

