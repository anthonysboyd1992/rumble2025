<div 
    x-data="{}"
    x-init="
        if (typeof Echo !== 'undefined') {
            Echo.channel('standings').listen('.updated', () => $wire.$refresh());
        }
    "
    wire:poll.5s
    class="min-h-screen"
>
    <div class="container mx-auto px-4 py-8">
        <header class="text-center mb-8">
            <x-rumble-logo />
            <p class="text-lg rumble-text-muted mt-2">Standings</p>
            
            <div class="mt-4 flex justify-center gap-2 flex-wrap">
                <button 
                    wire:click="$set('dayFilter', 'thursday')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $dayFilter === 'thursday' ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-white rumble-dark-bg-hover' }}"
                >
                    Thursday
                </button>
                <button 
                    wire:click="$set('dayFilter', 'friday')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $dayFilter === 'friday' ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-white rumble-dark-bg-hover' }}"
                >
                    Friday
                </button>
                <button 
                    wire:click="$set('dayFilter', 'saturday')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $dayFilter === 'saturday' ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-white rumble-dark-bg-hover' }}"
                >
                    Saturday
                </button>
                <button 
                    wire:click="$set('dayFilter', '')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $dayFilter === '' ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-white rumble-dark-bg-hover' }}"
                >
                    All Days
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
            
            <p class="text-xs text-zinc-600 mt-2">Live updates</p>
        </header>

        @if($this->standings->isEmpty())
            <div class="text-center py-16">
                <p class="text-zinc-500 text-xl">No results yet.</p>
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
                                <th class="py-3 px-4 text-right text-xs font-bold uppercase text-zinc-400">Time</th>
                                <th class="py-3 px-4 text-right text-xs font-bold uppercase text-zinc-400">Qual</th>
                                <th class="py-3 px-4 text-right text-xs font-bold uppercase text-zinc-400">Heats</th>
                                <th class="py-3 px-4 text-right text-xs font-bold uppercase text-zinc-400">A-Main</th>
                                <th class="py-3 px-4 text-right text-xs font-bold uppercase text-zinc-400">Total</th>
                                <th class="py-3 px-4 text-center text-xs font-bold uppercase text-zinc-400"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->standings as $index => $standing)
                                <tr class="border-b border-zinc-800/50 {{ $index % 2 === 0 ? 'bg-zinc-900/50' : 'bg-zinc-900' }}">
                                    <td class="py-4 px-4 text-zinc-400 font-bold text-lg">{{ $index + 1 }}</td>
                                    <td class="py-4 px-4">
                                        <span class="font-mono text-3xl rumble-blue font-bold">{{ $standing['entry']->car_number }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-zinc-200 text-lg font-medium">{{ $standing['entry']->driver_name }}</td>
                                    <td class="py-4 px-4 text-right font-mono text-zinc-500">{{ $standing['qualifying_time'] ?? '-' }}</td>
                                    <td class="py-4 px-4 text-right {{ $standing['qualifying_status'] ?? false ? 'text-red-400' : 'text-zinc-400' }} font-semibold">
                                        {{ $standing['qualifying_status'] ?? $standing['qualifying_points'] ?? '' }}
                                    </td>
                                    <td class="py-4 px-4 text-right {{ $standing['heat_status'] ?? false ? 'text-red-400' : 'text-zinc-400' }} font-semibold">
                                        {{ $standing['heat_status'] ?? $standing['heat_points'] ?? '' }}
                                    </td>
                                    <td class="py-4 px-4 text-right {{ $standing['amain_status'] ?? false ? 'text-red-400' : 'text-zinc-400' }} font-semibold">
                                        {{ $standing['amain_status'] ?? $standing['amain_points'] ?? '' }}
                                    </td>
                                    <td class="py-4 px-4 text-right text-green-400 font-bold text-xl">{{ $standing['total_points'] }}</td>
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
                                        <td colspan="9" class="py-4 px-4">
                                            <div class="bg-zinc-950 rounded-lg p-4 border border-zinc-800">
                                                <h4 class="text-zinc-400 text-sm font-semibold mb-3 uppercase">Points Breakdown</h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                    @foreach($standing['all_results'] ?? [] as $result)
                                                        <div class="bg-zinc-900 rounded-lg p-3 border border-zinc-800">
                                                            <div class="flex justify-between items-center mb-1">
                                                                <span class="text-zinc-400 text-xs font-medium">{{ $result['session_name'] }}</span>
                                                                <span class="text-zinc-600 text-xs uppercase">{{ $result['session_type'] }}</span>
                                                            </div>
                                                            <div class="flex justify-between items-center">
                                                                <div>
                                                                    @if($result['is_dns'])
                                                                        <span class="text-red-400 font-semibold">DNS</span>
                                                                    @elseif($result['is_dnf'])
                                                                        <span class="text-red-400 font-semibold">DQ</span>
                                                                    @else
                                                                        <span class="text-zinc-300">Pos {{ $result['position'] }}</span>
                                                                    @endif
                                                                </div>
                                                                <span class="text-green-400 font-bold text-lg">{{ $result['points'] }}</span>
                                                            </div>
                                                            @if($result['time'])
                                                                <div class="text-zinc-500 text-xs mt-1 font-mono">{{ $result['time'] }}</div>
                                                            @endif
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
                    {{ $this->standings->count() }} entries
                </p>
            </div>
        @endif
    </div>
</div>

