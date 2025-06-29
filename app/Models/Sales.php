<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $table = 'sales';
    protected $fillable = [
        'sales_date',
        'kasir_id',
        'total_amount',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
}
