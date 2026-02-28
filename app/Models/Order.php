<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_no',
        'status',
        'quantity',
        'subtotal',
        'shipping_cost',
        'discount',
        'total',
        'commission',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
