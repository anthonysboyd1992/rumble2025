<div class="rumble-dark-bg rounded-xl p-6 rumble-border">
    <h2 class="text-xl font-bold rumble-blue mb-4">Add Practice Time</h2>

    <form wire:submit="save" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
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
        </div>

        <div>
            <label class="block text-sm font-medium text-zinc-300 mb-2">Session Name</label>
            <input type="text" wire:model="sessionName" placeholder="e.g., Practice 1" class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white">
        </div>

        <div class="grid grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-2">Car #</label>
                <input type="text" wire:model="carNumber" placeholder="12" class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-2">Driver Name</label>
                <input type="text" wire:model="driverName" placeholder="John Doe" class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-2">Fast Time</label>
                <input type="text" wire:model="fastTime" placeholder="12.345" class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-2">Lap #</label>
                <input type="number" wire:model="fastLap" min="1" placeholder="1" class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white">
            </div>
        </div>

        <button type="submit" class="w-full rumble-blue-bg text-white font-bold py-3 px-6 rounded-lg transition-colors">
            Add Time
        </button>
    </form>

    @if($message)
        <div class="mt-4 p-4 rounded-lg bg-green-900/50 text-green-300">
            {{ $message }}
        </div>
    @endif
</div>

