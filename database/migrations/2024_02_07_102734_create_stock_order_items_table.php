<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_order_items', function (Blueprint $table) {
            $table->char('id')->primary();
            $table->char('order_id');
            $table->char('item_id');
            $table->integer('quantity');
            $table->double('unit_price');
            $table->double('total_price');
            $table->timestamps();


            $table->foreign('order_id')->references('stock_orders')->on('id')->onDelete('cascade');
            $table->foreign('item_id')->references('stock_items')->on('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_order_items');
    }
};