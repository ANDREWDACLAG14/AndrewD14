<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: 'harewataru', sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Receipt</h1>
        <p>Order ID: {{ $order->id }}</p>
        <p>Customer Name: {{ $order->customer_name }}</p>
        <p>Date: {{ $currentDateTime->format('F d, Y h:i A') }}</p>
        </div>

    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['size'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ number_format($item['unitPrice'], 2) }}</td>
                    <td>{{ number_format($item['totalPrice'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total Cost: {{ number_format($order->total_cost, 2) }}</h3>
</body>
</html>
