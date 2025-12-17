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
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0f0f0f 100%);
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
            <h1 class="text-5xl font-black text-amber-400 tracking-tight mb-2">RUMBLE</h1>
            <p class="text-xl text-zinc-400">in Fort Wayne</p>
            <p class="text-sm text-zinc-600 mt-1">Midget Race Management System</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1 space-y-8">
                <livewire:manage-classes />
                <livewire:import-results />
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

