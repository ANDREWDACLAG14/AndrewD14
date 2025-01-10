<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Inventory Report</title>
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
        .date-time {
            text-align: right;
            margin-bottom: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h1> Inventory Report</h1>

    <!-- Real-Time Date and Time -->
    <div class="date-time">
        <p><strong>Printed On:</strong> {{ \Carbon\Carbon::now()->format('l, F d, Y h:i A') }}</p>
    </div>

    <!-- Inventory Table -->
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Size</th>
                <th>Current Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach(json_decode($inventory->size, true) as $size)
                <tr>
                    <td>{{ $inventory->product_name }}</td>
                    <td>{{ $size['size'] }}</td>
                    <td>{{ $size['stock_in'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Ingredients Table -->
    <h3>Ingredients</h3>
    <table>
        <thead>
            <tr>
                <th>Ingredient Name</th>
                <th>Current Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach(json_decode($inventory->ingredients, true) as $ingredient)
                <tr>
                    <td>{{ $ingredient['name'] }}</td>
                    <td>{{ $ingredient['stock_in_ingredients'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
