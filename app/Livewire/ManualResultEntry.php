<?php

namespace App\Livewire;

use App\Events\StandingsUpdated;
use App\Models\Entry;
use App\Models\RaceClass;
use App\Models\Result;
use App\Models\Session;
use App\Services\PointsCalculator;
use Livewire\Attributes\On;
use Livewire\Component;

class ManualResultEntry extends Component
{
    public ?int $raceClassId = null;
    public string $day = 'friday';
    public string $sessionName = '';
    public string $sessionType = 'heat';
    public string $carNumber = '';
    public int $position = 1;
    public ?int $startingPosition = null;
    public bool $isDns = false;
    public bool $isDnf = false;
    public string $message = '';
    public ?string $matchedDriverName = null;

    public function updatedCarNumber(): void
    {
        $this->lookupDriver();
    }

    public function updatedRaceClassId(): void
    {
        $this->lookupDriver();
    }

    private function lookupDriver(): void
    {
        $this->matchedDriverName = null;
        if ($this->carNumber && $this->raceClassId) {
            $entry = Entry::where('car_number', trim($this->carNumber))
                ->where('race_class_id', $this->raceClassId)
                ->first();
            $this->matchedDriverName = $entry?->driver_name;
        }
    }

    #[On('classes-updated')]
    public function refreshClasses(): void {}

    #[On('day-changed')]
    public function updateDay(string $day): void
    {
        $this->day = $day;
    }

    public function save(): void
    {
        $this->validate([
            'raceClassId' => 'required|exists:race_classes,id',
            'day' => 'required|in:thursday,friday,saturday',
            'sessionName' => 'required|string|max:255',
            'sessionType' => 'required|in:qualifying,heat,amain',
            'carNumber' => 'required|string|max:20',
            'position' => 'required|integer|min:1',
        ]);

        $entry = Entry::where('car_number', trim($this->carNumber))
            ->where('race_class_id', $this->raceClassId)
            ->first();

        if (!$entry) {
            $this->message = "Entry #{$this->carNumber} not found in this class.";
            return;
        }

        $session = Session::firstOrCreate([
            'name' => $this->sessionName,
            'day' => $this->day,
            'race_class_id' => $this->raceClassId,
        ], [
            'type' => $this->sessionType,
        ]);

        $calculator = new PointsCalculator();
        $points = $calculator->calculate($this->sessionType, $this->position, $this->isDns, $this->isDnf);

        Result::updateOrCreate([
            'entry_id' => $entry->id,
            'race_session_id' => $session->id,
        ], [
            'position' => $this->position,
            'starting_position' => $this->startingPosition,
            'points_earned' => $points,
            'is_dns' => $this->isDns,
            'is_dnf' => $this->isDnf,
        ]);

        $this->message = "Added result for #{$this->carNumber} - P{$this->position} (+{$points} pts)";
        $this->carNumber = '';
        $this->position = 1;
        $this->startingPosition = null;
        $this->isDns = false;
        $this->isDnf = false;

        $this->dispatch('results-imported');
        event(new StandingsUpdated());
    }

    public function render()
    {
        return view('livewire.manual-result-entry', [
            'classes' => RaceClass::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }
}

