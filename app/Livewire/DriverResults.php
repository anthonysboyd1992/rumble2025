<?php

namespace App\Livewire;

use App\Models\Entry;
use Livewire\Attributes\On;
use Livewire\Component;

class DriverResults extends Component
{
    public ?int $selectedEntryId = null;

    #[On('results-imported')]
    public function refresh(): void
    {
    }

    public function selectDriver(int $entryId): void
    {
        $this->selectedEntryId = $this->selectedEntryId === $entryId ? null : $entryId;
    }

    public function render()
    {
        $entries = Entry::with(['results.session'])
            ->withCount('results')
            ->orderBy('car_number')
            ->get();

        $selectedEntry = $this->selectedEntryId 
            ? Entry::with(['results.session'])->find($this->selectedEntryId) 
            : null;

        return view('livewire.driver-results', [
            'entries' => $entries,
            'selectedEntry' => $selectedEntry,
        ]);
    }
}

