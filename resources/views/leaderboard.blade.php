<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUMBLE in Fort Wayne - Standings</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        html, body {
            background: #000 !important;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body class="text-white antialiased" style="background: #000;">
    <livewire:public-leaderboard />
    @livewireScripts
</body>
</html>
