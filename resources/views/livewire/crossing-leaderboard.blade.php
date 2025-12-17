<div class="rumble-dark-bg rounded-xl p-6 rumble-border">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold rumble-blue">Crossing Times</h2>
        <select wire:model.live="classFilter" class="bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-sm text-white">
            <option value="">All Classes</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>

    @if($this->sessions->isNotEmpty())
        <div class="mb-4 flex flex-wrap gap-2">
            <span class="text-zinc-400 text-sm">Sessions:</span>
            @foreach($this->sessions as $session)
                <span class="px-2 py-1 bg-zinc-800 rounded text-xs text-zinc-300">{{ $session }}</span>
            @endforeach
        </div>
    @endif

    @if($this->standings->isEmpty())
        <p class="text-zinc-500 text-center py-8">No crossing times yet. Import a CSV to get started.</p>
    @else
        <div class="space-y-2">
            @foreach($this->standings as $index => $standing)
                <div class="border border-zinc-700 rounded-lg overflow-hidden">
                    <button 
                        wire:click="toggleDriver({{ $index }})"
                        class="w-full flex items-center justify-between px-4 py-3 transition-colors {{ $expandedDriver === $index ? 'rumble-blue-bg-light' : 'rumble-dark-bg-700 rumble-dark-bg-hover' }}"
                    >
                        <div class="flex items-center gap-4">
                            <span class="text-zinc-400 w-8">{{ $index + 1 }}</span>
                            <span class="font-mono font-bold text-amber-400 text-lg">{{ $standing['car_number'] }}</span>
                            <span class="text-zinc-200">{{ $standing['driver_name'] }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-zinc-400 text-sm">{{ $standing['lap_count'] }} laps</span>
                            <span class="font-mono text-green-400 font-bold">{{ $standing['laptime'] }}</span>
                            <span class="text-zinc-500 text-xs">({{ $standing['session_name'] }})</span>
                            <svg class="w-5 h-5 text-zinc-400 transition-transform {{ $expandedDriver === $index ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    @if($expandedDriver === $index)
                        <div class="border-t border-zinc-700 bg-zinc-900 p-4 max-h-64 overflow-y-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-xs uppercase text-zinc-500 border-b border-zinc-800">
                                        <th class="pb-2 pr-4">#</th>
                                        <th class="pb-2 pr-4">Session</th>
                                        <th class="pb-2 text-right">Lap Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($standing['all_times'] as $i => $time)
                                        <tr class="border-b border-zinc-800/50">
                                            <td class="py-1.5 pr-4 text-zinc-500">{{ $i + 1 }}</td>
                                            <td class="py-1.5 pr-4 text-zinc-400">{{ $time['session'] }}</td>
                                            <td class="py-1.5 text-right font-mono {{ $i === 0 ? 'text-green-400 font-bold' : 'text-zinc-300' }}">
                                                {{ $time['time'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <p class="text-center text-zinc-600 text-sm mt-4">
            {{ $this->standings->count() }} drivers
        </p>
    @endif
</div>

