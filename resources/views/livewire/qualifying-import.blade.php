<div class="rumble-dark-bg rounded-xl p-6 rumble-border">
    <h2 class="text-xl font-bold rumble-blue mb-4">Import Practice CSV</h2>

    <form wire:submit="import" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-zinc-300 mb-2">Day</label>
            <select wire:model="day" class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white focus:ring-2" style="--tw-ring-color: hsl(206.07deg 75.92% 37.45%);" onfocus="this.style.borderColor='hsl(206.07deg 75.92% 37.45%)'" onblur="this.style.borderColor='hsl(312deg 7.69% 18%)'">
                <option value="thursday">Thursday</option>
                <option value="friday">Friday</option>
                <option value="saturday">Saturday</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-zinc-300 mb-2">Class</label>
            <select wire:model="raceClassId" class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white focus:ring-2" style="--tw-ring-color: hsl(206.07deg 75.92% 37.45%);" onfocus="this.style.borderColor='hsl(206.07deg 75.92% 37.45%)'" onblur="this.style.borderColor='hsl(312deg 7.69% 18%)'">
                <option value="">Select a class...</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-zinc-300 mb-2">Session Name</label>
            <input 
                type="text" 
                wire:model="sessionName" 
                placeholder="e.g., Practice 1"
                class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white focus:ring-2" style="--tw-ring-color: hsl(206.07deg 75.92% 37.45%);" onfocus="this.style.borderColor='hsl(206.07deg 75.92% 37.45%)'" onblur="this.style.borderColor='hsl(312deg 7.69% 18%)'"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-zinc-300 mb-2">CSV File</label>
            <input 
                type="file" 
                wire:model="csvFile"
                accept=".csv,.txt"
                class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:rumble-blue-bg file:text-white file:font-medium"
            >
            <p class="text-zinc-500 text-xs mt-1">Columns: Place, No., Name, Tx ID, Laps, Adjust, LastTime, Fast Time, Fast Lap, Misc</p>
        </div>

        <button type="submit" class="w-full rumble-blue-bg text-white font-bold py-3 px-6 rounded-lg transition-colors">
            Import Practice Times
        </button>
    </form>

    @if($message)
        <div class="mt-4 p-4 rounded-lg bg-green-900/50 text-green-300">
            {{ $message }}
        </div>
    @endif
</div>

