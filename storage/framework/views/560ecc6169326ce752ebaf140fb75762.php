<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="container mt-5">
    <h1>Create Order</h1>

    <form action="<?php echo e(route('orders.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label for="customer_name" class="form-label">Customer Name</label>
            <input type="text" name="customer_name" id="customer_name" class="form-control" required>
        </div>

        <!-- Product Search -->
        <div class="mb-3">
            <label for="product_search" class="form-label">Search Product</label>
            <input type="text" id="product_search" class="form-control" placeholder="Search product name...">
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
                    <option value="Small" data-price-modifier="0">Small</option>
                    <option value="Medium" data-price-modifier="16">Medium (+₱16)</option>
                    <option value="Large" data-price-modifier="36">Large (+₱36)</option>
                </select>
            </div>
        </div>

        <!-- Add to Cart Button -->
        <button type="button" id="add_to_cart" class="btn btn-primary mb-3" disabled>Add to Cart</button>

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
                <!-- Cart items will be added here -->
            </tbody>
        </table>

        <!-- Hidden input for storing cart data -->
        <input type="hidden" name="items" id="items_input">
        <button type="submit" class="btn btn-success w-100">Save Order</button>
        <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary w-100 mt-2">Cancel</a>
    </form>
<script>
    $(document).ready(function () {
    const products = {
        'Classic Milk Tea': {
            'Taro': 49, 'Matcha': 49, 'Red Velvet': 49, 'Cookies & Cream': 49,
            'Mango Cheesecake': 49, 'Dark Choco': 49, 'Strawberry': 49, 'Avocado': 49
        },
        'Premium Milk Tea': {
            'Oreo Cheesecake': 89, 'Taro Boba Fudge': 89,
            'Red Velvet Oreo Fudge': 89, 'Wintermelon Frappe': 89
        },
        'Iced Coffee': {
            'Sweet Caramel Macchiato': 69, 'Hazelnut Latte': 69, 'Salted Caramel Coffee': 69
        },
        'Hot Coffee': {
            'Black Coffee': 35, 'Spanish Latte': 45, 'Mocha Latte': 45, 'Vanilla Latte': 55
        }
    };

    const sizePrices = {
        'Classic Milk Tea': { Small: 49, Medium: 65, Large: 85 },
        'Premium Milk Tea': { Medium: 89, Large: 109 },
        'Iced Coffee': { Regular: 69 },
        'Hot Coffee': null // Single fixed price, no size options
    };

    let selectedCategory = '';
    let selectedProduct = '';
    let basePrice = 0;

    // Product Search
    $('#product_search').on('input', function () {
        const query = $(this).val().toLowerCase();
        let results = [];
        Object.keys(products).forEach(category => {
            Object.keys(products[category]).forEach(product => {
                if (product.toLowerCase().includes(query)) {
                    results.push({ category, product, price: products[category][product] });
                }
            });
        });

        if (results.length) {
            $('#product_list').show().html(results.map(item =>
                `<li class="list-group-item" 
                    data-category="${item.category}" 
                    data-name="${item.product}" 
                    data-base-price="${item.price}">
                    ${item.product} (${item.category})
                </li>`).join(''));
        } else {
            $('#product_list').hide();
        }
    });

    // Select Product
    $('#product_list').on('click', 'li', function () {
        selectedCategory = $(this).data('category');
        selectedProduct = $(this).data('name');
        basePrice = parseFloat($(this).data('base-price'));

        $('#selected_product').val(selectedProduct);
        $('#product_list').hide();
        $('#add_to_cart').prop('disabled', false);

        // Update size dropdown based on category
        updateSizeOptions(selectedCategory);
    });

    // Update Size Options
    function updateSizeOptions(category) {
        let sizeOptions = '';
        if (category in sizePrices && sizePrices[category] !== null) {
            Object.keys(sizePrices[category]).forEach(size => {
                sizeOptions += `<option value="${size}" data-price="${sizePrices[category][size]}">
                                    ${size} - ₱${sizePrices[category][size]}
                                </option>`;
            });
        } else {
            // For categories with fixed prices (e.g., Hot Coffee)
            sizeOptions = `<option value="Regular" data-price="${basePrice}">
                                Regular - ₱${basePrice}
                           </option>`;
        }
        $('#size').html(sizeOptions);
    }

    // Add to Cart
    $('#add_to_cart').click(function () {
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

            resetForm();
        }
    });

    // Remove Item
    $(document).on('click', '.remove_item', function () {
        $(this).closest('tr').remove();
        updateItemsInput();
    });

    // Update Hidden Input
    function updateItemsInput() {
        const items = [];
        $('#cart_table tbody tr').each(function () {
            const name = $(this).find('td').eq(0).text();
            const size = $(this).find('td').eq(1).text();
            const quantity = $(this).find('td').eq(2).text();
            const unitPrice = $(this).find('td').eq(3).text().replace('₱', '');
            const totalPrice = $(this).find('td').eq(4).text().replace('₱', '');
            items.push({ name, size, quantity, unitPrice, totalPrice });
        });
        $('#items_input').val(JSON.stringify(items));
    }

    // Reset Form
    function resetForm() {
        $('#selected_product').val('');
        $('#quantity').val('');
        $('#size').html('');
        $('#add_to_cart').prop('disabled', true);
    }
});

</script>
</body>
</html>
<?php /**PATH E:\rvshop_pos\resources\views/orders/create.blade.php ENDPATH**/ ?>