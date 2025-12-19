<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Counter - RUMBLE in Fort Wayne</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
        }
    </style>
</head>
<body>
    <div class="w-full h-full flex items-center justify-center" style="background: #000;">
        <livewire:public-event-display />
    </div>
    @livewireScripts
</body>
</html>

