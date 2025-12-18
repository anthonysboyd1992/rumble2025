<div class="rounded-xl p-6 border rumble-dark-bg rumble-border">
    <div class="mb-6">
        <div class="flex justify-center mb-4">
            <div class="flex gap-2">
                <button 
                    wire:click="$set('dayFilter', 'thursday')"
                    class="px-6 py-2 rounded-lg text-sm font-medium transition-colors {{ $dayFilter === 'thursday' ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-white rumble-dark-bg-hover' }}"
                >
                    Thursday
                </button>
                <button 
                    wire:click="$set('dayFilter', 'friday')"
                    class="px-6 py-2 rounded-lg text-sm font-medium transition-colors {{ $dayFilter === 'friday' ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-white rumble-dark-bg-hover' }}"
                >
                    Friday
                </button>
                <button 
                    wire:click="$set('dayFilter', 'saturday')"
                    class="px-6 py-2 rounded-lg text-sm font-medium transition-colors {{ $dayFilter === 'saturday' ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-white rumble-dark-bg-hover' }}"
                >
                    Saturday
                </button>
            </div>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold rumble-blue">Practice Times</h2>
            </div>
            
            <div class="flex justify-center">
                <div class="flex gap-2 flex-wrap">
                    @foreach($classes as $class)
                        @if($class->show_on_practice)
                            <button 
                                wire:click="$set('classFilter', {{ $class->id }})"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $classFilter === $class->id ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-white rumble-dark-bg-hover' }}"
                            >
                                {{ $class->name }}
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <div class="flex flex-wrap gap-2 items-center">
                <span class="text-zinc-400 text-sm font-medium mr-2">Print:</span>
                <a href="{{ route('print.qualifying', ['class' => $classFilter, 'day' => $dayFilter, 'inversion' => $fullInvert ? 'full' : ($inversionEnabled ? $inversionCount : null)]) }}" target="_blank" class="rumble-dark-bg-700 rumble-dark-bg-hover text-white text-sm px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                    Fastest Day
                </a>
                <a href="{{ route('print.qualifying.all.days', ['class' => $classFilter, 'inversion' => $fullInvert ? 'full' : ($inversionEnabled ? $inversionCount : null)]) }}" target="_blank" class="rumble-dark-bg-700 rumble-dark-bg-hover text-white text-sm px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                    Fastest All
                </a>
                <a href="{{ route('print.crossing.day', ['class' => $classFilter, 'day' => $dayFilter]) }}" target="_blank" class="rumble-dark-bg-700 rumble-dark-bg-hover text-white text-sm px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                    All Crossing Day
                </a>
                <a href="{{ route('print.all.crossings', ['class' => $classFilter]) }}" target="_blank" class="rumble-dark-bg-700 rumble-dark-bg-hover text-white text-sm px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                    All Crossings
                </a>
            </div>
            
            <div class="flex flex-wrap gap-2 items-center">
                <span class="text-zinc-400 text-sm font-medium mr-2">CSV:</span>
                <a href="{{ route('export.qualifying', ['class' => $classFilter, 'day' => $dayFilter, 'inversion' => $fullInvert ? 'full' : ($inversionEnabled ? $inversionCount : null)]) }}" class="rumble-dark-bg-700 rumble-dark-bg-hover text-white text-sm px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                    Fastest Day
                </a>
                <a href="{{ route('export.qualifying.all.days', ['class' => $classFilter, 'inversion' => $fullInvert ? 'full' : ($inversionEnabled ? $inversionCount : null)]) }}" class="rumble-dark-bg-700 rumble-dark-bg-hover text-white text-sm px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                    Fastest All
                </a>
                <a href="{{ route('export.crossing.day', ['class' => $classFilter, 'day' => $dayFilter]) }}" class="rumble-dark-bg-700 rumble-dark-bg-hover text-white text-sm px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                    All Crossing Day
                </a>
                <a href="{{ route('export.all.crossings', ['class' => $classFilter]) }}" class="rumble-dark-bg-700 rumble-dark-bg-hover text-white text-sm px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                    All Crossings
                </a>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-4 mb-4 p-3 rumble-dark-bg-700 rounded-lg flex-wrap">
        <button 
            wire:click="toggleFullInvert"
            class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ $fullInvert ? 'rumble-blue-bg text-white' : 'rumble-dark-bg text-white rumble-dark-bg-hover' }}"
        >
            Full Invert {{ $fullInvert ? 'ON' : 'OFF' }}
        </button>
        <button 
            wire:click="toggleInversion"
            class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ $inversionEnabled ? 'rumble-blue-bg text-white' : 'rumble-dark-bg text-white rumble-dark-bg-hover' }}"
        >
            Inversion {{ $inversionEnabled ? 'ON' : 'OFF' }}
        </button>
        <div class="flex items-center gap-2">
            <label class="text-zinc-400 text-sm">Top</label>
            <input 
                type="number" 
                wire:model.live.debounce.500ms="inversionCount" 
                min="2" 
                max="50"
                class="w-16 rumble-dark-bg rumble-border rounded px-2 py-1 text-white text-sm text-center"
                {{ $fullInvert ? 'disabled' : '' }}
            >
            <span class="text-zinc-400 text-sm">inverted</span>
        </div>
        @if($fullInvert)
            <span class="rumble-blue text-sm">(Entire list reversed)</span>
        @elseif($inversionEnabled)
            <span class="rumble-blue text-sm">(1st → {{ $inversionCount }}th, {{ $inversionCount }}th → 1st)</span>
        @endif
    </div>

    <div class="mb-4 p-3 bg-zinc-800/50 rounded-lg flex items-center gap-3">
        <label class="text-zinc-400 text-sm">Track Length (miles):</label>
        <input 
            type="text" 
            wire:model.live="trackLength" 
            class="w-24 bg-zinc-700 border border-zinc-600 rounded px-2 py-1 text-white text-sm text-center font-mono"
            placeholder="0.142857"
        >
        <span class="text-zinc-500 text-xs">(1/7 mile = 0.142857)</span>
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
        <p class="text-zinc-500 text-center py-8">No qualifying times yet. Import a CSV to get started.</p>
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
                            <span class="font-mono font-bold text-lg rumble-blue">{{ $standing['car_number'] }}</span>
                            <span class="text-zinc-200">{{ $standing['driver_name'] }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-zinc-500 text-xs">{{ $standing['session_name'] }}</span>
                            <span class="text-zinc-400 text-sm">{{ $standing['lap_count'] }} times</span>
                            <span class="font-mono text-green-400 font-bold">{{ \App\Helpers\TimeFormatter::format($standing['best_time']) }}</span>
                            @php
                                $speed = \App\Helpers\TimeFormatter::calculateSpeed($standing['best_time'], $trackLength);
                            @endphp
                            @if($speed)
                                <span class="text-blue-400 text-sm font-mono">{{ $speed }} mph</span>
                            @endif
                            <svg class="w-5 h-5 text-zinc-400 transition-transform {{ $expandedDriver === $index ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    @if($expandedDriver === $index)
                        <div class="border-t p-4 max-h-64 overflow-y-auto rumble-border rumble-dark-bg-900">
                            <table class="w-full text-sm">
<thead>
                                        <tr class="text-left text-xs uppercase text-zinc-500 border-b border-zinc-800">
                                            <th class="pb-2 pr-4">#</th>
                                            <th class="pb-2 pr-4">Session</th>
                                            <th class="pb-2 pr-4">Lap</th>
                                            <th class="pb-2 text-right">Time</th>
                                            <th class="pb-2 text-right">Speed</th>
                                            <th class="pb-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($standing['all_times'] as $i => $time)
                                            @php
                                                $speed = \App\Helpers\TimeFormatter::calculateSpeed($time['time'], $trackLength);
                                            @endphp
                                            <tr class="border-b border-zinc-800/50">
                                                <td class="py-1.5 pr-4 text-zinc-500">{{ $i + 1 }}</td>
                                                <td class="py-1.5 pr-4 text-zinc-400">{{ $time['session'] }}</td>
                                                <td class="py-1.5 pr-4 text-zinc-500">{{ $time['lap'] ?? '-' }}</td>
                                                <td class="py-1.5 text-right font-mono {{ ($time['is_best'] ?? false) ? 'text-green-400 font-bold' : 'text-zinc-300' }}">
                                                    {{ \App\Helpers\TimeFormatter::format($time['time']) }}
                                                </td>
                                                <td class="py-1.5 text-right text-blue-400 font-mono text-xs">
                                                    @if($speed)
                                                        {{ $speed }} mph
                                                    @else
                                                        <span class="text-zinc-600">-</span>
                                                    @endif
                                                </td>
                                                <td class="py-1.5 pl-4 text-right">
                                                    @if($time['id'])
                                                        @if($time['source'] === 'crossing')
                                                            <button wire:click="deleteCrossing({{ $time['id'] }})" wire:confirm="Delete this time?" class="text-red-400 hover:text-red-300 text-xs">×</button>
                                                        @elseif($time['source'] === 'qualifying')
                                                            <button wire:click="deleteQualifyingTime({{ $time['id'] }})" wire:confirm="Delete this time?" class="text-red-400 hover:text-red-300 text-xs">×</button>
                                                        @endif
                                                    @endif
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

