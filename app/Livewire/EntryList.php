<?php

namespace App\Livewire;

use App\Models\Entry;
use Livewire\Attributes\On;
use Livewire\Component;

class EntryList extends Component
{
    #[On('results-imported')]
    public function refresh(): void
    {
        // Will re-render with updated data
    }

    public function render()
    {
        $entries = Entry::withCount('results')
            ->orderBy('car_number')
            ->get();

        return view('livewire.entry-list', [
            'entries' => $entries,
        ]);
    }
}

