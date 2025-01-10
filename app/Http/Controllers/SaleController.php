<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    public function index()
{
    $sales = Sale::all(); // Fetch all sales
    return view('sales.index', compact('sales'));
}


    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }

public function generateReport($filter)
{
    $sales = Sale::query();

    // Apply filtering logic
    switch ($filter) {
        case 'daily':
            $sales->whereDate('date', Carbon::today());
            break;
        case 'weekly':
            $sales->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            break;
        case 'monthly':
            $sales->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year);
            break;
        case 'yearly':
            $sales->whereYear('date', Carbon::now()->year);
            break;
    }

    $sales = $sales->get();

    // Generate the PDF
    $pdf = PDF::loadView('sales.report', compact('sales', 'filter'));
    return $pdf->stream("{$filter}_report.pdf");
}


}
