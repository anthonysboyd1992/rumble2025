<div class="rumble-dark-bg rounded-xl p-6 rumble-border">
    <h2 class="text-xl font-bold rumble-blue mb-4">Event Counter</h2>
    
    <div class="space-y-6 mb-4">
        {{-- Counter --}}
        <div>
            <div class="flex items-center justify-center gap-6">
                <button 
                    wire:click="decrement"
                    class="w-16 h-16 rounded-lg rumble-blue-bg text-white font-bold text-2xl hover:opacity-80 transition-opacity"
                >
                    −
                </button>
                
                <div class="text-center">
                    <input 
                        type="number" 
                        wire:model.live.debounce.500ms="eventNumber"
                        min="1"
                        class="w-40 text-center text-6xl font-bold text-white bg-transparent border-2 border-zinc-700 rounded-lg px-4 py-2 focus:border-rumble-blue focus:outline-none"
                        placeholder="1"
                    />
                    <div class="text-zinc-400 text-sm mt-2">Current Event</div>
                </div>
                
                <button 
                    wire:click="increment"
                    class="w-16 h-16 rounded-lg rumble-blue-bg text-white font-bold text-2xl hover:opacity-80 transition-opacity"
                >
                    +
                </button>
            </div>
        </div>
        
        {{-- Predefined Messages --}}
        <div>
            <label class="block text-sm font-medium text-zinc-300 mb-2">Predefined Messages</label>
            @if($predefinedMessages->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($predefinedMessages as $predefined)
                        <button 
                            wire:click="applyMessage('{{ $predefined->text }}')"
                            class="px-3 py-1.5 rounded-lg text-sm transition-colors {{ $message === $predefined->text ? 'rumble-blue-bg text-white' : 'rumble-dark-bg-700 text-zinc-300 hover:rumble-blue-bg hover:text-white' }}"
                        >
                            {{ $predefined->text }}
                        </button>
                    @endforeach
                    @if($message)
                        <button 
                            wire:click="clearMessage"
                            class="px-3 py-1.5 rounded-lg text-sm transition-colors bg-red-600 text-white hover:bg-red-700"
                        >
                            Clear
                        </button>
                    @endif
                </div>
            @else
                <div class="flex flex-wrap gap-2">
                    <p class="text-zinc-500 text-sm">No predefined messages yet</p>
                    @if($message)
                        <button 
                            wire:click="clearMessage"
                            class="px-3 py-1.5 rounded-lg text-sm transition-colors bg-red-600 text-white hover:bg-red-700"
                        >
                            Clear
                        </button>
                    @endif
                </div>
            @endif
        </div>
        
        {{-- Add Predefined Message --}}
        <div>
            <label class="block text-sm font-medium text-zinc-300 mb-2">Add Predefined Message</label>
            <div class="flex gap-2">
                <input 
                    type="text" 
                    wire:model="newMessageText"
                    wire:keydown.enter="addPredefinedMessage"
                    placeholder="e.g., Midgets on track"
                    class="flex-1 rumble-dark-bg-700 rumble-border rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-rumble-blue focus:border-transparent"
                />
                <button 
                    wire:click="addPredefinedMessage"
                    class="px-4 py-2 rounded-lg rumble-blue-bg text-white hover:opacity-80 transition-opacity"
                >
                    Add
                </button>
            </div>
        </div>
        
        {{-- Manage Predefined Messages --}}
        @if($predefinedMessages->count() > 0)
            <div class="pt-3 border-t border-zinc-700">
                <div class="text-xs text-zinc-500 mb-2">Manage Messages:</div>
                <div class="flex flex-wrap gap-2">
                    @foreach($predefinedMessages as $predefined)
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg rumble-dark-bg-700">
                            <span class="text-sm text-zinc-300">{{ $predefined->text }}</span>
                            <button 
                                wire:click="deleteMessage({{ $predefined->id }})"
                                wire:confirm="Delete this message?"
                                class="text-red-400 hover:text-red-300 text-xs"
                            >
                                ×
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    
    <div class="text-center">
        <a href="{{ route('event-display') }}" target="_blank" class="text-zinc-400 hover:text-white text-sm">
            View Public Display →
        </a>
    </div>
</div>

