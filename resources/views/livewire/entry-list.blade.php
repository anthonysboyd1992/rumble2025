<div class="rumble-dark-bg rounded-xl p-6 rumble-border">
    <h2 class="text-xl font-bold rumble-blue mb-6">Registered Entries ({{ $entries->count() }})</h2>

    @if($entries->isEmpty())
        <p class="text-zinc-500 text-center py-8">No entries yet. Import results to register drivers.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($entries as $entry)
                <div class="rumble-dark-bg-700 rounded-lg p-4 rumble-border rumble-blue-border transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl font-bold font-mono rumble-blue">{{ $entry->car_number }}</span>
                        <div>
                            <p class="text-zinc-200 font-medium">{{ $entry->driver_name }}</p>
                            <p class="text-zinc-500 text-sm">{{ $entry->results_count }} results</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

