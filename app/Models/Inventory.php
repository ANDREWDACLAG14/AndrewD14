<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Size;
class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'product_name',
        'size',
        'stock_in',
        'ingredients',
        'stock_in_ingredients',
        'sold',
        'remaining',
    ];

    protected $casts = [
        'size' => 'array',
        'ingredients' => 'array',
    ];
    
}
