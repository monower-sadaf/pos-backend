<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;


    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'discount',
        'trade_offer_discount',
        'subtotal',
    ];

    /**
     * Define a relationship with the Sale model.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Define a relationship with the Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
