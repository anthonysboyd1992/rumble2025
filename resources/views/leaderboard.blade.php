<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUMBLE in Fort Wayne - Leaderboard</title>
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

    <livewire:public-leaderboard />

    <div class="checkered-header"></div>

    @livewireScripts
    <script>
        // Check if Echo/Pusher is configured, otherwise rely on polling
        window.Echo = window.Echo || null;
    </script>
    @if(config('broadcasting.default') === 'pusher')
        @vite(['resources/js/echo.js'])
    @endif
</body>
</html>

