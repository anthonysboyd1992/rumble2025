<div class="space-y-6">
    <div class="bg-zinc-900 rounded-xl p-6 border border-zinc-800">
        <h2 class="text-xl font-bold text-amber-400 mb-4">Import Results</h2>

        <form wire:submit="import" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-2">Class</label>
                <select wire:model="raceClassId" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
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
                    class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white font-mono text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                ></textarea>
            </div>

            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-black font-bold py-3 px-6 rounded-lg transition-colors">
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
        <div class="bg-zinc-900 rounded-xl p-6 border border-zinc-800">
            <h3 class="text-lg font-bold text-zinc-100 mb-4">Last Import</h3>
            <div class="space-y-2">
                @foreach($importedResults as $item)
                    <div class="flex justify-between items-center text-sm py-2 border-b border-zinc-800">
                        <span class="text-amber-400 font-mono">{{ $item['entry']->car_number }}</span>
                        <span class="text-zinc-300">{{ $item['entry']->driver_name }}</span>
                        <span class="text-zinc-400">P{{ $item['result']->position }}</span>
                        <span class="text-green-400">+{{ $item['result']->points_earned }} pts</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

