<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($filter) }} Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        table th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>{{ ucfirst($filter) }} Sales Report</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Items</th>
                <th>Total Cost</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->order_id }}</td>
                    <td>
                        <ul>
                            @foreach(json_decode($sale->items, true) as $item)
                                <li>{{ $item['quantity'] }}x {{ $item['name'] }} ({{ $item['size'] }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ number_format($sale->total_cost, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->date)->format('F d, Y h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
