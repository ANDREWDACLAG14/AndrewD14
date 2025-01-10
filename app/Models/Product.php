<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Specify the fields that can be mass-assigned
    protected $fillable = ['product_name', 'sizes', 'ingredients'];

    // Cast 'sizes' and 'ingredients' to arrays so they are handled as JSON
    protected $casts = [
        'sizes' => 'array',
        'ingredients' => 'array',
    ];
}
