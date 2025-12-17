<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUMBLE in Fort Wayne 2025</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0f0f0f 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="text-white antialiased flex items-center justify-center min-h-screen">
    <div class="text-center px-6">
        <x-rumble-logo class="mx-auto mb-8" />
        
        <h1 class="text-4xl font-bold mb-2">RUMBLE in Fort Wayne</h1>
        <p class="text-zinc-500 mb-12">2025</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a 
                href="{{ route('leaderboard') }}" 
                class="px-8 py-4 rounded-lg text-lg font-semibold transition-all rumble-blue-bg text-white hover:opacity-90"
            >
                Standings
            </a>
            <a 
                href="{{ route('qualifying-leaderboard') }}" 
                class="px-8 py-4 rounded-lg text-lg font-semibold transition-all bg-zinc-800 text-white hover:bg-zinc-700"
            >
                Live Timing
            </a>
        </div>
        
        @auth
            <div class="mt-12">
                <a href="{{ route('dashboard') }}" class="text-zinc-500 hover:text-white transition-colors">
                    Go to Dashboard →
                </a>
            </div>
        @else
            <div class="mt-12">
                <a href="{{ route('login') }}" class="text-zinc-500 hover:text-white transition-colors">
                    Admin Login
                </a>
            </div>
        @endauth
    </div>
</body>
</html>
