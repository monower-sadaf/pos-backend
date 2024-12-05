<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PosController extends Controller
{
    public function store(Request $request)
    {
        // Begin transaction to ensure everything is processed together
        DB::beginTransaction();

        try {
            // Validate request
            $validated = $request->validate([
                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            // Calculate totals
            $totalBeforeDiscount = 0;
            $totalDiscount = 0;
            $finalTotal = 0;

            // Create a new sale
            $sale = Sale::create([
                'total_before_discount' => 0, // Placeholder
                'total_discount' => 0, // Placeholder
                'final_total' => 0, // Placeholder
            ]);

            // Process items and calculate totals
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product->stock >= $item['quantity']) {
                    // Calculate price, discount, and trade offer discount
                    $subtotal = $product->price * $item['quantity'];
                    $discount = $item['discount'] ?? 0;
                    $tradeOfferDiscount = 0;

                    // Apply discount
                    $itemDiscount = ($discount / 100) * $subtotal;
                    $totalDiscount += $itemDiscount;

                    // Handle trade offer if applicable
                    if (isset($item['trade_offer_min_qty']) && $item['quantity'] >= $item['trade_offer_min_qty']) {
                        $freeItems = floor($item['quantity'] / $item['trade_offer_min_qty']) * $item['trade_offer_get_qty'];
                        $tradeOfferDiscount = $freeItems * $product->price;
                    }

                    $totalDiscount += $tradeOfferDiscount;
                    $finalTotal += $subtotal - $itemDiscount - $tradeOfferDiscount;

                    // Deduct stock from the product
                    $product->decrement('stock', $item['quantity']);

                    // Create sale item
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'discount' => $itemDiscount,
                        'trade_offer_discount' => $tradeOfferDiscount,
                        'subtotal' => $subtotal - $itemDiscount - $tradeOfferDiscount,
                    ]);
                } else {
                    return response()->json(['message' => 'Not enough stock for one or more products.'], 400);
                }
            }

            // Update sale total
            $sale->update([
                'total_before_discount' => $finalTotal + $totalDiscount,
                'total_discount' => $totalDiscount,
                'final_total' => $finalTotal,
            ]);

            // Commit transaction
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Sale processed successfully!',
                'sale' => $sale,
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback if anything fails
            return response()->json(['message' => 'Failed to process sale. Please try again.', 'status' => false, 'error' => $e->getMessage() ?? ''], 500);
        }
    }
}
