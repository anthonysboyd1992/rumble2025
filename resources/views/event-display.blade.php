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
            background: #000;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; overflow: hidden;">
    <div class="h-screen w-screen flex items-center justify-center" style="background: #000; margin: 0; padding: 0;">
        <livewire:public-event-display />
    </div>
    @livewireScripts
</body>
</html>

