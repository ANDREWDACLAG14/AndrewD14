<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryController extends Controller
{
    public function index()
{
    $inventories = Inventory::all()->map(function($inventory) {
        $inventory->size = json_decode($inventory->size, true);
        $inventory->ingredients = json_decode($inventory->ingredients, true);
        return $inventory;
    });
    return view('inventory.index', compact('inventories'));
}


    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'product_name' => 'required|string|max:255',
        'sizes' => 'required|array',
        'sizes.*.size' => 'required|string',
        'sizes.*.stock_in' => 'required|integer|min:1',
        'ingredients' => 'required|array',
        'ingredients.*.name' => 'required|string',
        'ingredients.*.stock_in_ingredients' => 'required|integer|min:1',
    ]);

    $sizes = $request->sizes;
    $ingredients = $request->ingredients;

    Inventory::create([
        'product_name' => $request->product_name,
        'size' => json_encode($sizes),
        'ingredients' => json_encode($ingredients),
        'sold' => 0, // You can keep 'sold' if needed
    ]);

    return redirect()->route('inventory.index')->with('success', 'Product added successfully!');
}


    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'sizes' => 'required|array',
            'sizes.*.size' => 'required|string',
            'sizes.*.stock_in' => 'required|integer|min:1',
            'ingredients' => 'required|array',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.stock_in_ingredients' => 'required|integer|min:1',
        ]);
    
        $inventory = Inventory::findOrFail($id);
        $inventory->product_name = $request->product_name;
        $inventory->size = json_encode($request->sizes);
        $inventory->ingredients = json_encode($request->ingredients);
        $inventory->save();
    
        return redirect()->route('inventory.index')->with('success', 'Product updated successfully.');
    }
    

    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Product deleted successfully!');
    }

    public function showPDF($id)
    {
        // Retrieve the inventory data
        $inventory = Inventory::findOrFail($id);
    
        // Generate the PDF using the Blade view
        $pdf = PDF::loadView('inventory.pdf', compact('inventory'));
        
        // Stream the PDF to the browser with the filename 'inventory_pdf.pdf'
        return $pdf->stream("inventory_{$id}_pdf.pdf");
    }
    
 
    
}
