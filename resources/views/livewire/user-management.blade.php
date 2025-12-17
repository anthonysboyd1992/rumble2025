<div class="space-y-6">
    <div class="rumble-dark-bg-800 rounded-xl p-6 rumble-border border">
        <h2 class="text-xl font-bold rumble-blue mb-4">{{ $editingId ? 'Edit User' : 'Create User' }}</h2>

        @if (session()->has('message'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-4">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1">Name</label>
                <input 
                    type="text" 
                    wire:model="name" 
                    class="w-full rumble-dark-bg-700 rumble-border border rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="John Doe"
                >
                @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1">Email</label>
                <input 
                    type="email" 
                    wire:model="email" 
                    class="w-full rumble-dark-bg-700 rumble-border border rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="john@example.com"
                >
                @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1">
                    Password {{ $editingId ? '(leave blank to keep current)' : '' }}
                </label>
                <input 
                    type="password" 
                    wire:model="password" 
                    class="w-full rumble-dark-bg-700 rumble-border border rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="********"
                >
                @error('password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rumble-blue-bg text-white font-bold py-2 px-6 rounded-lg transition-colors">
                    {{ $editingId ? 'Update User' : 'Create User' }}
                </button>
                @if($editingId)
                    <button type="button" wire:click="cancelEdit" class="bg-zinc-600 hover:bg-zinc-500 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                        Cancel
                    </button>
                @endif
            </div>
        </form>
    </div>

    <div class="rumble-dark-bg-800 rounded-xl p-6 rumble-border border">
        <h2 class="text-xl font-bold rumble-blue mb-4">Users</h2>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b rumble-border">
                        <th class="text-left py-3 px-4 text-zinc-400 font-medium">Name</th>
                        <th class="text-left py-3 px-4 text-zinc-400 font-medium">Email</th>
                        <th class="text-left py-3 px-4 text-zinc-400 font-medium">Created</th>
                        <th class="text-right py-3 px-4 text-zinc-400 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="border-b rumble-border hover:bg-zinc-800/50">
                            <td class="py-3 px-4 text-white">{{ $user->name }}</td>
                            <td class="py-3 px-4 text-zinc-300">{{ $user->email }}</td>
                            <td class="py-3 px-4 text-zinc-400 text-sm">{{ $user->created_at->format('M j, Y') }}</td>
                            <td class="py-3 px-4 text-right">
                                <button 
                                    wire:click="edit({{ $user->id }})" 
                                    class="text-blue-400 hover:text-blue-300 mr-3"
                                >
                                    Edit
                                </button>
                                @if(auth()->id() !== $user->id)
                                    <button 
                                        wire:click="delete({{ $user->id }})" 
                                        wire:confirm="Are you sure you want to delete this user?"
                                        class="text-red-400 hover:text-red-300"
                                    >
                                        Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

