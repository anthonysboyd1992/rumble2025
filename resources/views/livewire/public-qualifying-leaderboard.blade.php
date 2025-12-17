<div wire:poll.5s class="min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <header class="text-center mb-8">
            <x-rumble-logo />
            <p class="text-lg rumble-text-muted mt-2">Qualifying Times</p>
            
            <div class="mt-6 flex justify-center gap-3">
                <button 
                    wire:click="$set('dayFilter', 'thursday')"
                    class="px-6 py-3 rounded-lg text-base font-medium transition-colors {{ $dayFilter === 'thursday' ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-white rumble-dark-bg-hover' }}"
                >
                    Thursday
                </button>
                <button 
                    wire:click="$set('dayFilter', 'friday')"
                    class="px-6 py-3 rounded-lg text-base font-medium transition-colors {{ $dayFilter === 'friday' ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-white rumble-dark-bg-hover' }}"
                >
                    Friday
                </button>
                <button 
                    wire:click="$set('dayFilter', 'saturday')"
                    class="px-6 py-3 rounded-lg text-base font-medium transition-colors {{ $dayFilter === 'saturday' ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-white rumble-dark-bg-hover' }}"
                >
                    Saturday
                </button>
            </div>
            
            @if($this->classes->count() > 1)
                <div class="mt-4 flex justify-center gap-2">
                    @foreach($this->classes as $class)
                        <button 
                            wire:click="$set('classFilter', {{ $class->id }})"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $classFilter === $class->id ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-white rumble-dark-bg-hover' }}"
                        >
                            {{ $class->name }}
                        </button>
                    @endforeach
                </div>
            @endif
            
            <div class="mt-4 flex justify-center items-center gap-3">
                <label class="text-zinc-400 text-sm">Track Length (miles):</label>
                <input 
                    type="text" 
                    wire:model.live="trackLength" 
                    class="w-24 bg-zinc-800 border border-zinc-700 rounded px-2 py-1 text-white text-sm text-center font-mono"
                    placeholder="0.142857"
                >
            </div>
            <p class="text-xs text-zinc-600 mt-2">Live updates</p>
        </header>

        @if($this->standings->isEmpty())
            <div class="text-center py-16">
                <p class="text-zinc-500 text-xl">No qualifying times yet.</p>
                <p class="text-zinc-600 mt-2">Check back once qualifying begins!</p>
            </div>
        @else
            <div class="max-w-5xl mx-auto">
                <div class="bg-zinc-900/80 rounded-xl border border-zinc-800 overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-zinc-950 border-b-2 border-zinc-700">
                                <th class="py-3 px-4 text-left text-xs font-bold uppercase text-zinc-400">Pos</th>
                                <th class="py-3 px-4 text-left text-xs font-bold uppercase text-zinc-400">Car</th>
                                <th class="py-3 px-4 text-left text-xs font-bold uppercase text-zinc-400">Driver</th>
                                <th class="py-3 px-4 text-right text-xs font-bold uppercase text-zinc-400">Best Time</th>
                                <th class="py-3 px-4 text-left text-xs font-bold uppercase text-zinc-400">Session</th>
                                <th class="py-3 px-4 text-right text-xs font-bold uppercase text-zinc-400">Speed</th>
                                <th class="py-3 px-4 text-center text-xs font-bold uppercase text-zinc-400"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->standings as $index => $standing)
                                <tr class="border-b border-zinc-800/50 {{ $index % 2 === 0 ? 'bg-zinc-900/50' : 'bg-zinc-900' }}">
                                    <td class="py-4 px-4 text-zinc-400 font-bold text-lg">{{ $index + 1 }}</td>
                                    <td class="py-4 px-4">
                                        <span class="font-mono text-3xl rumble-blue font-bold">{{ $standing['car_number'] }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-zinc-200 text-lg font-medium">{{ $standing['driver_name'] }}</td>
                                    <td class="py-4 px-4 text-right font-mono text-green-400 font-bold text-xl">{{ \App\Helpers\TimeFormatter::format($standing['best_time']) }}</td>
                                    <td class="py-4 px-4 text-zinc-400 text-sm">{{ $standing['session_name'] }}</td>
                                    @php
                                        $speed = \App\Helpers\TimeFormatter::calculateSpeed($standing['best_time'], $trackLength);
                                    @endphp
                                    <td class="py-4 px-4 text-right font-mono text-blue-400">
                                        @if($speed)
                                            {{ $speed }} mph
                                        @else
                                            <span class="text-zinc-600">-</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        <button 
                                            wire:click="toggleDriver({{ $index }})"
                                            class="text-zinc-400 hover:text-zinc-200 transition-colors"
                                        >
                                            <svg class="w-5 h-5 transition-transform {{ $expandedDriver === $index ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                @if($expandedDriver === $index)
                                    <tr class="{{ $index % 2 === 0 ? 'bg-zinc-900/50' : 'bg-zinc-900' }}">
                                        <td colspan="7" class="py-4 px-4">
                                            <div class="bg-zinc-950 rounded-lg p-4 border border-zinc-800">
                                                <h4 class="text-zinc-400 text-sm font-semibold mb-3 uppercase">All Times</h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                    @foreach($standing['all_times'] as $i => $time)
                                                        @php
                                                            $speed = \App\Helpers\TimeFormatter::calculateSpeed($time['time'], $trackLength);
                                                        @endphp
                                                        <div class="bg-zinc-900 rounded-lg p-3 border border-zinc-800">
                                                            <div class="flex justify-between items-center mb-1">
                                                                <span class="text-zinc-400 text-xs">{{ $time['session'] }}</span>
                                                                @if($time['lap'] ?? null)
                                                                    <span class="text-zinc-600 text-xs">Lap {{ $time['lap'] }}</span>
                                                                @endif
                                                            </div>
                                                            <div class="flex justify-between items-center">
                                                                <span class="font-mono {{ ($time['is_best'] ?? false) ? 'text-green-400 font-bold' : 'text-zinc-300' }} text-lg">
                                                                    {{ \App\Helpers\TimeFormatter::format($time['time']) }}
                                                                </span>
                                                                @if($speed)
                                                                    <span class="text-blue-400 text-xs font-mono">{{ $speed }} mph</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="text-center text-zinc-600 text-sm mt-4">
                    {{ $this->standings->count() }} drivers
                </p>
            </div>
        @endif
    </div>
</div>

