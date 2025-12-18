<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="text-center mb-4">
            <x-rumble-logo />
            <p class="rumble-text-muted mt-2">Race Management Dashboard</p>
        </div>

        <!-- Primary Actions -->
        <div class="grid gap-4 md:grid-cols-2">
            <!-- Race Management -->
            <a href="{{ route('race-management') }}" class="group block p-6 rounded-xl border border-zinc-700 bg-zinc-800/50 hover:border-yellow-500 hover:bg-zinc-800 transition-all">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-yellow-500/10 text-yellow-400 group-hover:bg-yellow-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-zinc-100">Race Management</h2>
                        <p class="text-zinc-400 text-sm">Import results, manage classes, view standings</p>
                    </div>
                </div>
            </a>

            <!-- Practice Times -->
            <a href="{{ route('qualifying') }}" class="group block p-6 rounded-xl border border-zinc-700 bg-zinc-800/50 hover:border-orange-500 hover:bg-zinc-800 transition-all">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-orange-500/10 text-orange-400 group-hover:bg-orange-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-zinc-100">Practice Times</h2>
                        <p class="text-zinc-400 text-sm">Import CSV, track fastest times</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Secondary Actions -->
        <div class="grid gap-4 md:grid-cols-3">
            <!-- Public Leaderboard -->
            <a href="{{ route('leaderboard') }}" target="_blank" class="group block p-6 rounded-xl border border-zinc-700 bg-zinc-800/50 hover:border-green-500 hover:bg-zinc-800 transition-all">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-green-500/10 text-green-400 group-hover:bg-green-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-zinc-100">Public Leaderboard</h2>
                        <p class="text-zinc-400 text-sm">Live standings for spectators</p>
                    </div>
                </div>
            </a>

            <!-- Public Qualifying Leaderboard -->
            <a href="{{ route('qualifying-leaderboard') }}" target="_blank" class="group block p-6 rounded-xl border border-zinc-700 bg-zinc-800/50 hover:border-orange-500 hover:bg-zinc-800 transition-all">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-orange-500/10 text-orange-400 group-hover:bg-orange-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-zinc-100">Qualifying Leaderboard</h2>
                        <p class="text-zinc-400 text-sm">Live qualifying times for spectators</p>
                    </div>
                </div>
            </a>

            <!-- User Management -->
            <a href="{{ route('users') }}" class="group block p-6 rounded-xl border border-zinc-700 bg-zinc-800/50 hover:border-purple-500 hover:bg-zinc-800 transition-all">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-purple-500/10 text-purple-400 group-hover:bg-purple-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-zinc-100">User Management</h2>
                        <p class="text-zinc-400 text-sm">Create and manage admin users</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="grid gap-4 md:grid-cols-4 mt-4">
            @php
                $entryCount = \App\Models\Entry::count();
                $classCount = \App\Models\RaceClass::count();
                $sessionCount = \App\Models\Session::count();
                $resultCount = \App\Models\Result::count();
            @endphp
            <div class="p-4 rounded-xl rumble-card text-center">
                <p class="text-3xl font-bold rumble-blue">{{ $entryCount }}</p>
                <p class="text-zinc-400 text-sm">Entries</p>
            </div>
            <div class="p-4 rounded-xl rumble-card text-center">
                <p class="text-3xl font-bold text-green-400">{{ $classCount }}</p>
                <p class="text-zinc-400 text-sm">Classes</p>
            </div>
            <div class="p-4 rounded-xl rumble-card text-center">
                <p class="text-3xl font-bold text-blue-400">{{ $sessionCount }}</p>
                <p class="text-zinc-400 text-sm">Sessions</p>
            </div>
            <div class="p-4 rounded-xl rumble-card text-center">
                <p class="text-3xl font-bold text-purple-400">{{ $resultCount }}</p>
                <p class="text-zinc-400 text-sm">Results</p>
            </div>
        </div>
    </div>
</x-layouts.app>
