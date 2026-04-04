<!DOCTYPE html>
<html>
<head>
    <title>Reservation Schedule - {{ $date }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .status-approved { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
        .status-rejected { color: red; font-weight: bold; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #999; padding: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Daily Reservation Schedule</h1>
        <p>Date: {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Time Slot</th>
                <th>Amenity</th>
                <th>Customer</th>
                <th>Unit</th>
                <th>Guests</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $res)
                <tr>
                    <td>{{ $res->time_slot }}</td>
                    <td>{{ optional($res->amenity)->name }}</td>
                    <td>{{ $res->customer_name }}</td>
                    <td>{{ $res->customer_unit }}</td>
                    <td>{{ $res->guest_count }}</td>
                    <td class="status-{{ $res->status }}">
                        {{ ucfirst($res->status) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">No reservations found for this date.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('Y-m-d H:i:s') }} by {{ auth()->user()->name ?? 'System' }}
    </div>
</body>
</html>
