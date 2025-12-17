<?php

namespace App\Helpers;

class TimeFormatter
{
    public static function format(?string $time): string
    {
        if (!$time || $time === '---') {
            return '-';
        }

        // If time already has decimal, ensure 3 decimal places
        if (str_contains($time, '.')) {
            $parts = explode('.', $time);
            $decimal = str_pad($parts[1] ?? '0', 3, '0');
            return $parts[0] . '.' . substr($decimal, 0, 3);
        }

        // No decimal, add .000
        return $time . '.000';
    }

    /**
     * Calculate speed in MPH from lap time and track length
     * @param string|null $time Lap time in seconds (e.g., "12.345")
     * @param string|float $trackLength Track length in miles (e.g., 0.142857 for 1/7 mile)
     * @return string|null Speed in MPH formatted to 2 decimals
     */
    public static function calculateSpeed(?string $time, $trackLength): ?string
    {
        if (!$time || $time === '---' || !$trackLength) {
            return null;
        }

        $trackMiles = (float) $trackLength;
        if ($trackMiles <= 0) {
            return null;
        }

        // Parse time - could be "12.345" or "1:12.345"
        $seconds = self::parseTimeToSeconds($time);
        if ($seconds <= 0) {
            return null;
        }

        // Speed = distance / time, convert to hours
        $hours = $seconds / 3600;
        $speed = $trackMiles / $hours;

        return number_format($speed, 2);
    }

    /**
     * Parse time string to seconds
     * Handles formats like "12.345" or "1:12.345"
     */
    public static function parseTimeToSeconds(string $time): float
    {
        $time = trim($time);
        
        if (str_contains($time, ':')) {
            // Format: "M:SS.mmm" or "MM:SS.mmm"
            $parts = explode(':', $time);
            $minutes = (float) $parts[0];
            $seconds = (float) $parts[1];
            return ($minutes * 60) + $seconds;
        }

        // Format: "SS.mmm"
        return (float) $time;
    }
}

