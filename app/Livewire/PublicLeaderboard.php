<?php

namespace App\Livewire;

use App\Models\RaceClass;
use App\Services\LineupGenerator;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PublicLeaderboard extends Component
{
    public ?int $classFilter = null;

    public function mount(): void
    {
        // Default to first class if exists
        $firstClass = RaceClass::first();
        $this->classFilter = $firstClass?->id;
    }

    #[Computed]
    public function standings()
    {
        $generator = new LineupGenerator();
        return $generator->getStandings('friday', $this->classFilter);
    }

    #[Computed]
    public function classes()
    {
        return RaceClass::orderBy('name')->get();
    }

    #[Computed]
    public function currentClassName()
    {
        if (!$this->classFilter) return 'All Classes';
        return RaceClass::find($this->classFilter)?->name ?? 'All Classes';
    }

    public function render()
    {
        return view('livewire.public-leaderboard');
    }
}

