<div class="rumble-dark-bg rounded-xl p-6 rumble-border">
    <h2 class="text-xl font-bold text-red-400 mb-4">Reset Qualifying Data</h2>

    @if($message)
        <div class="mb-4 p-4 rounded-lg bg-green-900/50 text-green-300">
            {{ $message }}
        </div>
    @endif

    @if($showConfirm)
        <p class="text-zinc-300 mb-4">Are you sure? This will delete all qualifying and crossing times.</p>
        <div class="flex gap-3">
            <button wire:click="clearAllData" class="bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Yes, Delete Everything
            </button>
            <button wire:click="cancelReset" class="bg-zinc-700 hover:bg-zinc-600 text-white py-2 px-4 rounded-lg transition-colors">
                Cancel
            </button>
        </div>
    @else
        <button wire:click="confirmReset" class="bg-red-600/20 hover:bg-red-600/40 text-red-400 border border-red-600/50 font-bold py-2 px-4 rounded-lg transition-colors">
            Clear All Qualifying Data
        </button>
    @endif
</div>

