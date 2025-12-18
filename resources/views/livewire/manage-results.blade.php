<div class="rumble-dark-bg rounded-xl p-6 rumble-border">
    <h2 class="text-xl font-bold rumble-blue mb-4">Manage Data</h2>

    {{-- Filters --}}
    <div class="flex gap-4 mb-4">
        <div class="flex gap-2">
            @foreach(['thursday' => 'Thu', 'friday' => 'Fri', 'saturday' => 'Sat'] as $day => $label)
                <button 
                    wire:click="$set('dayFilter', '{{ $day }}')"
                    class="px-3 py-1 rounded text-sm font-bold {{ $dayFilter === $day ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-zinc-400' }}"
                >{{ $label }}</button>
            @endforeach
        </div>

        <select wire:model.live="classFilter" class="rumble-dark-bg-700 rumble-border rounded-lg px-3 py-1 text-white text-sm">
            @foreach($this->classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>

        <div class="flex gap-2 ml-auto">
            <button 
                wire:click="$set('tab', 'results')"
                class="px-3 py-1 rounded text-sm font-bold {{ $tab === 'results' ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-zinc-400' }}"
            >Results</button>
            <button 
                wire:click="$set('tab', 'times')"
                class="px-3 py-1 rounded text-sm font-bold {{ $tab === 'times' ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-zinc-400' }}"
            >Practice Times</button>
        </div>
    </div>

    @if($tab === 'results')
        {{-- Race Results --}}
        @forelse($this->sessions as $session)
            <div class="mb-4 border rumble-border rounded-lg overflow-hidden">
                <div class="flex justify-between items-center px-4 py-2 rumble-dark-bg-700">
                    <span class="font-bold text-white">{{ $session->name }}</span>
                    <button 
                        wire:click="deleteSession({{ $session->id }})"
                        wire:confirm="Delete entire session '{{ $session->name }}' and all results?"
                        class="text-red-400 hover:text-red-300 text-sm"
                    >Delete Session</button>
                </div>
                <div class="divide-y rumble-border">
                    @foreach($session->results->sortBy('position') as $result)
                        <div class="flex items-center justify-between px-4 py-2 text-sm">
                            <div class="flex items-center gap-4">
                                <span class="text-zinc-400 w-8">P{{ $result->position }}</span>
                                <span class="rumble-blue font-mono">#{{ $result->entry->car_number }}</span>
                                <span class="text-white">{{ $result->entry->driver_name }}</span>
                                @if($result->is_dns)<span class="text-red-400 text-xs">DNS</span>@endif
                                @if($result->is_dnf)<span class="text-yellow-400 text-xs">DNF</span>@endif
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-green-400">+{{ $result->points_earned }} pts</span>
                                <button 
                                    wire:click="deleteResult({{ $result->id }})"
                                    wire:confirm="Delete this result?"
                                    class="text-red-400 hover:text-red-300"
                                >&times;</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-zinc-500 text-center py-8">No results for this day/class</div>
        @endforelse
    @else
        {{-- Practice Times --}}
        @php
            $groupedTimes = $this->qualifyingTimes->groupBy('session_name');
        @endphp
        
        @forelse($groupedTimes as $sessionName => $times)
            <div class="mb-4 border rumble-border rounded-lg overflow-hidden">
                <div class="flex justify-between items-center px-4 py-2 rumble-dark-bg-700">
                    <span class="font-bold text-white">{{ $sessionName }}</span>
                    <button 
                        wire:click="deleteQualifyingSession('{{ $sessionName }}')"
                        wire:confirm="Delete all times from '{{ $sessionName }}'?"
                        class="text-red-400 hover:text-red-300 text-sm"
                    >Delete Session</button>
                </div>
                <div class="divide-y rumble-border">
                    @foreach($times as $time)
                        <div class="flex items-center justify-between px-4 py-2 text-sm">
                            <div class="flex items-center gap-4">
                                <span class="rumble-blue font-mono">#{{ $time->car_number }}</span>
                                <span class="text-white">{{ $time->driver_name }}</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-green-400 font-mono">{{ $time->fast_time }}</span>
                                <button 
                                    wire:click="deleteQualifyingTime({{ $time->id }})"
                                    wire:confirm="Delete this time?"
                                    class="text-red-400 hover:text-red-300"
                                >&times;</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-zinc-500 text-center py-8">No practice times for this day/class</div>
        @endforelse
    @endif
</div>

