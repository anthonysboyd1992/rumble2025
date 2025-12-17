<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUMBLE in Fort Wayne - User Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="rumble-dark-bg min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="text-zinc-400 hover:text-white transition-colors text-sm">
                ← Back to Dashboard
            </a>
        </div>
        
        <header class="text-center mb-8">
            <x-rumble-logo />
            <p class="text-sm rumble-text-muted mt-2">User Management</p>
        </header>

        <div class="max-w-4xl mx-auto">
            @livewire('user-management')
        </div>
    </div>
    @livewireScripts
</body>
</html>

