<?php

namespace App\Livewire;

use App\Models\RaceClass;
use App\Services\LineupGenerator;
use Livewire\Attributes\On;
use Livewire\Component;

class Standings extends Component
{
    public ?int $classFilter = null;
    public bool $inversionEnabled = false;
    public int $inversionCount = 12;

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
        $standings = $generator->getStandings('friday', $this->classFilter);

        // Apply inversion if enabled
        if ($this->inversionEnabled && $this->inversionCount > 0 && $standings->count() >= $this->inversionCount) {
            $toInvert = $standings->take($this->inversionCount)->reverse()->values();
            $rest = $standings->skip($this->inversionCount)->values();
            $standings = $toInvert->concat($rest);
        }

        return view('livewire.standings', [
            'standings' => $standings,
            'classes' => RaceClass::orderBy('name')->get(),
        ]);
    }
}

