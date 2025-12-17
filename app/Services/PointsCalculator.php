<?php

namespace App\Services;

class PointsCalculator
{
    // Qualifying: 75-70-69-68-67-66-65-64...
    // A-Main: 75-70-69-68-67-66-65-64...
    protected array $qualifyingPoints = [75, 70, 69, 68, 67, 66, 65, 64, 63, 62, 61, 60, 59, 58, 57, 56, 55, 54, 53, 52, 51, 50];
    protected array $amainPoints = [75, 70, 69, 68, 67, 66, 65, 64, 63, 62, 61, 60, 59, 58, 57, 56, 55, 54, 53, 52, 51, 50];

    // Heats: 25-20-19-18-17-16-15...
    protected array $heatPoints = [25, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1];

    public function calculate(string $sessionType, int $position, bool $isDns = false, bool $isDq = false): int
    {
        if ($isDns || $isDq) {
            return 0;
        }

        $points = match ($sessionType) {
            'qualifying', 'amain' => $this->qualifyingPoints,
            'heat', 'bmain', 'dash' => $this->heatPoints,
            default => [],
        };

        // Position is 1-indexed, array is 0-indexed
        $index = $position - 1;

        if ($index < 0 || $index >= count($points)) {
            // For positions beyond the defined points, calculate decreasing values
            $lastDefined = end($points);
            $extraPositions = $index - count($points) + 1;
            return max(1, $lastDefined - $extraPositions);
        }

        return $points[$index];
    }
}

