<?php

namespace App\Livewire;

use App\Events\StandingsUpdated;
use App\Models\Entry;
use App\Models\Result;
use App\Models\Session;
use Livewire\Component;

class ResetData extends Component
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
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Result::truncate();
        Session::truncate();
        Entry::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->showConfirm = false;
        $this->message = 'All race data has been cleared.';

        $this->dispatch('results-imported');

        event(new StandingsUpdated());

        $this->js('window.location.reload()');
    }

    public function render()
    {
        return view('livewire.reset-data');
    }
}

