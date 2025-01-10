<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.2rem;
        }
        .btn-primary, .btn-success, .btn-danger {
            width: 100%;
        }
        .table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .list-group-item {
            cursor: pointer;
        }
        .list-group-item:hover {
            background-color: #007bff;
            color: white;
        }
        .btn-sm {
            font-size: 0.8rem;
        }
        @media (max-width: 768px) {
            .card {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <!-- Header -->
    <div class="card">
        <div class="card-header text-center">
            Edit Order
        </div>
        <div class="card-body">
            <form action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Customer Name -->
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Customer Name</label>
                    <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ $order->customer_name }}" required>
                </div>

                <!-- Product Search -->
                <div class="mb-3">
                    <label for="product_search" class="form-label">Search Product</label>
                    <input type="text" id="product_search" class="form-control" placeholder="Type to search...">
                    <ul id="product_list" class="list-group mt-2" style="display:none;"></ul>
                </div>

                <!-- Quantity and Size -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="selected_product" class="form-label">Selected Product</label>
                        <input type="text" id="selected_product" class="form-control" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" id="quantity" class="form-control" min="1">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="size" class="form-label">Size</label>
                        <select id="size" class="form-select">
                            <!-- Dynamically populated sizes -->
                        </select>
                    </div>
                </div>

                <!-- Add to Cart Button -->
                <button type="button" id="add_to_cart" class="btn btn-primary mb-3" disabled><i class="bi bi-cart-plus"></i> Add to Cart</button>

                <!-- Cart Table -->
                <table id="cart_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(json_decode($order->items, true) as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['size'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>₱{{ number_format($item['unitPrice'], 2) }}</td>
                                <td>₱{{ number_format($item['totalPrice'], 2) }}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove_item">Remove</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Hidden Input -->
                <input type="hidden" name="items" id="items_input" value="{{ $order->items }}">

                <!-- Submit Buttons -->
                <button type="submit" class="btn btn-success w-100"><i class="bi bi-save"></i> Update Order</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary w-100 mt-2"><i class="bi bi-arrow-left"></i> Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Define all the products and their prices
    const products = {
        'Classic Milk Tea': { 'Taro': 49, 'Matcha': 49, 'Red Velvet': 49, 'Cookies & Cream': 49, 'Mango Cheesecake': 49, 'Dark Choco': 49, 'Strawberry': 49, 'Avocado': 49 },
        'Premium Milk Tea': { 'Oreo Cheesecake': 89, 'Taro Boba Fudge': 89, 'Red Velvet Oreo Fudge': 89, 'Wintermelon Frappe': 89 },
        'Iced Coffee': { 'Sweet Caramel Macchiato': 69, 'Hazelnut Latte': 69, 'Salted Caramel Coffee': 69 },
        'Hot Coffee': { 'Black Coffee': 35, 'Spanish Latte': 45, 'Mocha Latte': 45, 'Vanilla Latte': 55 }
    };

    const sizePrices = {
        'Classic Milk Tea': { 'Small': 49, 'Medium': 65, 'Large': 85 },
        'Premium Milk Tea': { 'Medium': 89, 'Large': 109 }
    };

    let selectedProduct = '';
    let selectedCategory = '';
    let basePrice = 0;

    // Product Search
    $('#product_search').on('input', function() {
        const query = $(this).val().toLowerCase();
        let results = [];
        Object.keys(products).forEach(category => {
            Object.keys(products[category]).forEach(product => {
                if (product.toLowerCase().includes(query)) {
                    results.push({ product, category });
                }
            });
        });

        if (results.length) {
            $('#product_list').show().html(results.map(item => `
                <li class="list-group-item" data-name="${item.product}" data-category="${item.category}">
                    ${item.product} (${item.category})
                </li>`).join(''));
        } else {
            $('#product_list').hide();
        }
    });

    // Select Product
    $('#product_list').on('click', 'li', function() {
        selectedProduct = $(this).data('name');
        selectedCategory = $(this).data('category');
        basePrice = products[selectedCategory][selectedProduct]; // Get the base price for the product

        $('#selected_product').val(`${selectedProduct} (${selectedCategory})`);
        $('#product_list').hide();
        $('#add_to_cart').prop('disabled', false);

        updateSizeOptions(selectedCategory);
    });

    // Update Size Options
    function updateSizeOptions(category) {
        let sizeOptions = '';

        if (category === 'Classic Milk Tea' || category === 'Premium Milk Tea') {
            // Display sizes for categories with size-based pricing
            sizeOptions = Object.keys(sizePrices[category]).map(size => `
                <option value="${size}" data-price="${sizePrices[category][size]}">
                    ${size} - ₱${(sizePrices[category][size]).toFixed(2)}
                </option>`).join('');
        } else {
            // Fixed pricing for categories like Iced Coffee and Hot Coffee
            sizeOptions = `<option value="Regular" data-price="${basePrice}">
                    Regular - ₱${basePrice.toFixed(2)}
                </option>`;
        }

        $('#size').html(sizeOptions);
    }

    // Add to Cart
    $('#add_to_cart').click(function() {
        const size = $('#size').val();
        const sizePrice = parseFloat($('#size option:selected').data('price'));
        const quantity = parseInt($('#quantity').val());
        const totalPrice = (sizePrice * quantity).toFixed(2);

        if (selectedProduct && quantity > 0) {
            const rowHtml = `
                <tr>
                    <td>${selectedProduct}</td>
                    <td>${size}</td>
                    <td>${quantity}</td>
                    <td>₱${sizePrice.toFixed(2)}</td>
                    <td>₱${totalPrice}</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove_item">Remove</button></td>
                </tr>`;
            $('#cart_table tbody').append(rowHtml);
            updateItemsInput();
        }
    });

    // Remove Item
    $(document).on('click', '.remove_item', function() {
        $(this).closest('tr').remove();
        updateItemsInput();
    });

    function updateItemsInput() {
        const items = [];
        $('#cart_table tbody tr').each(function() {
            const name = $(this).find('td').eq(0).text();
            const size = $(this).find('td').eq(1).text();
            const quantity = $(this).find('td').eq(2).text();
            const unitPrice = $(this).find('td').eq(3).text().replace('₱', '');
            const totalPrice = $(this).find('td').eq(4).text().replace('₱', '');
            items.push({ name, size, quantity, unitPrice, totalPrice });
        });
        $('#items_input').val(JSON.stringify(items));
    }
});
</script>

</body>
</html>
