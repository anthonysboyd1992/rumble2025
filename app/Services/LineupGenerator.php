<?php

namespace App\Services;

use App\Models\Entry;
use Illuminate\Support\Collection;

class LineupGenerator
{
    /**
     * Generate Saturday heat lineups based on Friday points.
     * Returns entries ordered by Friday points (highest first).
     */
    public function generateSaturdayHeats(): Collection
    {
        return Entry::all()
            ->sortByDesc(fn($entry) => $entry->friday_points)
            ->values();
    }

    /**
     * Split qualifying results into odd/even groups for Friday heats.
     * Odd positions (1, 3, 5...) go to odd group.
     * Even positions (2, 4, 6...) go to even group.
     */
    public function splitQualifyingGroups(int $sessionId): array
    {
        $results = \App\Models\Result::where('race_session_id', $sessionId)
            ->orderBy('position')
            ->with('entry')
            ->get();

        $odd = [];
        $even = [];

        foreach ($results as $result) {
            if ($result->position % 2 === 1) {
                $odd[] = $result->entry;
            } else {
                $even[] = $result->entry;
            }
        }

        return [
            'odd' => collect($odd),
            'even' => collect($even),
        ];
    }

    /**
     * Get standings sorted by total points.
     */
    public function getStandings(?string $day = null, ?int $raceClassId = null): Collection
    {
        $query = Entry::with('results.session');
        
        if ($raceClassId) {
            $query->where('race_class_id', $raceClassId);
        }
        
        $entries = $query->get();

        return $entries->map(function ($entry) use ($day) {
            $results = $entry->results;

            if ($day) {
                $results = $results->filter(fn($r) => $r->session->day === $day);
            }

            $qualifyingResult = $results->first(fn($r) => $r->session->type === 'qualifying');
            $qualifyingPoints = $results->filter(fn($r) => $r->session->type === 'qualifying')->sum('points_earned');
            $heatPoints = $results->filter(fn($r) => $r->session->type === 'heat')->sum('points_earned');
            $amainPoints = $results->filter(fn($r) => $r->session->type === 'amain')->sum('points_earned');

            // Check for DNS/DQ in each category
            $qualifyingResults = $results->filter(fn($r) => $r->session->type === 'qualifying');
            $heatResults = $results->filter(fn($r) => $r->session->type === 'heat');
            $amainResults = $results->filter(fn($r) => $r->session->type === 'amain');

            $qualifyingDns = $qualifyingResults->contains(fn($r) => $r->is_dns);
            $qualifyingDq = $qualifyingResults->contains(fn($r) => $r->is_dnf);
            $heatDns = $heatResults->contains(fn($r) => $r->is_dns);
            $heatDq = $heatResults->contains(fn($r) => $r->is_dnf);
            $amainDns = $amainResults->contains(fn($r) => $r->is_dns);
            $amainDq = $amainResults->contains(fn($r) => $r->is_dnf);

            return [
                'entry' => $entry,
                'total_points' => $results->sum('points_earned'),
                'qualifying_points' => $qualifyingResults->isNotEmpty() ? $qualifyingPoints : null,
                'qualifying_status' => $qualifyingDns ? 'DNS' : ($qualifyingDq ? 'DQ' : null),
                'heat_points' => $heatResults->isNotEmpty() ? $heatPoints : null,
                'heat_status' => $heatDns ? 'DNS' : ($heatDq ? 'DQ' : null),
                'amain_points' => $amainResults->isNotEmpty() ? $amainPoints : null,
                'amain_status' => $amainDns ? 'DNS' : ($amainDq ? 'DQ' : null),
                'qualifying_time' => ($qualifyingResult?->is_dns || $qualifyingResult?->is_dnf) ? null : $qualifyingResult?->time,
            ];
        })->sort(function ($a, $b) {
            // Primary: total points descending
            if ($a['total_points'] !== $b['total_points']) {
                return $b['total_points'] - $a['total_points'];
            }
            // Tiebreaker: qualifying time ascending (faster = better)
            $timeA = $a['qualifying_time'] ?? '99:99.999';
            $timeB = $b['qualifying_time'] ?? '99:99.999';
            return strcmp($timeA, $timeB);
        })->values();
    }
}

