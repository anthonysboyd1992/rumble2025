<?php

namespace App\Livewire;

use App\Events\StandingsUpdated;
use App\Models\RaceClass;
use App\Services\ResultParser;
use Livewire\Attributes\On;
use Livewire\Component;

class ImportResults extends Component
{
    #[On('classes-updated')]
    public function refreshClasses(): void
    {
        // Will re-render with updated classes
    }
    public string $rawText = '';
    public ?int $raceClassId = null;
    public string $day = 'friday';
    public array $importedResults = [];
    public string $message = '';

    public function import(): void
    {
        if (empty(trim($this->rawText))) {
            $this->message = 'Please paste results to import.';
            return;
        }

        if (empty($this->raceClassId)) {
            $this->message = 'Please select a class.';
            return;
        }

        $parser = new ResultParser();
        $this->importedResults = $parser->parse($this->rawText, $this->day, $this->raceClassId);

        $count = count($this->importedResults);
        $this->message = "Successfully imported {$count} results.";
        $this->rawText = '';

        $this->dispatch('results-imported');

        // Broadcast to public leaderboard
        event(new StandingsUpdated());
    }

    public function render()
    {
        return view('livewire.import-results', [
            'classes' => RaceClass::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }
}

