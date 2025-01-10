<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('sales', function (Blueprint $table) {
        $table->unsignedBigInteger('order_id')->nullable(); // Add the order_id column
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade'); // Add foreign key constraint
    });
}

public function down()
{
    Schema::table('sales', function (Blueprint $table) {
        $table->dropForeign(['order_id']);
        $table->dropColumn('order_id');
    });
}

};
