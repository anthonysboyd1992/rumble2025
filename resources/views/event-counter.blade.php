<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Counter - RUMBLE in Fort Wayne</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body {
            background: hsl(312deg 7.69% 8%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="text-white antialiased">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" class="rumble-blue hover:opacity-80 transition-opacity flex items-center gap-2 text-sm mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Dashboard</span>
                </a>
                <x-rumble-logo />
                <p class="text-sm rumble-text-muted mt-2">Event Counter Management</p>
            </div>
            
            <livewire:event-counter />
        </div>
    </div>
    @livewireScripts
</body>
</html>

