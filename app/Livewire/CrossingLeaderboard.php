<?php

namespace App\Livewire;

use App\Models\CrossingTime;
use App\Models\RaceClass;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class CrossingLeaderboard extends Component
{
    public ?int $classFilter = null;

    #[On('crossing-imported')]
    #[On('classes-updated')]
    public function refresh(): void
    {
    }

    public ?int $expandedDriver = null;

    public function toggleDriver(int $index): void
    {
        $this->expandedDriver = $this->expandedDriver === $index ? null : $index;
    }

    #[Computed]
    public function standings()
    {
        $query = CrossingTime::query();

        if ($this->classFilter) {
            $query->where('race_class_id', $this->classFilter);
        }

        // Get best time per driver (car_number)
        $times = $query->get()
            ->groupBy('car_number')
            ->map(function ($driverTimes) {
                $best = $driverTimes->sortBy('laptime')->first();
                return [
                    'car_number' => $best->car_number,
                    'driver_name' => $best->driver_name,
                    'laptime' => $best->laptime,
                    'session_name' => $best->session_name,
                    'lap_count' => $driverTimes->count(),
                    'all_times' => $driverTimes->sortBy('laptime')->map(fn($t) => [
                        'session' => $t->session_name,
                        'time' => $t->laptime,
                    ])->values(),
                ];
            })
            ->sortBy('laptime')
            ->values();

        return $times;
    }

    #[Computed]
    public function sessions()
    {
        $query = CrossingTime::query();
        
        if ($this->classFilter) {
            $query->where('race_class_id', $this->classFilter);
        }

        return $query->distinct()->pluck('session_name')->sort()->values();
    }

    public function render()
    {
        return view('livewire.crossing-leaderboard', [
            'classes' => RaceClass::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }
}

