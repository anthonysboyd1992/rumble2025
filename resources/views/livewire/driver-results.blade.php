<div class="rumble-dark-bg rounded-xl p-6 rumble-border">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold rumble-blue">Driver Results</h2>
        <select wire:model.live="classFilter" class="bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-sm text-white">
            <option value="">All Classes</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>

    @if($entries->isEmpty())
        <p class="text-zinc-500 text-center py-8">No entries yet.</p>
    @else
        <div class="space-y-2">
            @foreach($entries as $entry)
                <div class="border border-zinc-700 rounded-lg overflow-hidden">
                    <button 
                        wire:click="selectDriver({{ $entry->id }})"
                        class="w-full flex items-center justify-between px-4 py-3 transition-colors {{ $selectedEntryId === $entry->id ? 'rumble-blue-bg-light rumble-blue-border' : 'rumble-dark-bg-700 rumble-dark-bg-hover' }}"
                    >
                        <div class="flex items-center gap-3">
                            <span class="font-mono font-bold rumble-blue text-lg">{{ $entry->car_number }}</span>
                            <span class="text-zinc-200">{{ $entry->driver_name }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-green-400 font-bold">{{ $entry->results->sum('points_earned') }} pts</span>
                            <svg class="w-5 h-5 text-zinc-400 transition-transform {{ $selectedEntryId === $entry->id ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    @if($selectedEntryId === $entry->id)
                        <div class="border-t border-zinc-700 bg-zinc-900 p-4">
                            @if($entry->results->isEmpty())
                                <p class="text-zinc-500 text-sm">No results recorded.</p>
                            @else
                                <table class="w-full text-sm">
                                    <thead>
<tr class="text-left text-xs uppercase text-zinc-500 border-b border-zinc-800">
                                                            <th class="pb-2 pr-4">Session</th>
                                                            <th class="pb-2 pr-4 text-right">Pos</th>
                                                            <th class="pb-2 pr-4 text-right">Start</th>
                                                            <th class="pb-2 pr-4 text-right">Time</th>
                                                            <th class="pb-2 text-right">Points</th>
                                                            <th class="pb-2 pl-4 text-right"></th>
                                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($entry->results->sortBy('session.name') as $result)
<tr class="border-b border-zinc-800/50">
                                                                <td class="py-2 pr-4 text-zinc-200">{{ $result->session->name }}</td>
                                                                <td class="py-2 pr-4 text-right {{ $result->is_dns ? 'text-red-400' : ($result->is_dnf ? 'text-red-400' : 'text-zinc-200') }}">
                                                                    @if($result->is_dns)
                                                                        DNS
                                                                    @elseif($result->is_dnf)
                                                                        DQ
                                                                    @else
                                                                        {{ $result->position }}
                                                                    @endif
                                                                </td>
                                                                <td class="py-2 pr-4 text-right text-zinc-400">{{ $result->starting_position ?? '-' }}</td>
                                                                <td class="py-2 pr-4 text-right font-mono text-zinc-500">{{ $result->time ?? '-' }}</td>
                                                                <td class="py-2 text-right {{ $result->points_earned > 0 ? 'text-green-400' : 'text-red-400' }} font-bold">
                                                                    {{ $result->points_earned }}
                                                                </td>
                                                                <td class="py-2 pl-4 text-right">
                                                                    <button 
                                                                        wire:click="deleteResult({{ $result->id }})"
                                                                        wire:confirm="Delete this result?"
                                                                        class="text-red-400 hover:text-red-300 text-xs"
                                                                    >&times;</button>
                                                                </td>
                                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

