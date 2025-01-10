<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 2rem;
        }
        .btn {
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .form-label {
            font-weight: bold;
        }
        .input-group input {
            border-radius: 0.25rem;
        }
        .remove-size, .remove-ingredient {
            margin-left: 10px;
        }
        .remove-size:hover, .remove-ingredient:hover {
            background-color: #ff6b6b;
        }
    </style>
</head>
<body class="container mt-5">
    <div class="header">
        <h2>Edit Product</h2>
    </div>

    <div class="mb-3">
        <a href="<?php echo e(route('inventory.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Inventory
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Display Validation Errors -->
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('inventory.update', $inventory->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <!-- Product Name -->
                <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo e(old('product_name', $inventory->product_name)); ?>" required>
                </div>

                <!-- Sizes -->
                <div class="mb-3">
                    <label for="sizes" class="form-label">Sizes</label>
                    <div id="sizes-container">
                        <?php $__currentLoopData = json_decode($inventory->size, true) ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="input-group mb-2 size-row">
                                <input type="text" name="sizes[<?php echo e($index); ?>][size]" class="form-control" placeholder="Size" value="<?php echo e($size['size']); ?>" required>
                                <input type="number" name="sizes[<?php echo e($index); ?>][stock_in]" class="form-control" placeholder="Stock In" value="<?php echo e($size['stock_in']); ?>" required>
                                <button type="button" class="btn btn-danger remove-size">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-success" id="add-size">
                        <i class="bi bi-plus-circle"></i> Add Size
                    </button>
                </div>

                <!-- Ingredients -->
                <div class="mb-3">
                    <label for="ingredients" class="form-label">Ingredients</label>
                    <div id="ingredients-container">
                        <?php $__currentLoopData = json_decode($inventory->ingredients, true) ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="input-group mb-2 ingredient-row">
                                <input type="text" name="ingredients[<?php echo e($index); ?>][name]" class="form-control" placeholder="Ingredient" value="<?php echo e($ingredient['name']); ?>" required>
                                <input type="number" name="ingredients[<?php echo e($index); ?>][stock_in_ingredients]" class="form-control" placeholder="Stock In" value="<?php echo e($ingredient['stock_in_ingredients']); ?>" required>
                                <button type="button" class="btn btn-danger remove-ingredient">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-success" id="add-ingredient">
                        <i class="bi bi-plus-circle"></i> Add Ingredient
                    </button>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let sizeIndex = <?php echo e(count(json_decode($inventory->size, true) ?? [])); ?>;
            let ingredientIndex = <?php echo e(count(json_decode($inventory->ingredients, true) ?? [])); ?>;

            // Add Size Row
            document.getElementById('add-size').addEventListener('click', function () {
                const container = document.getElementById('sizes-container');
                const row = document.createElement('div');
                row.classList.add('input-group', 'mb-2', 'size-row');
                row.innerHTML = `
                    <input type="text" name="sizes[${sizeIndex}][size]" class="form-control" placeholder="Size" required>
                    <input type="number" name="sizes[${sizeIndex}][stock_in]" class="form-control" placeholder="Stock In" required>
                    <button type="button" class="btn btn-danger remove-size">
                        <i class="bi bi-trash"></i> Remove
                    </button>
                `;
                container.appendChild(row);
                sizeIndex++;
            });

            // Remove Size Row
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-size')) {
                    e.target.closest('.size-row').remove();
                }
            });

            // Add Ingredient Row
            document.getElementById('add-ingredient').addEventListener('click', function () {
                const container = document.getElementById('ingredients-container');
                const row = document.createElement('div');
                row.classList.add('input-group', 'mb-2', 'ingredient-row');
                row.innerHTML = `
                    <input type="text" name="ingredients[${ingredientIndex}][name]" class="form-control" placeholder="Ingredient" required>
                    <input type="number" name="ingredients[${ingredientIndex}][stock_in_ingredients]" class="form-control" placeholder="Stock In" required>
                    <button type="button" class="btn btn-danger remove-ingredient">
                        <i class="bi bi-trash"></i> Remove
                    </button>
                `;
                container.appendChild(row);
                ingredientIndex++;
            });

            // Remove Ingredient Row
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-ingredient')) {
                    e.target.closest('.ingredient-row').remove();
                }
            });
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\Family\rvshop_pos\resources\views/inventory/edit.blade.php ENDPATH**/ ?>