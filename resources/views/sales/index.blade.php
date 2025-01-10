<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .table-hover tbody tr:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body class="container mt-5">

    <!-- Header -->
    <div class="header text-center">
        <h1>Sales</h1>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Navigation and Filters -->
    <div class="d-flex justify-content-between mb-3">
        <div class="d-flex gap-2 align-items-center">
            <select id="filterSelect" class="form-select" style="width: 200px;">
                <option value="">Select Filter</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
            </select>
            <button id="printReport" class="btn btn-dark">
                <i class="bi bi-printer"></i> Print Report
            </button>
        </div>
        <div class="d-flex ms-auto gap-2">
            <a href="{{ route('orders.index') }}" class="btn btn-info">
                <i class="bi bi-box-seam"></i> Go to Orders
            </a>
            <a href="/dashboard" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Sales Table -->
    <table class="table table-bordered table-hover align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Items</th>
                <th>Total Cost</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr data-date="{{ $sale->date }}">
                    <td class="text-center">{{ $sale->id }}</td>
                    <td class="text-center">{{ $sale->order_id }}</td>
                    <td>
                        <ul class="list-unstyled mb-0">
                            @php $items = json_decode($sale->items, true); @endphp
                            @foreach($items as $item)
                                <li>
                                    <strong>{{ $item['quantity'] }}x</strong> {{ $item['name'] }}
                                    ({{ $item['size'] }} - ₱{{ number_format($item['unitPrice'], 2) }})
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-center">₱{{ number_format($sale->total_cost, 2) }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($sale->date)->setTimezone('Asia/Manila')->format('F d, Y h:i A') }}</td>
                    <td class="text-center">
                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        document.getElementById('printReport').addEventListener('click', function () {
            const filter = document.getElementById('filterSelect').value;

            if (!filter) {
                alert('Please select a filter before printing the report.');
                return;
            }

            const url = `/sales/report/${filter}`; // Endpoint for generating the filtered PDF report.
            window.open(url, '_blank'); // Open the PDF report in a new tab.
        });
    </script>
</body>
</html>
