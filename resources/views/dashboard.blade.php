<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="text-center mb-4">
            <x-rumble-logo />
            <p class="rumble-text-muted mt-2">Race Management Dashboard</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <!-- Race Management -->
            <a href="{{ route('race-management') }}" class="group block p-6 rounded-xl rumble-card rumble-card-hover transition-all">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-lg rumble-blue-bg-light rumble-blue group-hover:rumble-blue-bg" style="opacity: 0.2;">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-zinc-100">Race Management</h2>
                        <p class="text-zinc-400 text-sm">Import results, manage classes, view standings</p>
                    </div>
                </div>
            </a>

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

            <!-- Print Standings -->
            <a href="{{ route('print.standings', ['day' => 'friday']) }}" target="_blank" class="group block p-6 rounded-xl border border-zinc-700 bg-zinc-800/50 hover:border-blue-500 hover:bg-zinc-800 transition-all">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-blue-500/10 text-blue-400 group-hover:bg-blue-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-zinc-100">Print Standings</h2>
                        <p class="text-zinc-400 text-sm">Print-friendly standings page</p>
                    </div>
                </div>
            </a>

            <!-- Export CSV -->
            <a href="{{ route('export.standings', ['day' => 'friday']) }}" class="group block p-6 rounded-xl border border-zinc-700 bg-zinc-800/50 hover:border-purple-500 hover:bg-zinc-800 transition-all">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-purple-500/10 text-purple-400 group-hover:bg-purple-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-zinc-100">Export CSV</h2>
                        <p class="text-zinc-400 text-sm">Download standings as spreadsheet</p>
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
