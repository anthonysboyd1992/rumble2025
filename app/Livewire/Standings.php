<?php

namespace App\Livewire;

use App\Models\RaceClass;
use App\Services\LineupGenerator;
use Livewire\Attributes\On;
use Livewire\Component;

class Standings extends Component
{
    public ?int $classFilter = null;
    public ?string $dayFilter = 'friday';
    public bool $inversionEnabled = false;
    public int $inversionCount = 12;

    public function mount(): void
    {
        // Auto-select "midgets" class if it exists
        $midgetsClass = \App\Models\RaceClass::where('name', 'LIKE', '%midget%')->first();
        if ($midgetsClass) {
            $this->classFilter = $midgetsClass->id;
        }
    }

    #[On('results-imported')]
    public function refresh(): void
    {
        // Will re-render with updated data
    }

    public function toggleInversion(): void
    {
        $this->inversionEnabled = !$this->inversionEnabled;
    }

    public function render()
    {
        $generator = new LineupGenerator();
        $standings = $generator->getStandings($this->dayFilter, $this->classFilter);

        // Apply inversion if enabled
        if ($this->inversionEnabled && $this->inversionCount > 0 && $standings->count() >= $this->inversionCount) {
            $toInvert = $standings->take($this->inversionCount)->reverse()->values();
            $rest = $standings->skip($this->inversionCount)->values();
            $standings = $toInvert->concat($rest);
        }

        return view('livewire.standings', [
            'standings' => $standings,
            'classes' => RaceClass::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }
}

