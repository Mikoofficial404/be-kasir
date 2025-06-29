<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'Data Product Not Found',
                'success' => false,
            ], 422);
        }

        $products = $products->map(function ($product) {
            $product->photo_product = asset('storage/products/' . $product->photo_product);

            return $product;
        });

        return response()->json([
            'message' => 'Get All Resource Data Product',
            'data' => $products,
            'success' => true,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:100',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'photo_product' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'success' => false,
            ], 422);
        }

        $image = $request->file('photo_product');
        $image->store('products', 'public');
        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'photo_product' => $image->hashName(),
        ]);

        return response()->json([
            'message' => 'Product Has Created',
            'success' => true,
            'data' => $product,
        ], 200);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (! $product) {
            return response()->json([
                'message' => 'Data Product Not Found',
                'success' => false,
            ], 404);
        }

        $product->photo_product = asset('storage/products/' . $product->photo_product);

        return response()->json([
            'message' => 'Get All Data Product Resource',
            'success' => true,
            'data' => $product,
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        $product = Product::find($id);
        if (! $product) {
            return response()->json([
                'message' => 'Data Product Not Found',
                'success' => false,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:100',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'photo_product' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'success' => false,
            ], 422);
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ];

        if ($request->hasFile('photo_product')) {
            $image = $request->file('photo_product');
            $image->store('products', 'public');
            if ($product->photo_product) {
                Storage::disk('public')->delete('products/' . $product->photo_product);
            }
            $data['photo_product'] = $image->hashName();
        }

        return response()->json([
            'message' => 'Product Updated',
            'success' => true,
            'data' => $product->update($data),
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (! $product) {
            return response()->json([
                'message' => 'Product Not Found',
                'success' => false,
            ], 404);
        }

        if ($product->photo_product) {
            Storage::disk('public')->delete('products/' . $product->photo_product);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product Deleted',
            'success' => true,
        ], 200);
    }
}
