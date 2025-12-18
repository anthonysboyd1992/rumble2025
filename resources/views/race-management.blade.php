<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUMBLE in Fort Wayne - Race Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body {
            background: hsl(312deg 7.69% 8%);
            min-height: 100vh;
        }
        .checkered-header {
            background: repeating-linear-gradient(
                90deg,
                #000 0px,
                #000 20px,
                #fff 20px,
                #fff 40px
            );
            height: 8px;
        }
    </style>
</head>
<body class="text-white antialiased">
    <div class="checkered-header"></div>

    <div class="container mx-auto px-4 py-8">
        <header class="text-center mb-12">
            <div class="flex justify-start mb-4">
                <a href="{{ route('dashboard') }}" class="rumble-blue hover:opacity-80 transition-opacity flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Dashboard</span>
                </a>
            </div>
            <x-rumble-logo />
            <p class="text-sm rumble-text-muted mt-2">Midget Race Management System</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1 space-y-8">
                <livewire:manage-classes />
                <livewire:import-results />
                <livewire:manual-result-entry />
                <livewire:reset-data />
            </div>

            <div class="lg:col-span-2 space-y-8">
                <livewire:standings />
                <livewire:driver-results />
            </div>
        </div>
    </div>

    <div class="checkered-header mt-12"></div>

    @livewireScripts
</body>
</html>

