<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserManagement extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public ?int $editingId = null;

    public function save(): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email' . ($this->editingId ? ',' . $this->editingId : ''),
        ];

        if (!$this->editingId) {
            $rules['password'] = 'required|min:8';
        }

        $this->validate($rules);

        if ($this->editingId) {
            $user = User::find($this->editingId);
            $user->name = $this->name;
            $user->email = $this->email;
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }
            $user->save();
            session()->flash('message', 'User updated successfully.');
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            session()->flash('message', 'User created successfully.');
        }

        $this->reset(['name', 'email', 'password', 'editingId']);
    }

    public function edit(int $id): void
    {
        $user = User::find($id);
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
    }

    public function cancelEdit(): void
    {
        $this->reset(['name', 'email', 'password', 'editingId']);
    }

    public function delete(int $id): void
    {
        if (User::count() <= 1) {
            session()->flash('error', 'Cannot delete the last user.');
            return;
        }

        if (auth()->id() === $id) {
            session()->flash('error', 'Cannot delete yourself.');
            return;
        }

        User::destroy($id);
        session()->flash('message', 'User deleted successfully.');
    }

    public function render()
    {
        return view('livewire.user-management', [
            'users' => User::orderBy('name')->get(),
        ]);
    }
}

