<div class="space-y-6">
    <div class="rumble-dark-bg rounded-xl p-6 rumble-border">
        <h2 class="text-xl font-bold rumble-blue mb-4">Import Results</h2>

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
                <label class="block text-sm font-medium text-zinc-300 mb-2">Paste Results</label>
                <textarea
                    wire:model="rawText"
                    rows="10"
                    placeholder="Heat 1 8 Laps | 00:05:32.000 | Engler Machine & Tool
1. 12-Corbin Gurley[2]; 2. 71H-Max Stambaugh[1]; 3. 16-Ryan Ruhl[3]; ..."
                    class="w-full rumble-dark-bg-700 rumble-border rounded-lg px-4 py-3 text-white font-mono text-sm focus:ring-2" style="--tw-ring-color: hsl(206.07deg 75.92% 37.45%);" onfocus="this.style.borderColor='hsl(206.07deg 75.92% 37.45%)'" onblur="this.style.borderColor='hsl(312deg 7.69% 18%)'"
                ></textarea>
            </div>

            <button type="submit" class="w-full rumble-blue-bg text-white font-bold py-3 px-6 rounded-lg transition-colors">
                Import Results
            </button>
        </form>

        @if($message)
            <div class="mt-4 p-4 rounded-lg {{ str_contains($message, 'Successfully') ? 'bg-green-900/50 text-green-300' : 'bg-red-900/50 text-red-300' }}">
                {{ $message }}
            </div>
        @endif
    </div>

    @if(count($importedResults) > 0)
        <div class="rumble-dark-bg rounded-xl p-6 rumble-border">
            <h3 class="text-lg font-bold text-white mb-4">Last Import</h3>
            <div class="space-y-2">
                @foreach($importedResults as $item)
                    <div class="flex justify-between items-center text-sm py-2 border-b rumble-border">
                        <span class="rumble-blue font-mono">{{ $item['entry']->car_number }}</span>
                        <span class="text-zinc-300">{{ $item['entry']->driver_name }}</span>
                        <span class="text-zinc-400">P{{ $item['result']->position }}</span>
                        <span class="text-green-400">+{{ $item['result']->points_earned }} pts</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

