<?php

namespace App\Livewire;

use App\Models\CrossingTime;
use App\Models\QualifyingTime;
use Livewire\Component;

class ResetQualifying extends Component
{
    public bool $showConfirm = false;
    public string $message = '';

    public function confirmReset(): void
    {
        $this->showConfirm = true;
    }

    public function cancelReset(): void
    {
        $this->showConfirm = false;
    }

    public function clearAllData(): void
    {
        QualifyingTime::truncate();
        CrossingTime::truncate();

        $this->showConfirm = false;
        $this->message = 'All qualifying and crossing times have been cleared.';

        $this->dispatch('qualifying-imported');
        $this->dispatch('crossing-imported');
    }

    public function render()
    {
        return view('livewire.reset-qualifying');
    }
}

