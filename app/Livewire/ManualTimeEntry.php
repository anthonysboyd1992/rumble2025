<?php

namespace App\Livewire;

use App\Models\QualifyingTime;
use App\Models\RaceClass;
use Livewire\Attributes\On;
use Livewire\Component;

class ManualTimeEntry extends Component
{
    public ?int $raceClassId = null;
    public string $day = 'friday';
    public string $sessionName = '';
    public string $carNumber = '';
    public string $driverName = '';
    public string $fastTime = '';
    public ?int $laps = null;
    public ?int $fastLap = null;
    public string $message = '';

    #[On('classes-updated')]
    public function refreshClasses(): void {}

    public function save(): void
    {
        $this->validate([
            'raceClassId' => 'required|exists:race_classes,id',
            'day' => 'required|in:thursday,friday,saturday',
            'sessionName' => 'required|string|max:255',
            'carNumber' => 'required|string|max:20',
            'driverName' => 'required|string|max:255',
            'fastTime' => 'required|string|max:20',
        ]);

        QualifyingTime::create([
            'race_class_id' => $this->raceClassId,
            'day' => $this->day,
            'session_name' => $this->sessionName,
            'car_number' => trim($this->carNumber),
            'driver_name' => trim($this->driverName),
            'fast_time' => trim($this->fastTime),
            'laps' => $this->laps,
            'fast_lap' => $this->fastLap,
        ]);

        $this->message = "Added time for #{$this->carNumber}";
        $this->carNumber = '';
        $this->driverName = '';
        $this->fastTime = '';
        $this->laps = null;
        $this->fastLap = null;
        
        $this->dispatch('qualifying-imported');
    }

    public function render()
    {
        return view('livewire.manual-time-entry', [
            'classes' => RaceClass::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }
}

