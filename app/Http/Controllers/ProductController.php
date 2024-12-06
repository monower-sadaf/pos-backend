<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class ProductController extends Controller
{

    public function index()
    {

        $products = Product::all();
        return response()->json($products, 200);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $product = Product::create($request->all());
            return response()->json([
                'message' => 'Product created successfully',
                'status' => true
            ], 201);
        }
    }

    public function destroy(Request $request)
    {

        $product = Product::find($request->id);
        $result = $product->delete();

        if (!$result) {
            return response()->json(['message' => 'Product not found', 'status' => false], 404);
        } else {
            return response()->json(['message' => 'Product deleted successfully', 'status' => true], 200);
        }
    }
}
