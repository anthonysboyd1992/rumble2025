<?php

namespace App\Livewire;

use App\Models\RaceClass;
use Livewire\Component;

class ManageClasses extends Component
{
    public string $newClassName = '';
    public ?int $editingId = null;
    public string $editingName = '';

    public function create(): void
    {
        $this->validate(['newClassName' => 'required|string|max:255']);
        RaceClass::create(['name' => $this->newClassName]);
        $this->newClassName = '';
        $this->dispatch('classes-updated');
    }

    public function edit(int $id): void
    {
        $class = RaceClass::find($id);
        $this->editingId = $id;
        $this->editingName = $class->name;
    }

    public function update(): void
    {
        $this->validate(['editingName' => 'required|string|max:255']);
        RaceClass::find($this->editingId)->update(['name' => $this->editingName]);
        $this->editingId = null;
        $this->editingName = '';
        $this->dispatch('classes-updated');
    }

    public function cancel(): void
    {
        $this->editingId = null;
        $this->editingName = '';
    }

    public function delete(int $id): void
    {
        RaceClass::find($id)->delete();
        $this->dispatch('classes-updated');
    }

    public function render()
    {
        return view('livewire.manage-classes', [
            'classes' => RaceClass::orderBy('name')->get(),
        ]);
    }
}

