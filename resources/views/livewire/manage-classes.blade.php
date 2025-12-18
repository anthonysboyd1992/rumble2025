<div class="rumble-dark-bg rounded-xl p-6 rumble-border">
    <h2 class="text-xl font-bold rumble-blue mb-4">Race Classes</h2>

    <form wire:submit="create" class="flex gap-2 mb-4">
        <input 
            type="text" 
            wire:model="newClassName" 
            placeholder="New class name..."
            class="flex-1 rumble-dark-bg-700 rumble-border rounded-lg px-3 py-2 text-white text-sm focus:ring-2 focus:ring-rumble-blue focus:border-transparent"
        >
        <button type="submit" class="rumble-blue-bg text-white font-bold px-4 py-2 rounded-lg transition-colors text-sm">
            Add
        </button>
    </form>

    @if($classes->isEmpty())
        <p class="text-zinc-500 text-sm">No classes yet.</p>
    @else
        <div id="classes-list" class="space-y-2">
            @foreach($classes as $class)
                <div 
                    draggable="true"
                    data-id="{{ $class->id }}"
                    class="flex items-center justify-between bg-zinc-800/50 rounded-lg px-3 py-2 cursor-move transition-all hover:bg-zinc-800"
                >
                    @if($editingId === $class->id)
                        <form wire:submit="update" class="flex-1 flex gap-2 items-center">
                            <input 
                                type="text" 
                                wire:model="editingName"
                                class="flex-1 bg-zinc-700 border border-zinc-600 rounded px-2 py-1 text-white text-sm"
                            >
                            <button type="submit" class="text-green-400 hover:text-green-300 text-sm">Save</button>
                            <button type="button" wire:click="cancel" class="text-zinc-400 hover:text-zinc-300 text-sm">Cancel</button>
                        </form>
                    @else
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                            </svg>
                            <span class="text-zinc-200">{{ $class->name }}</span>
                            <div class="flex gap-2 ml-2">
                                <label class="flex items-center gap-1 text-xs {{ $class->show_on_leaderboard ? 'text-green-400' : 'text-zinc-500' }}">
                                    <input type="checkbox" wire:click="toggleVisibility({{ $class->id }}, 'leaderboard')" {{ $class->show_on_leaderboard ? 'checked' : '' }} class="w-3 h-3">
                                    LB
                                </label>
                                <label class="flex items-center gap-1 text-xs {{ $class->show_on_practice ? 'text-green-400' : 'text-zinc-500' }}">
                                    <input type="checkbox" wire:click="toggleVisibility({{ $class->id }}, 'practice')" {{ $class->show_on_practice ? 'checked' : '' }} class="w-3 h-3">
                                    PT
                                </label>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="edit({{ $class->id }})" class="text-zinc-400 hover:rumble-blue text-sm" style="--hover-color: hsl(206.07deg 75.92% 37.45%);" onmouseover="this.style.color='hsl(206.07deg 75.92% 37.45%)'" onmouseout="this.style.color='rgb(163, 163, 163)'">Edit</button>
                            <button wire:click="delete({{ $class->id }})" wire:confirm="Delete this class?" class="text-zinc-400 hover:text-red-400 text-sm">Delete</button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <script>
        (function() {
            const list = document.getElementById('classes-list');
            if (!list) return;

            let draggedElement = null;
            let placeholder = null;

            list.addEventListener('dragstart', function(e) {
                if (e.target.closest('[draggable="true"]')) {
                    draggedElement = e.target.closest('[draggable="true"]');
                    draggedElement.style.opacity = '0.5';
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/html', draggedElement.outerHTML);
                }
            });

            list.addEventListener('dragend', function(e) {
                if (draggedElement) {
                    draggedElement.style.opacity = '';
                    draggedElement = null;
                }
                if (placeholder) {
                    placeholder.remove();
                    placeholder = null;
                }
                document.querySelectorAll('[data-id]').forEach(el => {
                    el.classList.remove('border-t-2');
                });
            });

            list.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                
                if (!draggedElement) return;

                const afterElement = getDragAfterElement(list, e.clientY);
                
                if (afterElement == null) {
                    list.appendChild(draggedElement);
                } else {
                    list.insertBefore(draggedElement, afterElement);
                }
            });

            list.addEventListener('drop', function(e) {
                e.preventDefault();
                if (draggedElement) {
                    const items = Array.from(list.querySelectorAll('[data-id]'));
                    const newOrder = items.map(item => parseInt(item.getAttribute('data-id')));
                    Livewire.find('{{ $this->getId() }}').call('reorder', newOrder);
                }
            });

            function getDragAfterElement(container, y) {
                const draggableElements = [...container.querySelectorAll('[draggable="true"]')].filter(el => el !== draggedElement);
                
                return draggableElements.reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offset = y - box.top - box.height / 2;
                    
                    if (offset < 0 && offset > closest.offset) {
                        return { offset: offset, element: child };
                    } else {
                        return closest;
                    }
                }, { offset: Number.NEGATIVE_INFINITY }).element;
            }
        })();
    </script>
</div>

