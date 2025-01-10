<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'items',
        'total_cost',
        'status',
    ];

    public $timestamps = true;

    protected $casts = [
        'items' => 'array',
    ];

    public function sale()
    {
        return $this->hasOne(Sale::class, 'order_id');
    }
}
