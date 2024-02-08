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
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->uuid('item_id');
            $table->integer('quantity');
            $table->double('unit_price');
            $table->double('total_price');
            $table->timestamps();


            $table->foreign('order_id')->references('id')->on('stock_orders')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('stock_items')->onDelete('cascade');
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