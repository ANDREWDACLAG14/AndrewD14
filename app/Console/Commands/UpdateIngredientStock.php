<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inventory;

class UpdateIngredientStock extends Command
{
    protected $signature = 'update:ingredient-stock';
    protected $description = 'Add missing stock_in_ingredients key to all inventory ingredients';

    public function handle()
    {
        $inventories = Inventory::all();

        foreach ($inventories as $inventory) {
            $ingredients = json_decode($inventory->ingredients, true);

            if (is_array($ingredients)) {
                foreach ($ingredients as $key => $ingredient) {
                    // Ensure each ingredient has the `stock_in_ingredients` key
                    $ingredients[$key]['stock_in_ingredients'] = $ingredient['stock_in_ingredients'] ?? 0;
                }

                // Save the updated ingredients JSON back into the database
                $inventory->ingredients = json_encode($ingredients);
                $inventory->save();
            } else {
                $this->info("Invalid ingredients data for product: {$inventory->product_name}");
            }
        }

        $this->info('Ingredient stock update completed.');
    }
}
