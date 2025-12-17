<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ForcePasswordChange extends Component
{
    public string $password = '';
    public string $password_confirmation = '';

    public function save(): void
    {
        $this->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->password = Hash::make($this->password);
        $user->must_change_password = false;
        $user->save();

        session()->flash('message', 'Password changed successfully.');
        
        $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.force-password-change');
    }
}

