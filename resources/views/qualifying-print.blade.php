<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUMBLE in Fort Wayne - Practice Times</title>
    <style>
        @page {
            size: letter;
            margin: 0.5in;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #000;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .header p {
            font-size: 10pt;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }
        th {
            background: #000;
            color: #fff;
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }
        th.right, td.right {
            text-align: right;
        }
        td {
            padding: 4px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background: #f5f5f5;
        }
        .car {
            font-weight: bold;
            font-family: monospace;
        }
        .time {
            font-family: monospace;
            font-weight: bold;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
        @media screen {
            body { max-width: 8.5in; margin: 0 auto; padding: 20px; }
            .no-print { display: block; text-align: center; margin-bottom: 20px; }
            .no-print button { padding: 10px 20px; font-size: 14px; cursor: pointer; }
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">Print Practice Times</button>
    </div>

    <div class="header">
        <h1>RUMBLE in Fort Wayne</h1>
        <p>{{ $className ?? 'All Classes' }} - Practice Times - {{ now()->format('F j, Y g:i A') }}</p>
        @if(isset($inversion))
            @if($inversion === 'full')
                <p style="color: #666; font-size: 9pt; margin-top: 4px;">Full Inversion Applied: Entire list reversed</p>
            @elseif($inversion > 0)
                <p style="color: #666; font-size: 9pt; margin-top: 4px;">Inversion Applied: Top {{ $inversion }} positions inverted</p>
            @endif
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Pos</th>
                <th>Car</th>
                <th>Driver</th>
                <th class="right">Best Time</th>
                <th>Session</th>
                <th>All Times</th>
            </tr>
        </thead>
        <tbody>
            @foreach($standings as $index => $standing)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="car">{{ $standing['car_number'] }}</td>
                    <td>{{ $standing['driver_name'] }}</td>
                    <td class="right time">{{ \App\Helpers\TimeFormatter::format($standing['best_time']) }}</td>
                    <td>{{ $standing['session_name'] }}</td>
                    <td>{{ implode(' | ', array_map(fn($t) => \App\Helpers\TimeFormatter::format($t), $standing['all_times'] ?? [])) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ $standings->count() }} drivers | www.rumbleinfortwayne.com
    </div>
</body>
</html>

