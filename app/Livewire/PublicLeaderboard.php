<?php

namespace App\Livewire;

use App\Models\RaceClass;
use App\Models\Session;
use App\Services\LineupGenerator;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PublicLeaderboard extends Component
{
    public ?int $classFilter = null;
    public ?string $dayFilter = 'friday';
    public array $expandedDrivers = [];

    public function toggleDriver(int $index): void
    {
        if (in_array($index, $this->expandedDrivers)) {
            $this->expandedDrivers = array_values(array_diff($this->expandedDrivers, [$index]));
        } else {
            $this->expandedDrivers[] = $index;
            if (count($this->expandedDrivers) > 2) {
                array_shift($this->expandedDrivers);
            }
        }
    }

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
        return $generator->getStandings($this->dayFilter, $this->classFilter);
    }

    #[Computed]
    public function classes()
    {
        return RaceClass::where('show_on_leaderboard', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function currentClassName()
    {
        if (!$this->classFilter) return 'All Classes';
        return RaceClass::find($this->classFilter)?->name ?? 'All Classes';
    }

    #[Computed]
    public function availableDays()
    {
        return Session::query()
            ->whereIn('id', \App\Models\Result::distinct()->pluck('race_session_id'))
            ->distinct()
            ->pluck('day')
            ->filter()
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.public-leaderboard');
    }
}

