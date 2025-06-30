<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sales::all();
        if (! $sales) {
            return response()->json([
                'message' => 'Riwayat Transaksi Tidak ada',
                'success' => false,
            ], 422);
        }

        return response()->json([
            'message' => 'Get All Resource Transaksi',
            'success' => true,
            'data' => $sales,
        ], 200);
    }

    public function exportPdf()
{
    $sales = Sales::select('sales_date', 'total_amount', 'status')->get();

    $pdf = Pdf::loadView('pdf.sales', compact('sales'));
    return $pdf->download('sales_report.pdf');
}
}
