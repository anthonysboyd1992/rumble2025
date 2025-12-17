<?php

namespace App\Http\Controllers;

use App\Services\LineupGenerator;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ExportController extends Controller
{
    public function standings(?string $day = null): Response
    {
        $classId = request()->query('class');
        $generator = new LineupGenerator();
        $standings = $generator->getStandings($day, $classId);

        $className = $classId ? \App\Models\RaceClass::find($classId)?->name : 'All Classes';

        $csv = "Position,Car,Driver,Class,Time,Qualifying,Heats,A-Main,Total\n";

        foreach ($standings as $index => $standing) {
            $csv .= implode(',', [
                $index + 1,
                '"' . $standing['entry']->car_number . '"',
                '"' . $standing['entry']->driver_name . '"',
                '"' . ($standing['entry']->raceClass?->name ?? '') . '"',
                '"' . ($standing['qualifying_time'] ?? '') . '"',
                $standing['qualifying_status'] ?? $standing['qualifying_points'] ?? '',
                $standing['heat_status'] ?? $standing['heat_points'] ?? '',
                $standing['amain_status'] ?? $standing['amain_points'] ?? '',
                $standing['total_points'],
            ]) . "\n";
        }

        $filename = 'standings' . ($day ? '-' . $day : '') . '-' . date('Y-m-d-His') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function print(?string $day = null): View
    {
        $classId = request()->query('class');
        $generator = new LineupGenerator();
        $standings = $generator->getStandings($day, $classId);

        $className = $classId ? \App\Models\RaceClass::find($classId)?->name : null;

        return view('standings-print', [
            'standings' => $standings,
            'day' => $day,
            'className' => $className,
        ]);
    }

    public function qualifyingCsv(): Response
    {
        $classId = request()->query('class');
        $day = request()->query('day', 'friday');
        $inversion = request()->query('inversion');
        if ($inversion && $inversion !== 'full') {
            $inversion = (int) $inversion;
        }
        $standings = $this->getQualifyingStandings($classId, $day, $inversion);

        $className = $classId ? \App\Models\RaceClass::find($classId)?->name : 'All Classes';

        $csv = "Position,Car,Driver,Best Time,Session,All Times\n";

        foreach ($standings as $index => $standing) {
            $allTimesStr = implode(' | ', array_map(fn($t) => \App\Helpers\TimeFormatter::format($t), $standing['all_times']));
            $csv .= implode(',', [
                $index + 1,
                '"' . $standing['car_number'] . '"',
                '"' . $standing['driver_name'] . '"',
                '"' . \App\Helpers\TimeFormatter::format($standing['best_time']) . '"',
                '"' . $standing['session_name'] . '"',
                '"' . $allTimesStr . '"',
            ]) . "\n";
        }

        $filename = 'qualifying-' . ($classId ? strtolower(str_replace(' ', '-', $className)) . '-' : '') . date('Y-m-d-His') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function qualifyingPrint(): View
    {
        $classId = request()->query('class');
        $day = request()->query('day', 'friday');
        $inversion = request()->query('inversion');
        if ($inversion && $inversion !== 'full') {
            $inversion = (int) $inversion;
        }
        $standings = $this->getQualifyingStandings($classId, $day, $inversion);

        $className = $classId ? \App\Models\RaceClass::find($classId)?->name : null;

        return view('qualifying-print', [
            'standings' => $standings,
            'className' => $className,
            'inversion' => $inversion,
        ]);
    }

    protected function getQualifyingStandings(?int $classId, string $day = 'friday', $inversion = null): \Illuminate\Support\Collection
    {
        $qualifyingQuery = \App\Models\QualifyingTime::query();
        if ($classId) {
            $qualifyingQuery->where('race_class_id', $classId);
        }
        $qualifyingQuery->where('day', $day);
        $qualifyingTimes = $qualifyingQuery->get()->map(fn($t) => [
            'car_number' => $t->car_number,
            'driver_name' => $t->driver_name,
            'time' => $t->fast_time,
            'session' => $t->session_name,
            'lap' => null,
        ]);

        $crossingQuery = \App\Models\CrossingTime::query();
        if ($classId) {
            $crossingQuery->where('race_class_id', $classId);
        }
        $crossingQuery->where('day', $day);
        $crossingTimes = $crossingQuery->get()->map(fn($t) => [
            'car_number' => $t->car_number,
            'driver_name' => $t->driver_name,
            'time' => $t->laptime,
            'session' => $t->session_name,
            'lap' => $t->lap,
        ]);

        $allTimes = $qualifyingTimes->concat($crossingTimes);

        $standings = $allTimes
            ->groupBy('car_number')
            ->map(function ($driverTimes) {
                $sorted = $driverTimes->sortBy('time');
                $best = $sorted->first();
                return [
                    'car_number' => $best['car_number'],
                    'driver_name' => $best['driver_name'],
                    'best_time' => $best['time'],
                    'session_name' => $best['session'],
                    'lap_count' => $driverTimes->count(),
                    'all_times' => $sorted->map(fn($t) => $t['time'])->values()->all(),
                ];
            })
            ->sortBy(function ($standing) {
                return \App\Helpers\TimeFormatter::parseTimeToSeconds($standing['best_time']);
            })
            ->values();

        // Apply full invert if requested
        if ($inversion === 'full') {
            $standings = $standings->reverse()->values();
        }
        // Apply inversion if provided
        elseif ($inversion && is_numeric($inversion) && $inversion > 0 && $standings->count() >= $inversion) {
            $toInvert = $standings->take($inversion)->reverse()->values();
            $rest = $standings->skip($inversion)->values();
            $standings = $toInvert->concat($rest);
        }

        return $standings;
    }

    protected function getAllQualifyingTimes(?int $classId, string $day = 'friday', $inversion = null): \Illuminate\Support\Collection
    {
        $standings = $this->getQualifyingStandings($classId, $day, $inversion);
        
        // Flatten to individual times with driver info
        $allTimes = collect();
        foreach ($standings as $standing) {
            foreach ($standing['all_times'] as $time) {
                $allTimes->push([
                    'car_number' => $standing['car_number'],
                    'driver_name' => $standing['driver_name'],
                    'time' => $time,
                    'session' => $standing['session_name'],
                    'lap' => null,
                ]);
            }
        }
        
        return $allTimes->sortBy('time')->values();
    }

    protected function getQualifyingStandingsAllDays(?int $classId, $inversion = null): \Illuminate\Support\Collection
    {
        $qualifyingQuery = \App\Models\QualifyingTime::query();
        if ($classId) {
            $qualifyingQuery->where('race_class_id', $classId);
        }
        $qualifyingTimes = $qualifyingQuery->get()->map(fn($t) => [
            'car_number' => $t->car_number,
            'driver_name' => $t->driver_name,
            'time' => $t->fast_time,
            'session' => $t->session_name,
            'day' => $t->day,
            'lap' => null,
        ]);

        $crossingQuery = \App\Models\CrossingTime::query();
        if ($classId) {
            $crossingQuery->where('race_class_id', $classId);
        }
        $crossingTimes = $crossingQuery->get()->map(fn($t) => [
            'car_number' => $t->car_number,
            'driver_name' => $t->driver_name,
            'time' => $t->laptime,
            'session' => $t->session_name,
            'day' => $t->day,
            'lap' => $t->lap,
        ]);

        $allTimes = $qualifyingTimes->concat($crossingTimes);

        $standings = $allTimes
            ->groupBy('car_number')
            ->map(function ($driverTimes) {
                $sorted = $driverTimes->sortBy(function ($t) {
                    return \App\Helpers\TimeFormatter::parseTimeToSeconds($t['time']);
                });
                $best = $sorted->first();
                return [
                    'car_number' => $best['car_number'],
                    'driver_name' => $best['driver_name'],
                    'best_time' => $best['time'],
                    'session_name' => $best['session'],
                    'day' => $best['day'],
                    'lap_count' => $driverTimes->count(),
                    'all_times' => [$best['time']], // Only the best time
                ];
            })
            ->sortBy(function ($standing) {
                return \App\Helpers\TimeFormatter::parseTimeToSeconds($standing['best_time']);
            })
            ->values();

        // Apply full invert if requested
        if ($inversion === 'full') {
            $standings = $standings->reverse()->values();
        }
        // Apply inversion if provided
        elseif ($inversion && is_numeric($inversion) && $inversion > 0 && $standings->count() >= $inversion) {
            $toInvert = $standings->take($inversion)->reverse()->values();
            $rest = $standings->skip($inversion)->values();
            $standings = $toInvert->concat($rest);
        }

        return $standings;
    }

    protected function getAllQualifyingTimesAllDays(?int $classId, ?int $inversion = null): \Illuminate\Support\Collection
    {
        $qualifyingQuery = \App\Models\QualifyingTime::query();
        if ($classId) {
            $qualifyingQuery->where('race_class_id', $classId);
        }
        $qualifyingTimes = $qualifyingQuery->get()->map(fn($t) => [
            'car_number' => $t->car_number,
            'driver_name' => $t->driver_name,
            'time' => $t->fast_time,
            'session' => $t->session_name,
            'day' => $t->day,
            'lap' => null,
        ]);

        $crossingQuery = \App\Models\CrossingTime::query();
        if ($classId) {
            $crossingQuery->where('race_class_id', $classId);
        }
        $crossingTimes = $crossingQuery->get()->map(fn($t) => [
            'car_number' => $t->car_number,
            'driver_name' => $t->driver_name,
            'time' => $t->laptime,
            'session' => $t->session_name,
            'day' => $t->day,
            'lap' => $t->lap,
        ]);

        return $qualifyingTimes->concat($crossingTimes)->sortBy('time')->values();
    }

    public function qualifyingAllCsv(): Response
    {
        $classId = request()->query('class');
        $day = request()->query('day', 'friday');
        $inversion = request()->query('inversion') ? (int) request()->query('inversion') : null;
        $times = $this->getAllQualifyingTimes($classId, $day, $inversion);

        $className = $classId ? \App\Models\RaceClass::find($classId)?->name : 'All Classes';

        $csv = "Position,Car,Driver,Session,Lap,Time\n";

        foreach ($times as $index => $time) {
            $csv .= implode(',', [
                $index + 1,
                '"' . $time['car_number'] . '"',
                '"' . $time['driver_name'] . '"',
                '"' . $time['session'] . '"',
                $time['lap'] ?? '',
                '"' . \App\Helpers\TimeFormatter::format($time['time']) . '"',
            ]) . "\n";
        }

        $filename = 'qualifying-all-' . ($classId ? strtolower(str_replace(' ', '-', $className)) . '-' : '') . date('Y-m-d-His') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function qualifyingAllPrint(): View
    {
        $classId = request()->query('class');
        $day = request()->query('day', 'friday');
        $inversion = request()->query('inversion') ? (int) request()->query('inversion') : null;
        $standings = $this->getQualifyingStandings($classId, $day, $inversion);

        $className = $classId ? \App\Models\RaceClass::find($classId)?->name : null;
        $times = $this->getAllQualifyingTimes($classId, $day, $inversion);

        return view('qualifying-all-print', [
            'times' => $times,
            'className' => $className,
        ]);
    }

    public function qualifyingAllDaysCsv(): Response
    {
        $classId = request()->query('class');
        $inversion = request()->query('inversion');
        if ($inversion && $inversion !== 'full') {
            $inversion = (int) $inversion;
        }
        $standings = $this->getQualifyingStandingsAllDays($classId, $inversion);

        $className = $classId ? \App\Models\RaceClass::find($classId)?->name : 'All Classes';

        $csv = "Position,Car,Driver,Best Time,Session,All Times\n";

        foreach ($standings as $index => $standing) {
            $allTimesStr = implode(' | ', array_map(fn($t) => \App\Helpers\TimeFormatter::format($t), $standing['all_times']));
            $csv .= implode(',', [
                $index + 1,
                '"' . $standing['car_number'] . '"',
                '"' . $standing['driver_name'] . '"',
                '"' . \App\Helpers\TimeFormatter::format($standing['best_time']) . '"',
                '"' . $standing['session_name'] . '"',
                '"' . $allTimesStr . '"',
            ]) . "\n";
        }

        $filename = 'qualifying-fastest-all-' . ($classId ? strtolower(str_replace(' ', '-', $className)) . '-' : '') . date('Y-m-d-His') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function qualifyingAllDaysPrint(): View
    {
        $classId = request()->query('class');
        $inversion = request()->query('inversion');
        if ($inversion && $inversion !== 'full') {
            $inversion = (int) $inversion;
        }
        $standings = $this->getQualifyingStandingsAllDays($classId, $inversion);

        $className = $classId ? \App\Models\RaceClass::find($classId)?->name : null;

        return view('qualifying-print', [
            'standings' => $standings,
            'className' => $className,
            'inversion' => $inversion,
        ]);
    }

    protected function getCrossingTimes(?int $classId, string $day = 'friday'): \Illuminate\Support\Collection
    {
        $crossingQuery = \App\Models\CrossingTime::query();
        if ($classId) {
            $crossingQuery->where('race_class_id', $classId);
        }
        $crossingQuery->where('day', $day);
        
        return $crossingQuery->get()->map(fn($t) => [
            'car_number' => $t->car_number,
            'driver_name' => $t->driver_name,
            'time' => $t->laptime,
            'session' => $t->session_name,
            'lap' => $t->lap,
        ])->sortBy('time')->values();
    }

    protected function getAllCrossingTimes(?int $classId): \Illuminate\Support\Collection
    {
        $crossingQuery = \App\Models\CrossingTime::query();
        if ($classId) {
            $crossingQuery->where('race_class_id', $classId);
        }
        
        return $crossingQuery->get()->map(fn($t) => [
            'car_number' => $t->car_number,
            'driver_name' => $t->driver_name,
            'time' => $t->laptime,
            'session' => $t->session_name,
            'day' => $t->day,
            'lap' => $t->lap,
        ])->sortBy('time')->values();
    }

    public function crossingDayPrint(): View
    {
        $classId = request()->query('class');
        $day = request()->query('day', 'friday');
        $times = $this->getCrossingTimes($classId, $day);

        $className = $classId ? \App\Models\RaceClass::find($classId)?->name : null;

        return view('qualifying-all-print', [
            'times' => $times,
            'className' => $className,
        ]);
    }

    public function crossingDayCsv(): Response
    {
        $classId = request()->query('class');
        $day = request()->query('day', 'friday');
        $times = $this->getCrossingTimes($classId, $day);

        $className = $classId ? \App\Models\RaceClass::find($classId)?->name : 'All Classes';

        $csv = "Position,Car,Driver,Session,Lap,Time\n";

        foreach ($times as $index => $time) {
            $csv .= implode(',', [
                $index + 1,
                '"' . $time['car_number'] . '"',
                '"' . $time['driver_name'] . '"',
                '"' . $time['session'] . '"',
                $time['lap'] ?? '',
                '"' . \App\Helpers\TimeFormatter::format($time['time']) . '"',
            ]) . "\n";
        }

        $filename = 'crossing-day-' . ($classId ? strtolower(str_replace(' ', '-', $className)) . '-' : '') . date('Y-m-d-His') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function allCrossingsPrint(): View
    {
        $classId = request()->query('class');
        $times = $this->getAllCrossingTimes($classId);

        $className = $classId ? \App\Models\RaceClass::find($classId)?->name : null;

        return view('qualifying-all-print', [
            'times' => $times,
            'className' => $className,
            'showDay' => true,
        ]);
    }

    public function allCrossingsCsv(): Response
    {
        $classId = request()->query('class');
        $times = $this->getAllCrossingTimes($classId);

        $className = $classId ? \App\Models\RaceClass::find($classId)?->name : 'All Classes';

        $csv = "Position,Car,Driver,Day,Session,Lap,Time\n";

        foreach ($times as $index => $time) {
            $csv .= implode(',', [
                $index + 1,
                '"' . $time['car_number'] . '"',
                '"' . $time['driver_name'] . '"',
                '"' . ucfirst($time['day'] ?? '') . '"',
                '"' . $time['session'] . '"',
                $time['lap'] ?? '',
                '"' . \App\Helpers\TimeFormatter::format($time['time']) . '"',
            ]) . "\n";
        }

        $filename = 'all-crossings-' . ($classId ? strtolower(str_replace(' ', '-', $className)) . '-' : '') . date('Y-m-d-His') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function mrpLineup(): Response
    {
        $classId = request()->query('class');
        $day = request()->query('day', 'friday');
        
        // Get crossing times grouped by session
        $crossingQuery = \App\Models\CrossingTime::query();
        if ($classId) {
            $crossingQuery->where('race_class_id', $classId);
        }
        $crossingQuery->where('day', $day);
        $crossingTimes = $crossingQuery->get();

        // Group by session
        $sessions = $crossingTimes->groupBy('session_name');

        $output = [];

        foreach ($sessions as $sessionName => $times) {
            // Get ONLY the fastest time per driver for this session
            // Group by car_number to ensure each driver appears only once
            $driverBestTimes = $times
                ->groupBy('car_number')
                ->map(function ($driverTimes) {
                    // Sort by time (ascending) and take the first (fastest) one
                    $best = $driverTimes->sortBy(function ($t) {
                        $seconds = \App\Helpers\TimeFormatter::parseTimeToSeconds($t->laptime);
                        return $seconds > 0 ? $seconds : PHP_FLOAT_MAX; // Handle invalid times
                    })->first();
                    
                    if (!$best) {
                        return null;
                    }
                    
                    return [
                        'car_number' => $best->car_number,
                        'driver_name' => $best->driver_name,
                        'time_seconds' => \App\Helpers\TimeFormatter::parseTimeToSeconds($best->laptime),
                    ];
                })
                ->filter() // Remove any null entries
                ->sortBy('time_seconds') // Sort all drivers by their fastest time
                ->values();

            // Get max lap for this session
            $maxLap = $times->whereNotNull('lap')->max('lap');

            // Build the lineup string
            $lineupParts = [];
            foreach ($driverBestTimes as $index => $driver) {
                $lineupParts[] = ($index + 1) . '. ' . $driver['car_number'] . '-' . $driver['driver_name'];
            }

            $lineup = implode('; ', $lineupParts);
            
            // Format: Session Name (Max Lap): 1. Car-Driver; 2. Car-Driver; ...
            // Session name may or may not include sponsor prefix
            // Replace "Practice" with "Qualifying" in session name
            $sessionLine = trim($sessionName);
            $sessionLine = str_replace('Practice', 'Qualifying', $sessionLine);
            if ($maxLap) {
                $sessionLine .= ' (' . $maxLap . ' Laps)';
            }
            $sessionLine .= ': ' . $lineup;

            $output[] = $sessionLine;
        }

        // Also include qualifying times if they exist
        $qualifyingQuery = \App\Models\QualifyingTime::query();
        if ($classId) {
            $qualifyingQuery->where('race_class_id', $classId);
        }
        $qualifyingQuery->where('day', $day);
        $qualifyingTimes = $qualifyingQuery->get();

        if ($qualifyingTimes->isNotEmpty()) {
            $qualifyingSessions = $qualifyingTimes->groupBy('session_name');

            foreach ($qualifyingSessions as $sessionName => $times) {
                // Get ONLY the fastest time per driver for this session
                // Group by car_number to ensure each driver appears only once
                $driverBestTimes = $times
                    ->groupBy('car_number')
                    ->map(function ($driverTimes) {
                        // Sort by time (ascending) and take the first (fastest) one
                        $best = $driverTimes->sortBy(function ($t) {
                            $seconds = \App\Helpers\TimeFormatter::parseTimeToSeconds($t->fast_time);
                            return $seconds > 0 ? $seconds : PHP_FLOAT_MAX; // Handle invalid times
                        })->first();
                        
                        if (!$best) {
                            return null;
                        }
                        
                        return [
                            'car_number' => $best->car_number,
                            'driver_name' => $best->driver_name,
                            'time_seconds' => \App\Helpers\TimeFormatter::parseTimeToSeconds($best->fast_time),
                        ];
                    })
                    ->filter() // Remove any null entries
                    ->sortBy('time_seconds') // Sort all drivers by their fastest time
                    ->values();

                // Get laps from qualifying times
                $laps = $times->whereNotNull('laps')->max('laps');

                // Build the lineup string
                $lineupParts = [];
                foreach ($driverBestTimes as $index => $driver) {
                    $lineupParts[] = ($index + 1) . '. ' . $driver['car_number'] . '-' . $driver['driver_name'];
                }

                $lineup = implode('; ', $lineupParts);
                
                // Format: Session Name (Laps): 1. Car-Driver; 2. Car-Driver; ...
                // Session name may or may not include sponsor prefix
                // Replace "Practice" with "Qualifying" in session name
                $sessionLine = trim($sessionName);
                $sessionLine = str_replace('Practice', 'Qualifying', $sessionLine);
                if ($laps) {
                    $sessionLine .= ' (' . $laps . ' Laps)';
                }
                $sessionLine .= ': ' . $lineup;

                $output[] = $sessionLine;
            }
        }

        $content = implode("\n", $output);

        $filename = 'mrp-lineup-' . ($classId ? strtolower(str_replace(' ', '-', \App\Models\RaceClass::find($classId)?->name ?? '')) . '-' : '') . date('Y-m-d-His') . '.txt';

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}

