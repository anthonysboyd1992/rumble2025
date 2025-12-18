<?php

namespace App\Livewire;

use App\Events\StandingsUpdated;
use App\Models\QualifyingTime;
use App\Models\RaceClass;
use App\Models\Result;
use App\Models\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ManageResults extends Component
{
    public ?int $classFilter = null;
    public string $dayFilter = 'friday';
    public string $tab = 'results';

    #[On('classes-updated')]
    #[On('results-imported')]
    #[On('qualifying-imported')]
    public function refresh(): void {}

    public function mount(): void
    {
        $firstClass = RaceClass::orderBy('sort_order')->first();
        $this->classFilter = $firstClass?->id;
    }

    #[Computed]
    public function classes()
    {
        return RaceClass::orderBy('sort_order')->orderBy('name')->get();
    }

    #[Computed]
    public function sessions()
    {
        return Session::where('race_class_id', $this->classFilter)
            ->where('day', $this->dayFilter)
            ->with(['results.entry'])
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function qualifyingTimes()
    {
        return QualifyingTime::where('race_class_id', $this->classFilter)
            ->where('day', $this->dayFilter)
            ->orderBy('session_name')
            ->orderBy('fast_time')
            ->get();
    }

    public function deleteResult(int $resultId): void
    {
        Result::find($resultId)?->delete();
        $this->dispatch('results-imported');
        event(new StandingsUpdated());
    }

    public function deleteSession(int $sessionId): void
    {
        $session = Session::find($sessionId);
        if ($session) {
            $session->results()->delete();
            $session->delete();
        }
        $this->dispatch('results-imported');
        event(new StandingsUpdated());
    }

    public function deleteQualifyingTime(int $timeId): void
    {
        QualifyingTime::find($timeId)?->delete();
        $this->dispatch('qualifying-imported');
    }

    public function deleteQualifyingSession(string $sessionName): void
    {
        QualifyingTime::where('race_class_id', $this->classFilter)
            ->where('day', $this->dayFilter)
            ->where('session_name', $sessionName)
            ->delete();
        $this->dispatch('qualifying-imported');
    }

    public function render()
    {
        return view('livewire.manage-results');
    }
}

