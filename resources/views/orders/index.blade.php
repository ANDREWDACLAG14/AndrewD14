<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
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
        .badge {
            font-size: 0.9em;
        }
        .actions > * {
            margin-bottom: 5px;
        }
    </style>
</head>
<body class="container mt-5">

    <!-- Header -->
    <div class="header">
        <h1 class="text-center mb-0">Orders</h1>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Buttons -->
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('orders.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add Order</a>
        <div>
            <a href="{{ route('sales.index') }}" class="btn btn-info me-2"><i class="bi bi-box-seam"></i> Go to Sales</a>
            <a href="/dashboard" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        </div>
    </div>

    <!-- Orders Table -->
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Items</th>
                <th>Total Cost</th>
                <th>Status</th>
                <th>Date & Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td class="text-center">{{ $order->id }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>
                        <ul class="list-unstyled mb-0">
                            @php $items = json_decode($order->items, true); @endphp
                            @foreach($items as $item)
                                <li>
                                    <strong>{{ $item['quantity'] }}x</strong> {{ $item['name'] }} 
                                    ({{ $item['size'] }} - ₱{{ number_format($item['unitPrice'], 2) }})
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-center">₱{{ number_format($order->total_cost, 2) }}</td>
                    <td class="text-center">
                        @if($order->status === 'completed')
                            <span class="badge bg-success">Completed</span>
                        @else
                            <span class="badge bg-danger">Pending</span>
                        @endif
                    </td>
                    <td class="text-center">
                        {{ $order->updated_at 
                        ? $order->updated_at->setTimezone('Asia/Manila')->format('F d, Y h:i A') 
                        : $order->created_at->setTimezone('Asia/Manila')->format('F d, Y h:i A') }}
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning btn-sm w-100">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>

                            @if($order->status != 'completed')
                                <form action="{{ route('orders.markComplete', $order->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                        <i class="bi bi-check-circle"></i> Mark as Complete
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('orders.undoComplete', $order->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm w-100">
                                        <i class="bi bi-arrow-clockwise"></i> Undo Completion
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('orders.receipt', $order->id) }}" target="_blank" class="btn btn-secondary btn-sm w-100">
                                <i class="bi bi-receipt"></i> Print Receipt
                            </a>
                        </div>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
