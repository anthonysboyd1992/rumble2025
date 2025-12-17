<div class="bg-zinc-900 rounded-xl p-6 border border-zinc-800">
    <h2 class="text-xl font-bold text-amber-400 mb-6">Driver Results</h2>

    @if($entries->isEmpty())
        <p class="text-zinc-500 text-center py-8">No entries yet.</p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 mb-6">
            @foreach($entries as $entry)
                <button 
                    wire:click="selectDriver({{ $entry->id }})"
                    class="text-left px-3 py-2 rounded-lg border transition-colors {{ $selectedEntryId === $entry->id ? 'bg-amber-500 text-black border-amber-500' : 'bg-zinc-800/50 border-zinc-700 hover:border-amber-500/50 text-zinc-300' }}"
                >
                    <span class="font-mono font-bold">{{ $entry->car_number }}</span>
                    <span class="text-sm ml-1">{{ Str::limit($entry->driver_name, 12) }}</span>
                </button>
            @endforeach
        </div>

        @if($selectedEntry)
            <div class="border-t border-zinc-800 pt-6">
                <h3 class="text-lg font-bold text-zinc-100 mb-4">
                    <span class="text-amber-400 font-mono">{{ $selectedEntry->car_number }}</span> - {{ $selectedEntry->driver_name }}
                </h3>

                @if($selectedEntry->results->isEmpty())
                    <p class="text-zinc-500">No results recorded.</p>
                @else
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-xs uppercase text-zinc-500 border-b border-zinc-800">
                                <th class="pb-2 pr-4">Session</th>
                                <th class="pb-2 pr-4">Day</th>
                                <th class="pb-2 pr-4 text-right">Pos</th>
                                <th class="pb-2 pr-4 text-right">Start</th>
                                <th class="pb-2 pr-4 text-right">Time</th>
                                <th class="pb-2 text-right">Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedEntry->results->sortBy('session.name') as $result)
                                <tr class="border-b border-zinc-800/50">
                                    <td class="py-2 pr-4 text-zinc-200">{{ $result->session->name }}</td>
                                    <td class="py-2 pr-4 text-zinc-400 capitalize">{{ $result->session->day }}</td>
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
                                    <td class="py-2 pr-4 text-right font-mono text-zinc-500 text-sm">{{ $result->time ?? '-' }}</td>
                                    <td class="py-2 text-right {{ $result->points_earned > 0 ? 'text-green-400' : 'text-red-400' }} font-bold">
                                        {{ $result->points_earned }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-zinc-700">
                                <td colspan="5" class="py-2 text-right text-zinc-400 font-medium">Total:</td>
                                <td class="py-2 text-right text-green-400 font-bold text-lg">{{ $selectedEntry->results->sum('points_earned') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                @endif
            </div>
        @else
            <p class="text-zinc-500 text-center py-4">Select a driver to view their results.</p>
        @endif
    @endif
</div>

