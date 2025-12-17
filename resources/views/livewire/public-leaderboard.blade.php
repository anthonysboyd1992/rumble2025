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
            <h1 class="text-5xl font-black text-amber-400 tracking-tight mb-2">RUMBLE</h1>
            <p class="text-xl text-zinc-400">in Fort Wayne</p>
            <p class="text-lg text-zinc-500 mt-2">Friday Standings</p>
            
            @if($this->classes->count() > 1)
                <div class="mt-4 flex justify-center gap-2">
                    @foreach($this->classes as $class)
                        <button 
                            wire:click="$set('classFilter', {{ $class->id }})"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $classFilter === $class->id ? 'bg-amber-500 text-black' : 'bg-zinc-800 text-zinc-300 hover:bg-zinc-700' }}"
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
            <div class="bg-zinc-900/80 rounded-xl border border-zinc-800 overflow-hidden max-w-4xl mx-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs uppercase text-zinc-500 border-b border-zinc-800 bg-zinc-900">
                            <th class="py-4 px-4">Pos</th>
                            <th class="py-4 px-4">Car</th>
                            <th class="py-4 px-4">Driver</th>
                            <th class="py-4 px-4 text-right">Time</th>
                            <th class="py-4 px-4 text-right">Qual</th>
                            <th class="py-4 px-4 text-right">Heats</th>
                            <th class="py-4 px-4 text-right">A-Main</th>
                            <th class="py-4 px-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->standings as $index => $standing)
                            <tr class="border-b border-zinc-800/50">
                                <td class="py-4 px-4 text-zinc-400">{{ $index + 1 }}</td>
                                <td class="py-4 px-4 font-mono text-2xl text-amber-400 font-bold">{{ $standing['entry']->car_number }}</td>
                                <td class="py-4 px-4 text-zinc-200 text-lg">{{ $standing['entry']->driver_name }}</td>
                                <td class="py-4 px-4 text-right font-mono text-zinc-500">{{ $standing['qualifying_time'] ?? '-' }}</td>
                                <td class="py-4 px-4 text-right {{ $standing['qualifying_status'] ?? false ? 'text-red-400' : 'text-zinc-400' }}">
                                    {{ $standing['qualifying_status'] ?? $standing['qualifying_points'] ?? '' }}
                                </td>
                                <td class="py-4 px-4 text-right {{ $standing['heat_status'] ?? false ? 'text-red-400' : 'text-zinc-400' }}">
                                    {{ $standing['heat_status'] ?? $standing['heat_points'] ?? '' }}
                                </td>
                                <td class="py-4 px-4 text-right {{ $standing['amain_status'] ?? false ? 'text-red-400' : 'text-zinc-400' }}">
                                    {{ $standing['amain_status'] ?? $standing['amain_points'] ?? '' }}
                                </td>
                                <td class="py-4 px-4 text-right text-green-400 font-bold text-xl">{{ $standing['total_points'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p class="text-center text-zinc-600 text-sm mt-8">
                {{ $this->standings->count() }} entries
            </p>
        @endif
    </div>
</div>

