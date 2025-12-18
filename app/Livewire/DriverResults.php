<?php

namespace App\Livewire;

use App\Events\StandingsUpdated;
use App\Models\Entry;
use App\Models\RaceClass;
use App\Models\Result;
use Livewire\Attributes\On;
use Livewire\Component;

class DriverResults extends Component
{
    public ?int $selectedEntryId = null;
    public ?int $classFilter = null;

    #[On('results-imported')]
    #[On('classes-updated')]
    public function refresh(): void
    {
    }

    public function selectDriver(int $entryId): void
    {
        $this->selectedEntryId = $this->selectedEntryId === $entryId ? null : $entryId;
    }

    public function deleteResult(int $resultId): void
    {
        Result::find($resultId)?->delete();
        $this->dispatch('results-imported');
        event(new StandingsUpdated());
    }

    public function render()
    {
        $query = Entry::with(['results.session', 'raceClass'])
            ->withCount('results');

        if ($this->classFilter) {
            $query->where('race_class_id', $this->classFilter);
        }

        $entries = $query->get()
            ->sortByDesc(fn($entry) => $entry->results->sum('points_earned'))
            ->values();

        return view('livewire.driver-results', [
            'entries' => $entries,
            'classes' => RaceClass::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }
}

