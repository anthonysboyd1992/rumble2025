<div class="rumble-dark-bg rounded-xl p-6 rumble-border">
    <h2 class="text-xl font-bold rumble-blue mb-4">Add Result</h2>

    <form wire:submit="save" class="space-y-4">
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-2">Day</label>
                <select wire:model="day" class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white">
                    <option value="thursday">Thursday</option>
                    <option value="friday">Friday</option>
                    <option value="saturday">Saturday</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-2">Class</label>
                <select wire:model="raceClassId" class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white">
                    <option value="">Select...</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-2">Type</label>
                <select wire:model="sessionType" class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white">
                    <option value="qualifying">Qualifying</option>
                    <option value="heat">Heat</option>
                    <option value="amain">A-Main</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-zinc-300 mb-2">Session Name</label>
            <input type="text" wire:model="sessionName" placeholder="e.g., Heat 1, A-Main" class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white">
        </div>

        <div class="grid grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-2">Car #</label>
                <input type="text" wire:model.live="carNumber" placeholder="12" class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white">
                @if($matchedDriverName)
                    <p class="text-green-400 text-xs mt-1">{{ $matchedDriverName }}</p>
                @elseif($carNumber && $raceClassId)
                    <p class="text-red-400 text-xs mt-1">Not found</p>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-2">Position</label>
                <input type="number" wire:model="position" min="1" class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-2">Start Pos</label>
                <input type="number" wire:model="startingPosition" min="1" placeholder="Optional" class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white">
            </div>
            <div class="flex items-end gap-4 pb-2">
                <label class="flex items-center gap-2 text-zinc-300">
                    <input type="checkbox" wire:model="isDns" class="rounded">
                    DNS
                </label>
                <label class="flex items-center gap-2 text-zinc-300">
                    <input type="checkbox" wire:model="isDnf" class="rounded">
                    DNF
                </label>
            </div>
        </div>

        <button type="submit" class="w-full rumble-blue-bg text-white font-bold py-3 px-6 rounded-lg transition-colors">
            Add Result
        </button>
    </form>

    @if($message)
        <div class="mt-4 p-4 rounded-lg {{ str_contains($message, 'not found') ? 'bg-red-900/50 text-red-300' : 'bg-green-900/50 text-green-300' }}">
            {{ $message }}
        </div>
    @endif
</div>

