<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration
{
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->string('name');  // Ingredient name (e.g., Flour, Sugar)
            $table->integer('stock_in_ingredients');  // Stock quantity for each ingredient
             $table->unsignedBigInteger('inventory_id');  // Ensure correct type for the foreign key
            $table->foreign('inventory_id')->references('id')->on('inventory')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ingredients');
    }
}
