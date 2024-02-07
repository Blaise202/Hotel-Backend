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
        Schema::create('stock_item_quantities', function (Blueprint $table) {
            $table->char('id')->primary();
            $table->char('item_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('item_id')->references('stock_items')->on('id')->ondelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_item_quantities');
    }
};