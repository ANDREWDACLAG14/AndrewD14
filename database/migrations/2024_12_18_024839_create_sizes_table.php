<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSizesTable extends Migration
{
    public function up()
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->string('size');  // Size (e.g., Small, Medium, Large)
            $table->integer('stock_in');  // Stock quantity for each size
            $table->unsignedBigInteger('inventory_id');  // Ensure correct type for the foreign key
            $table->foreign('inventory_id')->references('id')->on('inventory')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sizes');
    }
}
