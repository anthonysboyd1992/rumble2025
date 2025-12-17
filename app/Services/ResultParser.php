<?php

namespace App\Services;

use App\Models\Entry;
use App\Models\Session;
use App\Models\Result;

class ResultParser
{
    protected ?int $raceClassId = null;

    public function parse(string $rawText, string $day = 'friday', ?int $raceClassId = null): array
    {
        $this->raceClassId = $raceClassId;
        $lines = array_filter(array_map('trim', explode("\n", $rawText)));
        $imported = [];

        foreach ($lines as $line) {
            // Check if this is a header line
            if ($this->isHeaderLine($line)) {
                $session = $this->parseHeader($line, $day);
                continue;
            }

            // Skip if no session yet
            if (!isset($session)) {
                continue;
            }

            // Parse results line
            $results = $this->parseResultsLine($line, $session);
            $imported = array_merge($imported, $results);
        }

        return $imported;
    }

    protected function isHeaderLine(string $line): bool
    {
        // Header format with full info: "Qualifying 1 99 Laps | 00:01:06.000 | Ti-22 Performance"
        // Header format minimal: "Qualifying 2" (no laps, time, or sponsor)
        return preg_match('/^(Qualifying|Heat|A Feature|B Feature|Dash)\s+\d+/i', $line);
    }

    protected function parseHeader(string $line, string $day): Session
    {
        // Full format: "Heat 1 8 Laps | 00:05:32.000 | Engler Machine & Tool"
        // Minimal format: "Qualifying 2"
        
        // Try full format first
        if (preg_match('/^(Qualifying|Heat|A Feature|B Feature|Dash)\s+(\d+)\s+(\d+)\s+Laps?\s*\|\s*([^\|]+)\s*\|\s*(.+)$/i', $line, $matches)) {
            $name = trim($matches[1]) . ' ' . $matches[2];
            $laps = (int) $matches[3];
            $duration = trim($matches[4]);
            $sponsor = trim($matches[5]);
        } else {
            // Minimal format: "Qualifying 2"
            preg_match('/^(Qualifying|Heat|A Feature|B Feature|Dash)\s+(\d+)/i', $line, $matches);
            $name = trim($matches[1] ?? 'Unknown') . ' ' . ($matches[2] ?? '1');
            $laps = null;
            $duration = null;
            $sponsor = null;
        }

        $typeMap = [
            'qualifying' => 'qualifying',
            'heat' => 'heat',
            'a feature' => 'amain',
            'b feature' => 'bmain',
            'dash' => 'dash',
        ];
        $type = $typeMap[strtolower($matches[1] ?? 'heat')] ?? 'heat';

        return Session::firstOrCreate(
            ['name' => $name, 'day' => $day, 'race_class_id' => $this->raceClassId],
            [
                'type' => $type,
                'day' => $day,
                'group' => 'all',
                'laps' => $laps,
                'duration' => $duration,
                'sponsor' => $sponsor,
                'race_class_id' => $this->raceClassId,
            ]
        );
    }

    protected function parseResultsLine(string $line, Session $session): array
    {
        $results = [];
        $pointsCalculator = new PointsCalculator();

        // Split by semicolon for multiple results on one line
        $entries = preg_split('/;\s*/', $line);

        foreach ($entries as $entryText) {
            $entryText = trim($entryText);
            if (empty($entryText)) continue;

            $parsed = $this->parseEntry($entryText);
            if (!$parsed) continue;

            // Find or create the entry (auto-registration)
            $entry = Entry::firstOrCreate(
                ['car_number' => $parsed['car_number'], 'race_class_id' => $this->raceClassId],
                ['driver_name' => $parsed['driver_name'], 'race_class_id' => $this->raceClassId]
            );

            // Calculate points
            $points = $pointsCalculator->calculate($session->type, $parsed['position'], $parsed['is_dns'], $parsed['is_dq'] ?? false);

            // Create or update result
            $result = Result::updateOrCreate(
                ['entry_id' => $entry->id, 'race_session_id' => $session->id],
                [
                    'position' => $parsed['position'],
                    'time' => $parsed['time'],
                    'starting_position' => $parsed['starting_position'],
                    'points_earned' => $points,
                    'is_dns' => $parsed['is_dns'],
                    'is_dnf' => $parsed['is_dq'] ?? false,
                ]
            );

            $results[] = [
                'entry' => $entry,
                'result' => $result,
            ];
        }

        return $results;
    }

    protected function parseEntry(string $text): ?array
    {
        // Check for DNS/DQ: "(DNS) 77E-Ashton VanEvery" or "(DQ) 49T-Gregg Dalman"
        $isDns = str_contains(strtoupper($text), '(DNS)');
        $isDq = str_contains(strtoupper($text), '(DQ)');
        $text = preg_replace('/\(DNS\)\s*/i', '', $text);
        $text = preg_replace('/\(DQ\)\s*/i', '', $text);

        // Pattern for qualifying with time: "1. 71H-Max Stambaugh, 00:12.195[11]"
        // Pattern for heat/feature: "1. 17-Jared Horstman[3]"
        // Pattern for DNS/DQ without bracket: "(DNS) 49T-Gregg Dalman"
        $pattern = '/^(\d+)\.\s*([A-Za-z0-9]+)-([^,\[]+)(?:,\s*([0-9:\.]+))?(?:\[(\d+)\])?/';

        if (!preg_match($pattern, $text, $matches)) {
            return null;
        }

        return [
            'position' => (int) $matches[1],
            'car_number' => $matches[2],
            'driver_name' => trim($matches[3]),
            'time' => ($isDns || $isDq) ? null : ($matches[4] ?? null),
            'starting_position' => isset($matches[5]) ? (int) $matches[5] : null,
            'is_dns' => $isDns,
            'is_dq' => $isDq,
        ];
    }
}

