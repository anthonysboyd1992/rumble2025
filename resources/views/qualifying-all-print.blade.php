<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Qualifying Times - RUMBLE in Fort Wayne</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10pt; padding: 0.5in; }
        h1 { font-size: 16pt; margin-bottom: 4px; }
        h2 { font-size: 12pt; margin-bottom: 8px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { padding: 4px 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; font-weight: bold; font-size: 9pt; }
        td { font-size: 9pt; }
        .pos { width: 40px; text-align: center; }
        .car { width: 60px; font-weight: bold; }
        .time { font-family: monospace; text-align: right; }
        .lap { width: 40px; text-align: center; }
        @media print {
            body { padding: 0.25in; }
            @page { margin: 0.25in; size: letter portrait; }
            .no-print { display: none; }
        }
        @media screen {
            .no-print { display: block; text-align: center; margin-bottom: 20px; }
            .no-print button { padding: 10px 20px; font-size: 14px; cursor: pointer; background: #333; color: #fff; border: none; border-radius: 4px; }
            .no-print button:hover { background: #555; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">{{ isset($showDay) && $showDay ? 'Print All Days' : 'Print Day' }} Qualifying Times</button>
    </div>

    <h1>RUMBLE in Fort Wayne</h1>
    <h2>{{ isset($showDay) && $showDay ? 'All Days - ' : '' }}All Qualifying Times{{ $className ? ' - ' . $className : '' }}</h2>

    <table>
        <thead>
            <tr>
                <th class="pos">#</th>
                <th class="car">Car</th>
                <th>Driver</th>
                @if(isset($showDay) && $showDay)
                    <th>Day</th>
                @endif
                <th>Session</th>
                <th class="lap">Lap</th>
                <th class="time">Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($times as $index => $time)
                <tr>
                    <td class="pos">{{ $index + 1 }}</td>
                    <td class="car">{{ $time['car_number'] }}</td>
                    <td>{{ $time['driver_name'] }}</td>
                    @if(isset($showDay) && $showDay)
                        <td>{{ ucfirst($time['day'] ?? '') }}</td>
                    @endif
                    <td>{{ $time['session'] }}</td>
                    <td class="lap">{{ $time['lap'] ?? '-' }}</td>
                    <td class="time">{{ \App\Helpers\TimeFormatter::format($time['time']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 16px; font-size: 8pt; color: #999;">
        {{ count($times) }} times | Generated {{ now()->format('M j, Y g:i A') }}
    </p>
</body>
</html>

