<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sales;
use App\Models\SalesDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalesDetailsController extends Controller
{
    public function index()
    {
        $salesDetails = SalesDetails::with('user', 'product')->get();

        if ($salesDetails->isEmpty()) {
            return response()->json([
                'message' => 'data Not Found',
                'success' => false,
            ], 422);
        }

        return response()->json([
            'data' => $salesDetails,
            'message' => 'Get All Resource',
            'success' => true,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'success' => false,
                'data' => $validator->errors(),
            ], 422);
        }

        $uniquecode = 'ORD-' . strtoupper(uniqid('', true));

        $user = auth('api')->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $product = Product::find($request->product_id);
        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
            ], 401);
        }

        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stock Not Enough',
            ], 400);
        }

        $total = $product->price * $request->quantity;

        // $subtotal = $product->price + $product->price;
        $product->stock -= $request->quantity;
        $product->save();

        $sales = Sales::create([
            'sales_date' => now()->toDateString(),
            'kasir_id' => $user->id,
            'total_amount' => $total,
            'status' => 'paid',
        ]);

        $salesDetails = SalesDetails::create([
            'order_number' => $uniquecode,
            'kasir_id' => $user->id,
            'sales_id' => $sales->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_amount' => $total,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction Success',
            'data' => $salesDetails,
        ], 200);
    }

    public function show(SalesDetails $salesDetails)
    {
        return $salesDetails;
    }

    public function update(Request $request, $id)
    {
        $salesDetails = SalesDetails::find($id);
        if (! $salesDetails) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction Not Found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        $product = Product::find($request->product_id);
        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
            ], 404);
        }

        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stock Not Enough',
            ], 400);
        }

        $total = $product->price * $request->quantity;

        $product->stock -= $request->quantity;
        $product->save();

        if ($salesDetails->sales) {
            $salesDetails->sales->update([
                'total_amount' => $total,
            ]);
        }

        $salesDetails->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_amount' => $total,
        ]);

        return response()->json([
            'message' => 'Transaction Updated',
            'success' => true,
            'data' => $salesDetails,
        ], 200);
    }
}
