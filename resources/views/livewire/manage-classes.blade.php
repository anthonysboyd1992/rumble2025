<div class="bg-zinc-900 rounded-xl p-6 border border-zinc-800">
    <h2 class="text-xl font-bold text-amber-400 mb-4">Race Classes</h2>

    <form wire:submit="create" class="flex gap-2 mb-4">
        <input 
            type="text" 
            wire:model="newClassName" 
            placeholder="New class name..."
            class="flex-1 bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent"
        >
        <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-black font-bold px-4 py-2 rounded-lg transition-colors text-sm">
            Add
        </button>
    </form>

    @if($classes->isEmpty())
        <p class="text-zinc-500 text-sm">No classes yet.</p>
    @else
        <div class="space-y-2">
            @foreach($classes as $class)
                <div class="flex items-center justify-between bg-zinc-800/50 rounded-lg px-3 py-2">
                    @if($editingId === $class->id)
                        <form wire:submit="update" class="flex-1 flex gap-2">
                            <input 
                                type="text" 
                                wire:model="editingName"
                                class="flex-1 bg-zinc-700 border border-zinc-600 rounded px-2 py-1 text-white text-sm"
                            >
                            <button type="submit" class="text-green-400 hover:text-green-300 text-sm">Save</button>
                            <button type="button" wire:click="cancel" class="text-zinc-400 hover:text-zinc-300 text-sm">Cancel</button>
                        </form>
                    @else
                        <span class="text-zinc-200">{{ $class->name }}</span>
                        <div class="flex gap-2">
                            <button wire:click="edit({{ $class->id }})" class="text-zinc-400 hover:text-amber-400 text-sm">Edit</button>
                            <button wire:click="delete({{ $class->id }})" wire:confirm="Delete this class?" class="text-zinc-400 hover:text-red-400 text-sm">Delete</button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

