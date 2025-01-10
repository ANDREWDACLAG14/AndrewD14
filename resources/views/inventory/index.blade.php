<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header {
            background-color: #0d6efd;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .table-hover tbody tr:hover {
            background-color: #e9ecef;
        }
        .actions > * {
            margin-bottom: 5px;
        }
    </style>
</head>
<body class="container mt-5">

    <!-- Page Header -->
    <div class="header">
        <h1 class="mb-0">Inventory Management</h1>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('inventory.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add Product</a>
        <a href="/dashboard" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>

    <!-- Inventory Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Ingredients</th>
                    <th>Sold</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventories as $key => $inventory)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $inventory->product_name }}</td>
                        <td class="text-center">
    @if(!empty($inventory->size) && is_array($inventory->size))
        @foreach($inventory->size as $size)
            <div>{{ $size['size'] }} (Stock: {{ $size['stock_in'] }} pieces)</div>
        @endforeach
    @else
        N/A
    @endif
</td>

<td class="text-center">
    @if(!empty($inventory->ingredients) && is_array($inventory->ingredients))
        @foreach($inventory->ingredients as $ingredient)
            <div>{{ $ingredient['name'] }} (Stock: {{ $ingredient['stock_in_ingredients'] }} pieces)</div>
        @endforeach
    @else
        N/A
    @endif
</td>

                        <td class="text-center">{{ $inventory->sold }}</td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <a href="{{ route('inventory.edit', $inventory->id) }}" class="btn btn-warning btn-sm w-100">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="{{ route('inventory.pdf', $inventory->id) }}" target="_blank" class="btn btn-secondary btn-sm w-100">
                                    <i class="bi bi-file-earmark-pdf"></i> Print Inventory
                                </a>
                                <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No inventory available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
