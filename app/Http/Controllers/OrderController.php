<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Sale;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'customer_name' => 'required|string',
        'items' => 'required|string', // Ensure items is passed as a JSON string
    ]);

    // Decode the items JSON
    $items = json_decode($request->input('items'), true);

    if (!$items || !is_array($items)) {
        return back()->with('error', 'Invalid items data.');
    }

    // Calculate the total cost from the items
    $totalCost = collect($items)->sum(function ($item) {
        if (!isset($item['totalPrice'])) {
            throw new \Exception('Invalid item structure: Missing totalPrice.');
        }
        return (float)$item['totalPrice'];
    });

    // Save the order
    $order = new Order();
    $order->customer_name = $request->input('customer_name');
    $order->items = json_encode($items); // Save items as JSON
    $order->total_cost = $totalCost;
    $order->status = 'pending';
    $order->save();

    return redirect()->route('orders.index')->with('success', 'Order created successfully!');
}


    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
{
    // Validate the request data
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'items' => 'required|string', // Items is passed as a JSON string
    ]);

    // Decode the JSON string into an array
    $items = json_decode($request->input('items'), true);

    // Ensure the decoded data is an array
    if (!is_array($items)) {
        return back()->withErrors(['items' => 'Invalid items format.']);
    }

    // Calculate the total cost from the items
    $totalCost = collect($items)->sum(fn($item) => $item['quantity'] * $item['unitPrice']);

    // Reset status to pending and update the order
    $order->update([
        'customer_name' => $request->input('customer_name'),
        'items' => json_encode($items), // Save items as JSON
        'total_cost' => $totalCost,
        'status' => 'pending', // Reset status to pending
        'updated_at' => now(), // Force refresh of updated_at
    ]);

    // Delete the corresponding sales record if the order was marked as complete
    Sale::where('order_id', $order->id)->delete();

    return redirect()->route('orders.index')->with('success', 'Order updated successfully! Mark as Complete has been reset.');
}



    public function destroy(Order $order)
    {
        Sale::where('order_id', $order->id)->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order and related sales data deleted successfully.');
    }

    public function markComplete($id)
    {
        $order = Order::findOrFail($id);
    
        // Update order status
        $order->status = 'completed';
        $order->save();
    
        // Decode items in the order
        $items = json_decode($order->items, true);
    
        foreach ($items as $item) {
            $product = Inventory::where('product_name', $item['name'])->first();
    
            if ($product) {
                // Handle sizes
                $sizes = json_decode($product->size, true) ?? [];
                foreach ($sizes as $key => $size) {
                    if ($size['size'] === $item['size']) {
                        $sizes[$key]['stock_in'] = max(0, $sizes[$key]['stock_in'] - $item['quantity']);
                        $sizes[$key]['sold'] = ($sizes[$key]['sold'] ?? 0) + $item['quantity'];
                    }
                }
    
                // Handle ingredients (1 product = 1 piece of each ingredient)
                $ingredients = json_decode($product->ingredients, true) ?? [];
                foreach ($ingredients as $key => $ingredient) {
                    if (isset($ingredient['stock_in_ingredients'])) {
                        $ingredients[$key]['stock_in_ingredients'] = max(
                            0,
                            $ingredients[$key]['stock_in_ingredients'] - $item['quantity']
                        );
                    }
                }
    
                // Save updated inventory
                $product->size = json_encode($sizes);
                $product->ingredients = json_encode($ingredients);
                $product->sold += $item['quantity'];
                $product->save();
            }
        }
    
        // Log sale
        Sale::updateOrCreate(
            ['order_id' => $order->id],
            [
                'order_id' => $order->id,
                'items' => $order->items,
                'total_cost' => $order->total_cost,
                'date' => now(),
            ]
        );
    
        return redirect()->back()->with('success', 'Order marked as complete and inventory updated!');
    }
    


    public function generateReceipt($id)
{
    $order = Order::findOrFail($id);
    $items = json_decode($order->items, true);
    $currentDateTime = Carbon::now('Asia/Manila');

    // Load a view and pass data for the receipt
    $pdf = \PDF::loadView('orders.receipt', compact('order', 'items', 'currentDateTime'));

    // Stream the PDF in a new tab
    return $pdf->stream("receipt_order_{$id}.pdf");
}

}
