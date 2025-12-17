<?php

namespace App\Livewire;

use App\Models\CrossingTime;
use App\Models\RaceClass;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class CrossingImport extends Component
{
    use WithFileUploads;

    public $csvFile;
    public ?int $raceClassId = null;
    public string $day = 'friday';
    public string $sessionName = '';
    public string $message = '';
    public int $importedCount = 0;

    #[On('classes-updated')]
    public function refreshClasses(): void
    {
    }

    #[On('day-changed')]
    public function updateDay(string $day): void
    {
        $this->day = $day;
    }

    public function import(): void
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt',
            'raceClassId' => 'required|exists:race_classes,id',
            'day' => 'required|in:thursday,friday,saturday',
            'sessionName' => 'required|string|max:255',
        ]);

        $path = $this->csvFile->getRealPath();
        $rows = array_map('str_getcsv', file($path));
        $header = array_shift($rows);

        // Normalize header names
        $header = array_map(fn($h) => strtolower(trim($h)), $header);

        $this->importedCount = 0;

        foreach ($rows as $row) {
            if (count($row) < count($header)) continue;
            
            $data = array_combine($header, $row);
            
            $carNumber = $data['no.'] ?? $data['no'] ?? null;
            $laptime = $data['laptime'] ?? $data['lap time'] ?? null;
            
            if (!$carNumber) continue;
            
            // Skip if laptime is empty or ---
            if (!$laptime || $laptime === '---' || trim($laptime) === '') {
                continue;
            }

            // Parse index which may contain session info like "1-Pre (18:49:55)"
            $indexRaw = $data['index'] ?? '';
            $indexNum = null;
            if (preg_match('/^(\d+)/', $indexRaw, $matches)) {
                $indexNum = (int) $matches[1];
            }

            $lap = $data['lap'] ?? null;
            if ($lap && $lap !== '---') {
                $lap = (int) $lap;
            } else {
                $lap = null;
            }

            CrossingTime::create([
                'race_class_id' => $this->raceClassId,
                'day' => $this->day,
                'session_name' => $this->sessionName,
                'index' => $indexNum,
                'car_number' => trim($carNumber),
                'driver_name' => trim($data['name'] ?? ''),
                'trns_id' => $data['trns id'] ?? $data['trnsid'] ?? null,
                'lap' => $lap,
                'laptime' => trim($laptime),
                'speed' => $data['speed'] ?? null,
                'hits_power' => $data['hits/power'] ?? $data['hits'] ?? $data['power'] ?? null,
                'misc' => $data['misc'] ?? null,
            ]);

            $this->importedCount++;
        }

        $this->message = "Imported {$this->importedCount} crossing times.";
        $this->csvFile = null;
        $this->dispatch('crossing-imported');
    }

    public function render()
    {
        return view('livewire.crossing-import', [
            'classes' => RaceClass::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }
}

