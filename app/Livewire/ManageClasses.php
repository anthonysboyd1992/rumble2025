<?php

namespace App\Livewire;

use App\Models\RaceClass;
use Livewire\Component;

class ManageClasses extends Component
{
    public string $newClassName = '';
    public ?int $editingId = null;
    public string $editingName = '';
    public int $editingOrder = 0;

    public function create(): void
    {
        $this->validate(['newClassName' => 'required|string|max:255']);
        $maxOrder = RaceClass::max('sort_order') ?? 0;
        RaceClass::create(['name' => $this->newClassName, 'sort_order' => $maxOrder + 1]);
        $this->newClassName = '';
        $this->dispatch('classes-updated');
    }

    public function edit(int $id): void
    {
        $class = RaceClass::find($id);
        $this->editingId = $id;
        $this->editingName = $class->name;
        $this->editingOrder = $class->sort_order;
    }

    public function update(): void
    {
        $this->validate(['editingName' => 'required|string|max:255']);
        RaceClass::find($this->editingId)->update(['name' => $this->editingName, 'sort_order' => $this->editingOrder]);
        $this->editingId = null;
        $this->editingName = '';
        $this->editingOrder = 0;
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

    public function reorder(array $ids): void
    {
        foreach ($ids as $index => $id) {
            RaceClass::where('id', $id)->update(['sort_order' => $index + 1]);
        }
        $this->dispatch('classes-updated');
    }

    public function toggleVisibility(int $id, string $type): void
    {
        $class = RaceClass::find($id);
        if ($type === 'leaderboard') {
            $class->update(['show_on_leaderboard' => !$class->show_on_leaderboard]);
        } else {
            $class->update(['show_on_practice' => !$class->show_on_practice]);
        }
        $this->dispatch('classes-updated');
    }

    public function render()
    {
        return view('livewire.manage-classes', [
            'classes' => RaceClass::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }
}

