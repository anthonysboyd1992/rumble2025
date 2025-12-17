<div class="bg-zinc-900 rounded-xl p-6 border border-zinc-800">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-amber-400">Standings</h2>
        <div class="flex gap-3 items-center">
            <select wire:model.live="classFilter" class="bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-sm text-white">
                <option value="">All Classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
            <a href="{{ route('print.standings', ['day' => 'friday', 'class' => $classFilter]) }}" target="_blank" class="bg-zinc-700 hover:bg-zinc-600 text-white text-sm px-3 py-1.5 rounded-lg transition-colors">
                Print
            </a>
            <a href="{{ route('export.standings', ['day' => 'friday', 'class' => $classFilter]) }}" class="bg-zinc-700 hover:bg-zinc-600 text-white text-sm px-3 py-1.5 rounded-lg transition-colors">
                CSV
            </a>
        </div>
    </div>

    <div class="flex items-center gap-4 mb-4 p-3 bg-zinc-800/50 rounded-lg">
        <button 
            wire:click="toggleInversion"
            class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ $inversionEnabled ? 'bg-amber-500 text-black' : 'bg-zinc-700 text-zinc-300 hover:bg-zinc-600' }}"
        >
            Inversion {{ $inversionEnabled ? 'ON' : 'OFF' }}
        </button>
        <div class="flex items-center gap-2">
            <label class="text-zinc-400 text-sm">Top</label>
            <input 
                type="number" 
                wire:model.live="inversionCount" 
                min="2" 
                max="50"
                class="w-16 bg-zinc-700 border border-zinc-600 rounded px-2 py-1 text-white text-sm text-center"
            >
            <span class="text-zinc-400 text-sm">inverted</span>
        </div>
        @if($inversionEnabled)
            <span class="text-amber-400 text-sm">(1st → {{ $inversionCount }}th, {{ $inversionCount }}th → 1st)</span>
        @endif
    </div>

    @if($standings->isEmpty())
        <p class="text-zinc-500 text-center py-8">No results yet. Import results to see standings.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs uppercase text-zinc-500 border-b border-zinc-800">
                        <th class="pb-3 pr-4">Pos</th>
                        <th class="pb-3 pr-4">Car</th>
                        <th class="pb-3 pr-4">Driver</th>
                        <th class="pb-3 pr-4 text-right">Time</th>
                        <th class="pb-3 pr-4 text-right">Qual</th>
                        <th class="pb-3 pr-4 text-right">Heats</th>
                        <th class="pb-3 pr-4 text-right">A-Main</th>
                        <th class="pb-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($standings as $index => $standing)
                        <tr class="border-b border-zinc-800/50 hover:bg-zinc-800/30 transition-colors">
                            <td class="py-3 pr-4 text-zinc-400">{{ $index + 1 }}</td>
                            <td class="py-3 pr-4 font-mono text-amber-400 font-bold">{{ $standing['entry']->car_number }}</td>
                            <td class="py-3 pr-4 text-zinc-200">{{ $standing['entry']->driver_name }}</td>
                            <td class="py-3 pr-4 text-right font-mono text-zinc-500 text-sm">{{ $standing['qualifying_time'] ?? '-' }}</td>
                            <td class="py-3 pr-4 text-right {{ $standing['qualifying_status'] ? 'text-red-400' : 'text-zinc-400' }}">
                                {{ $standing['qualifying_status'] ?? $standing['qualifying_points'] ?? '' }}
                            </td>
                            <td class="py-3 pr-4 text-right {{ $standing['heat_status'] ? 'text-red-400' : 'text-zinc-400' }}">
                                {{ $standing['heat_status'] ?? $standing['heat_points'] ?? '' }}
                            </td>
                            <td class="py-3 pr-4 text-right {{ $standing['amain_status'] ? 'text-red-400' : 'text-zinc-400' }}">
                                {{ $standing['amain_status'] ?? $standing['amain_points'] ?? '' }}
                            </td>
                            <td class="py-3 text-right text-green-400 font-bold text-lg">{{ $standing['total_points'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

