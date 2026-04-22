<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Orders PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background: #f4f4f4;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .header {
            margin-bottom: 1rem;
        }

        .header h2 {
            margin: 0;
        }

        .header p {
            margin: 0;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Filtered Orders</h2>
        <p>Generated on {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Status</th>
                <th>Total</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td class="text-center">{{ $order->id }}</td>
                    <td>{{ $order->user->name }}<br><small>{{ $order->user->email }}</small></td>
                    <td class="text-center">{{ ucfirst($order->status) }}</td>
                    <td class="text-right">${{ number_format($order->total_amount, 2) }}</td>
                    <td class="text-center">{{ $order->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
