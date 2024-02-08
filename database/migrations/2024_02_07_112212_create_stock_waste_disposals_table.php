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
        Schema::create('stock_waste_disposals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('item_id');
            $table->dateTime('disposal_date');
            $table->string('disposal_method')->nullable();
            $table->integer('quantity');
            $table->longText('reason');
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('stock_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_waste_disposals');
    }
};