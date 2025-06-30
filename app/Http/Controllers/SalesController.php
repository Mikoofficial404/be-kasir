<?php

namespace App\Http\Controllers;

use App\Models\Sales;

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
}
