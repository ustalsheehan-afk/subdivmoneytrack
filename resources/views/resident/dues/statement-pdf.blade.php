<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dues Statement</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; font-weight: bold; }
        td { text-align: left; }
        .status-paid { background-color: #d1fae5; color: #065f46; padding: 2px 6px; border-radius: 4px; }
        .status-unpaid { background-color: #ffedd5; color: #7c2d12; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
    <h2>Dues Statement</h2>
    <p>Resident: {{ $user->name }}</p>
    <p>Date: {{ now()->format('M d, Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Due Date</th>
                <th>Amount</th>
                <th>Paid</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dues as $due)
            <tr>
                <td>{{ $due->title }}</td>
                <td>{{ $due->due_date->format('M d, Y') }}</td>
                <td>₱{{ number_format($due->amount,2) }}</td>
                <td>₱{{ number_format($due->paid_amount,2) }}</td>
                <td>
                    <span class="{{ $due->status == 'paid' ? 'status-paid' : 'status-unpaid' }}">
                        {{ ucfirst($due->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
