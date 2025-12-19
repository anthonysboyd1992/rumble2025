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
            padding: 20px;
        }
    </style>
</head>
<body class="text-white antialiased">
    <div class="max-w-2xl mx-auto">
        <livewire:event-counter />
    </div>
    @livewireScripts
</body>
</html>

