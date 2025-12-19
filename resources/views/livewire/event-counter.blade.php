<div class="rumble-dark-bg rounded-xl p-6 rumble-border">
    <h2 class="text-xl font-bold rumble-blue mb-4">Event Counter</h2>
    
    <div class="flex items-center justify-center gap-6 mb-4">
        <button 
            wire:click="decrement"
            class="w-16 h-16 rounded-lg rumble-blue-bg text-white font-bold text-2xl hover:opacity-80 transition-opacity"
        >
            −
        </button>
        
        <div class="text-center">
            <div class="text-5xl font-bold rumble-blue mb-2">{{ $eventNumber }}</div>
            <div class="text-zinc-400 text-sm">Current Event</div>
        </div>
        
        <button 
            wire:click="increment"
            class="w-16 h-16 rounded-lg rumble-blue-bg text-white font-bold text-2xl hover:opacity-80 transition-opacity"
        >
            +
        </button>
    </div>
    
    <div class="text-center">
        <a href="{{ route('event-display') }}" target="_blank" class="text-zinc-400 hover:text-white text-sm">
            View Public Display →
        </a>
    </div>
</div>

