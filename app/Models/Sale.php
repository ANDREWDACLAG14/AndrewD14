<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'items',
        'total_cost',
        'date', // Ensure 'date' is fillable
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
