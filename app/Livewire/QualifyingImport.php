<?php

namespace App\Livewire;

use App\Models\QualifyingTime;
use App\Models\RaceClass;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class QualifyingImport extends Component
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
            $fastTime = $data['fast time'] ?? $data['fasttime'] ?? null;
            
            if (!$carNumber || !$fastTime) continue;

            QualifyingTime::create([
                'race_class_id' => $this->raceClassId,
                'day' => $this->day,
                'session_name' => $this->sessionName,
                'car_number' => trim($carNumber),
                'driver_name' => trim($data['name'] ?? ''),
                'tx_id' => $data['tx id'] ?? $data['txid'] ?? null,
                'place' => isset($data['place']) ? (int) $data['place'] : null,
                'laps' => isset($data['laps']) ? (int) $data['laps'] : null,
                'adjust' => $data['adjust'] ?? null,
                'last_time' => $data['lasttime'] ?? $data['last time'] ?? null,
                'fast_time' => trim($fastTime),
                'fast_lap' => isset($data['fast lap']) ? (int) $data['fast lap'] : null,
                'misc' => $data['misc'] ?? null,
            ]);

            $this->importedCount++;
        }

        $this->message = "Imported {$this->importedCount} qualifying times.";
        $this->csvFile = null;
        $this->dispatch('qualifying-imported');
    }

    public function render()
    {
        return view('livewire.qualifying-import', [
            'classes' => RaceClass::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }
}

