<?php

namespace App\Livewire;

use App\Models\CrossingTime;
use App\Models\QualifyingTime;
use App\Models\RaceClass;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PublicQualifyingLeaderboard extends Component
{
    public ?int $classFilter = null;
    public string $dayFilter = 'friday';
    public ?int $expandedDriver = null;
    public string $trackLength = '0.142857'; // 1/7 mile default

    public function mount(): void
    {
        $firstClass = RaceClass::first();
        $this->classFilter = $firstClass?->id;
    }

    public function toggleDriver(int $index): void
    {
        $this->expandedDriver = $this->expandedDriver === $index ? null : $index;
    }

    #[Computed]
    public function standings()
    {
        // Get qualifying times
        $qualifyingQuery = QualifyingTime::query();
        if ($this->classFilter) {
            $qualifyingQuery->where('race_class_id', $this->classFilter);
        }
        $qualifyingQuery->where('day', $this->dayFilter);
        $qualifyingTimes = $qualifyingQuery->get()->map(fn($t) => [
            'car_number' => $t->car_number,
            'driver_name' => $t->driver_name,
            'time' => $t->fast_time,
            'session' => $t->session_name,
        ]);

        // Get crossing times
        $crossingQuery = CrossingTime::query();
        if ($this->classFilter) {
            $crossingQuery->where('race_class_id', $this->classFilter);
        }
        $crossingQuery->where('day', $this->dayFilter);
        $crossingTimes = $crossingQuery->get()->map(fn($t) => [
            'car_number' => $t->car_number,
            'driver_name' => $t->driver_name,
            'time' => $t->laptime,
            'session' => $t->session_name,
            'lap' => $t->lap,
        ]);

        // Combine and get best per driver
        $allTimes = $qualifyingTimes->concat($crossingTimes);

        $standings = $allTimes
            ->groupBy('car_number')
            ->map(function ($driverTimes) {
                // Find best (fastest) time first
                $best = $driverTimes->sortBy(function ($t) {
                    return \App\Helpers\TimeFormatter::parseTimeToSeconds($t['time']);
                })->first();
                
                // Sort all times by session first (reverse order: Practice 3, Practice 2, Practice 1), then by time within each session
                $sorted = $driverTimes->sortBy([
                    function ($t) {
                        // Extract number from session name for reverse natural sorting
                        $session = $t['session'];
                        preg_match('/(\d+)/', $session, $matches);
                        $number = isset($matches[1]) ? (int)$matches[1] : 0;
                        // Prefix with session type for grouping (Practice, Qualifying, etc.)
                        // Use negative number for reverse sort (higher numbers first)
                        $prefix = preg_replace('/\s*\d+.*/', '', $session);
                        return $prefix . ' ' . str_pad(999 - $number, 3, '0', STR_PAD_LEFT);
                    },
                    function ($t) {
                        return \App\Helpers\TimeFormatter::parseTimeToSeconds($t['time']);
                    }
                ]);
                
                return [
                    'car_number' => $best['car_number'],
                    'driver_name' => $best['driver_name'],
                    'best_time' => $best['time'],
                    'session_name' => $best['session'],
                    'lap_count' => $driverTimes->count(),
                    'all_times' => $sorted->map(fn($t) => [
                        'session' => $t['session'],
                        'time' => $t['time'],
                        'lap' => $t['lap'] ?? null,
                        'is_best' => $t['time'] === $best['time'],
                    ])->values(),
                ];
            })
            ->sortBy(function ($standing) {
                return \App\Helpers\TimeFormatter::parseTimeToSeconds($standing['best_time']);
            })
            ->values();

        return $standings;
    }

    #[Computed]
    public function classes()
    {
        return RaceClass::orderBy('sort_order')->orderBy('name')->get();
    }

    #[Computed]
    public function currentClassName()
    {
        if (!$this->classFilter) return 'All Classes';
        return RaceClass::find($this->classFilter)?->name ?? 'All Classes';
    }

    public function render()
    {
        return view('livewire.public-qualifying-leaderboard');
    }
}

