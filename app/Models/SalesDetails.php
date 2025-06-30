<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetails extends Model
{
    use HasFactory;

    protected $table = 'sales_details';

    protected $fillable = [
        'order_number',
        'kasir_id',
        'product_id',
        'sales_id',
        'quantity',
        'total_amount',
        // 'subtotal'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }
}
