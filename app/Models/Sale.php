<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_before_discount',
        'total_discount',
        'final_total',
    ];

    /**
     * Define a relationship with the SaleItem model.
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
