<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 8px;
        }
        .btn-success, .btn-danger {
            transition: all 0.3s ease;
        }
        .btn-success:hover, .btn-danger:hover {
            transform: scale(1.05);
        }
        .form-section-title {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 10px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Add New Product</h2>
            <a href="<?php echo e(route('inventory.index')); ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Inventory
            </a>
        </div>

        <!-- Form -->
        <form action="<?php echo e(route('inventory.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <!-- Product Name -->
            <div class="mb-4">
                <label for="product_name" class="form-label form-section-title">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" required>
            </div>

            <!-- Sizes Section -->
            <div class="mb-4">
                <label class="form-section-title">Sizes and Stock</label>
                <div id="sizes-container">
                    <div class="row align-items-center mb-2">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="sizes[0][size]" placeholder="Size (e.g., Small)" required>
                        </div>
                        <div class="col-md-5">
                            <input type="number" class="form-control" name="sizes[0][stock_in]" placeholder="Stock In (pcs)" min="1" required>
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-danger btn-sm d-none remove-size"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm mt-2" onclick="addSizeRow()" data-bs-toggle="tooltip" data-bs-placement="top" title="Add another size">
                    <i class="bi bi-plus-circle"></i> Add Size
                </button>
            </div>

            <!-- Ingredients Section -->
            <div class="mb-4">
                <label class="form-section-title">Ingredients</label>
                <div id="ingredients-container">
                    <div class="row align-items-center mb-2">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="ingredients[0][name]" placeholder="Ingredient" required>
                        </div>
                        <div class="col-md-5">
                            <input type="number" class="form-control" name="ingredients[0][stock_in_ingredients]" placeholder="Stock In (pcs)" min="1" required>
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-danger btn-sm d-none remove-ingredient"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm mt-2" onclick="addIngredientRow()" data-bs-toggle="tooltip" data-bs-placement="top" title="Add another ingredient">
                    <i class="bi bi-plus-circle"></i> Add Ingredient
                </button>
            </div>

            <!-- Submit Button -->
            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Product
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let sizeIndex = 1;
    let ingredientIndex = 1;

    function addSizeRow() {
        const sizeRow = `
            <div class="row align-items-center mb-2">
                <div class="col-md-5">
                    <input type="text" class="form-control" name="sizes[${sizeIndex}][size]" placeholder="Size (e.g., Small)" required>
                </div>
                <div class="col-md-5">
                    <input type="number" class="form-control" name="sizes[${sizeIndex}][stock_in]" placeholder="Stock In (pcs)" min="1" required>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-danger btn-sm remove-size" onclick="removeRow(this)"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        document.getElementById('sizes-container').insertAdjacentHTML('beforeend', sizeRow);
        sizeIndex++;
        activateTooltips();
    }

    function addIngredientRow() {
        const ingredientRow = `
            <div class="row align-items-center mb-2">
                <div class="col-md-5">
                    <input type="text" class="form-control" name="ingredients[${ingredientIndex}][name]" placeholder="Ingredient" required>
                </div>
                <div class="col-md-5">
                    <input type="number" class="form-control" name="ingredients[${ingredientIndex}][stock_in_ingredients]" placeholder="Stock In (pcs)" min="1" required>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-danger btn-sm remove-ingredient" onclick="removeRow(this)"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        document.getElementById('ingredients-container').insertAdjacentHTML('beforeend', ingredientRow);
        ingredientIndex++;
        activateTooltips();
    }

    function removeRow(button) {
        button.closest('.row').remove();
    }

    function activateTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }

    document.addEventListener("DOMContentLoaded", activateTooltips);
</script>
</body>
</html>
<?php /**PATH C:\Users\Family\rvshop_pos\resources\views/inventory/create.blade.php ENDPATH**/ ?>