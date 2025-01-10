<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    // Define the inverse relationship
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);  // Adjust the Inventory model path if needed
    }
}