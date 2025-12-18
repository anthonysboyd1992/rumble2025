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
        $driver = \DB::connection()->getDriverName();
        
        if ($driver === 'mysql') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } elseif ($driver === 'sqlite') {
            \DB::statement('PRAGMA foreign_keys = OFF;');
        }
        
        Result::truncate();
        Session::truncate();
        Entry::truncate();
        
        if ($driver === 'mysql') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } elseif ($driver === 'sqlite') {
            \DB::statement('PRAGMA foreign_keys = ON;');
        }

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

