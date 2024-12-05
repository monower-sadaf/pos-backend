<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Validator;

class PosController extends Controller
{
    public function store(Request $request)
    {
        return $request->all();
        /* try {
            $validated = $request->validate([
                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.discount' => 'nullable|integer|min:0',
            ]);

            $totalDiscount = 0;
            $finalTotal = 0;
            $updatedProducts = [];

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);

                if ($product && $product->stock >= $item['quantity']) {

                    $amount = $product->price * $item['quantity'];

           
                    $discount = 0;
                    if (isset($item['discount']) && $item['discount'] > 0) {
                        $discount = ($item['discount'] / 100) * $amount;
                    }

                    $totalDiscount += $discount;

                    
                    $product->decrement('stock', $item['quantity']);

                   
                    $updatedProducts[] = [
                        'product_id' => $product->id,
                        'updated_stock' => $product->stock
                    ];

                   
                    $finalTotal += ($amount - $discount);
                } else {
                    
                    return response()->json(['message' => 'Not enough stock for one or more products.'], 400);
                }
            }

            
            return response()->json([
                'message' => 'Sale processed successfully!',
                'updated_products' => $updatedProducts,
                'total_discount' => $totalDiscount,
                'final_total' => $finalTotal
            ]);
        } catch (\Exception $e) {
            
            return response()->json(['message' => 'Failed to process sale. Please try again.'], 500);
        } */
    }
}
