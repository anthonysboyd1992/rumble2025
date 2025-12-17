<div class="min-h-screen flex items-center justify-center rumble-dark-bg">
    <div class="max-w-md w-full mx-4">
        <div class="rumble-dark-bg-800 rounded-xl p-8 rumble-border border">
            <div class="text-center mb-6">
                <x-rumble-logo />
                <h2 class="text-xl font-bold text-white mt-4">Change Your Password</h2>
                <p class="text-zinc-400 text-sm mt-2">You must set a new password before continuing.</p>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-1">New Password</label>
                    <input 
                        type="password" 
                        wire:model="password" 
                        class="w-full rumble-dark-bg-700 rumble-border border rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="********"
                        autofocus
                    >
                    @error('password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-1">Confirm Password</label>
                    <input 
                        type="password" 
                        wire:model="password_confirmation" 
                        class="w-full rumble-dark-bg-700 rumble-border border rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="********"
                    >
                </div>

                <button type="submit" class="w-full rumble-blue-bg text-white font-bold py-3 px-6 rounded-lg transition-colors">
                    Change Password
                </button>
            </form>
        </div>
    </div>
</div>

