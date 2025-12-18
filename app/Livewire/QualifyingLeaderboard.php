<?php

namespace App\Livewire;

use App\Models\CrossingTime;
use App\Models\QualifyingTime;
use App\Models\RaceClass;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class QualifyingLeaderboard extends Component
{
    public ?int $classFilter = null;
    public string $dayFilter = 'friday';
    public ?int $expandedDriver = null;
    public string $trackLength = '0.142857'; // 1/7 mile default
    public bool $inversionEnabled = false;
    public int $inversionCount = 12;
    public bool $fullInvert = false;

    public function updatedInversionCount($value): void
    {
        if (empty($value) || $value < 1) {
            $this->inversionCount = 1;
        }
    }

    public function mount(): void
    {
        $this->dispatch('day-changed', day: $this->dayFilter);
    }

    public function updatedDayFilter(): void
    {
        $this->dispatch('day-changed', day: $this->dayFilter);
    }

    #[On('qualifying-imported')]
    #[On('crossing-imported')]
    #[On('classes-updated')]
    public function refresh(): void
    {
    }

    public function toggleDriver(int $index): void
    {
        $this->expandedDriver = $this->expandedDriver === $index ? null : $index;
    }

    public function deleteCrossing(int $id): void
    {
        CrossingTime::find($id)?->delete();
        $this->dispatch('qualifying-imported');
    }

    public function deleteQualifyingTime(int $id): void
    {
        QualifyingTime::find($id)?->delete();
        $this->dispatch('qualifying-imported');
    }

    public function toggleInversion(): void
    {
        $this->inversionEnabled = !$this->inversionEnabled;
        if ($this->inversionEnabled) {
            $this->fullInvert = false;
        }
    }

    public function toggleFullInvert(): void
    {
        $this->fullInvert = !$this->fullInvert;
        if ($this->fullInvert) {
            $this->inversionEnabled = false;
        }
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
            'id' => $t->id,
            'car_number' => $t->car_number,
            'driver_name' => $t->driver_name,
            'time' => $t->fast_time,
            'session' => $t->session_name,
            'lap' => $t->fast_lap,
            'source' => 'qualifying',
        ]);

        // Get crossing times
        $crossingQuery = CrossingTime::query();
        if ($this->classFilter) {
            $crossingQuery->where('race_class_id', $this->classFilter);
        }
        $crossingQuery->where('day', $this->dayFilter);
        $crossingTimes = $crossingQuery->get()->map(fn($t) => [
            'id' => $t->id,
            'car_number' => $t->car_number,
            'driver_name' => $t->driver_name,
            'time' => $t->laptime,
            'session' => $t->session_name,
            'lap' => $t->lap,
            'source' => 'crossing',
        ]);

        // Combine all times
        $allTimes = $qualifyingTimes->concat($crossingTimes);

        // Group by driver and find best
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
                    'source' => $best['source'],
                    'lap_count' => $driverTimes->count(),
                    'all_times' => $sorted->map(fn($t) => [
                        'id' => $t['id'] ?? null,
                        'session' => $t['session'],
                        'time' => $t['time'],
                        'source' => $t['source'],
                        'lap' => $t['lap'] ?? null,
                        'is_best' => $t['time'] === $best['time'],
                    ])->values(),
                ];
            })
            ->sortBy(function ($standing) {
                return \App\Helpers\TimeFormatter::parseTimeToSeconds($standing['best_time']);
            })
            ->values();

        // Apply full invert if enabled
        if ($this->fullInvert) {
            $standings = $standings->reverse()->values();
        }
        // Apply inversion if enabled
        elseif ($this->inversionEnabled && $this->inversionCount > 0 && $standings->count() >= $this->inversionCount) {
            $toInvert = $standings->take($this->inversionCount)->reverse()->values();
            $rest = $standings->skip($this->inversionCount)->values();
            $standings = $toInvert->concat($rest);
        }

        return $standings;
    }

    #[Computed]
    public function sessions()
    {
        $qualifyingSessions = QualifyingTime::query()
            ->when($this->classFilter, fn($q) => $q->where('race_class_id', $this->classFilter))
            ->where('day', $this->dayFilter)
            ->distinct()->pluck('session_name');
            
        $crossingSessions = CrossingTime::query()
            ->when($this->classFilter, fn($q) => $q->where('race_class_id', $this->classFilter))
            ->where('day', $this->dayFilter)
            ->distinct()->pluck('session_name');

        return $qualifyingSessions->concat($crossingSessions)->unique()->sort()->values();
    }

    public function render()
    {
        return view('livewire.qualifying-leaderboard', [
            'classes' => RaceClass::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }
}

