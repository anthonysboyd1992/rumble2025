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

        $csv = "Position,Car,Driver,Time,Qualifying,Heats,A-Main,Total\n";

        foreach ($standings as $index => $standing) {
            $csv .= implode(',', [
                $index + 1,
                '"' . $standing['entry']->car_number . '"',
                '"' . $standing['entry']->driver_name . '"',
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
}

