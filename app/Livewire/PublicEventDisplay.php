<?php

namespace App\Livewire;

use App\Models\Event;
use Livewire\Attributes\On;
use Livewire\Component;

class PublicEventDisplay extends Component
{
    public string $size = 'small'; // 'small' or 'large'

    #[On('event-updated')]
    public function refresh(): void {}

    public function render()
    {
        return view('livewire.public-event-display', [
            'eventNumber' => Event::current()->event_number,
        ]);
    }
}

